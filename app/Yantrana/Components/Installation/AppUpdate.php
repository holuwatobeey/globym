<?php
/*
* AppUpdate.php - Controller file
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Installation;
use App\Yantrana\Components\Store\ManageStoreEngine;
use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Components\Installation\Requests\UpdatePayloadRequest;

use DB;
use Exception;
use Schema;
use Artisan;
use GuzzleHttp\Client as GuzzleHttpClient;
use ZipArchive;
use Response;

class AppUpdate
{
    /**
     * @var ManageStoreEngine - ManageStore Engine
     */
    protected $manageStoreEngine;

    /**
     * @var String - project destination
     */
    protected $projectDestination = ''; // temp

    /**
     * Constructor.
     *
     *-----------------------------------------------------------------------*/
    public function __construct(ManageStoreEngine $manageStoreEngine)
    {
        @ini_set('memory_limit', '-1');
        @ini_set('max_execution_time', '-1');
        $this->manageStoreEngine = $manageStoreEngine;
    }

    /**
     * Check for update
     *
     * @return void
     *---------------------------------------------------------------- */
    public function index()
    {
       return view('lw-system.update');
    }

    /**
     * Check for update
     *
     * @return void
     *---------------------------------------------------------------- */
    public function curlPost($url, $data = [])
    {
       $client = new GuzzleHttpClient;

        $response = $client->post($url, [
            'headers' => [
                // 'Authorization' => 'Bearer YOUR_TOKEN_HERE',
            ],
            'form_params' => $data,
        ]);

        // You need to parse the response body
        // This will parse it into an array
        $response = json_decode($response->getBody(), true);

        return $response;
    }

    /**
     * Perform for update
     *
     * @return void
     *---------------------------------------------------------------- */
    public function downloadUpdate(UpdatePayloadRequest $request)
    {
        $response = [];
        $requestData = $request->all();

        try {

            $requestData['client_user_info'] = [
                'http_host' => array_get($_SERVER, 'HTTP_HOST', ''),
                'http_origin' => array_get($_SERVER, 'HTTP_ORIGIN', ''),
                'remote_addr' => array_get($_SERVER, 'REMOTE_ADDR',''),
                'http_user_agent' => array_get($_SERVER, 'HTTP_USER_AGENT', ''),
                'http_referer' => array_get($_SERVER, 'HTTP_REFERER', ''),
            ];
            
            $filePath = $this->curlPost(config('lwSystem.app_update_url').'/api/app-update/download-files', $requestData);

            // dd($filePath);

            $fileData =  array_get($filePath, 'data');

            if(array_get($filePath, 'error')) {
                $response = [
                    'error' => true,
                    'message' =>  array_get($filePath, 'message')
                ];

            } else {

                $zipFile = array_get($fileData, 'zip_file');
                $sqlFile = array_get($fileData, 'sql_file');

                if(!\file_exists(\storage_path('lw-updates'))) {
                    \mkdir(\storage_path('lw-updates'));
                }
                
                if( $zipFile) {
                    // download zip file
                    $this->curlDownloadFile(
                        $zipFile, 
                        \storage_path('lw-updates/update.zip'), 
                        $requestData
                    );
                }
                
                if($sqlFile) {
                    // download sql file
                    $this->curlDownloadFile(
                        $sqlFile, 
                        \storage_path('lw-updates/update.sql'), 
                        $requestData
                    );
                }

                $response = [
                    'error' => false,
                    'next_version' => array_get($fileData, 'next_version'),
                    'new_version' => array_get($fileData, 'new_version'),
                    'delete_before' => array_get($fileData, 'delete_before'),
                    'is_zip_file' => $zipFile ? true : false,
                    'is_sql_file' => $sqlFile ? true : false,
                    'message' => 'Downloaded Successfully'
                ];
            }

        } catch (Exception $e) {
            // Log the error or something
            $response = [
                'error' => true,
                'message' => $e->getMessage()
            ];

        }
    
        return \Response::json($response);
    }

    /**
     * Perform for update
     *
     * @return void
     *---------------------------------------------------------------- */
    public function performUpdate(Request $request)
    {
        $requestData = $request->all();
        $response = [];

        try {

        $isZipFile = array_get($requestData, 'download_data.is_zip_file', false);
        $isSqlFile = array_get($requestData, 'download_data.is_sql_file', false);        

        // dd($requestData);

            /** Run Database
            *---------------------------------------------------------------- */
            $sqlFilePath = \storage_path('lw-updates/update.sql');
            if($isSqlFile === true) {
                // Create connection
                $conn = new \mysqli(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'));
                // Check connection
                if ($conn->connect_error) { // on error show it
                 
                    return Response::json( [
                        'error' => true,
                        'message' => $conn->connect_error
                    ]);

                } else { // not not an error proceed
                    // check if the provided the sql file to feed base database
                    // prepare query if the file found
                    $query = '';
                    if(!\file_exists($sqlFilePath)) {
                        return Response::json( [
                            'error' => true,
                            'message' => 'SQL file not found'
                        ]);
                    }
                    // get file contents
                    $sqlScript = file($sqlFilePath);
                    // loop through the file contents
                    foreach ($sqlScript as $line)	{                    
                        $startWith = substr(trim($line), 0 ,2);
                        $endWith = substr(trim($line), -1 ,1);
                        
                        if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                            continue;
                        }

                        $query = $query . $line;
                        if ($endWith == ';') {
                            // run the queries or show the error if happen
                            if(!mysqli_query($conn,$query)) {
                                return Response::json( [
                                    'error' => true,
                                    'message' => mysqli_error($conn)
                                ]);
                            }
                            $query= '';		
                        }
                    }
                }
                // close the connection
                $conn->close();
            }
            
            /** Delete before update file
            *---------------------------------------------------------------- */
            $deleteBeforeItems = array_get($requestData, 'download_data.delete_before', []);
            if(!empty($deleteBeforeItems)) {
                foreach ($deleteBeforeItems as $deleteItem) {
                    $this->deleteRecursive(\base_path($this->projectDestination.$deleteItem));
                }
            }
            
            /** Extract new files
            *---------------------------------------------------------------- */
            $updatePath = \base_path($this->projectDestination);
            $archive = \storage_path('lw-updates/update.zip');

            if(!class_exists( 'ZipArchive' )) {
                 return Response::json( [
                    'error' => true,
                    'message' => 'ZipArchive class is required in order to use SelfUpdate'
                ]);
            }

            $zip = new ZipArchive;
            if ($zip->open($archive) === true) {
                $zip->extractTo($updatePath);
                $zip->close();

                $this->deleteRecursive(\storage_path('lw-updates'));

                $response = [
                    'error' => false,
                    'message' => 'Updated Successfully'
                ];
                // echo 'ok';
            } else {
                $response = [
                    'error' => true,
                    'message' => 'Update Failed'
                ];
            }

        } catch (Exception $e) {
            // Log the error or something
            $response = [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
        
        return Response::json($response);
    }

    /**
    * Store Customer Uid
    *
    * @param string $customerUid
    *
    * @return  json object
    *---------------------------------------------------------------- */
    public function storeRegistration(Request $request)
    {
       $requestData = $request->all();

       updateCreateArrayFileItem(
           'config/.lw_registration', 'registration_id', array_get($requestData, 'registration_id', '')
       );

       updateCreateArrayFileItem(
           'config/.lw_registration', 'your_email', array_get($requestData, 'your_email', '')
       );

        return Response::json([]);
    }

    /**
    * Download via curl
    *
    * @param string $customerUid
    *
    * @return  json object
    *---------------------------------------------------------------- */
    public function curlDownloadFile($url, $savePath, $requestData = [])
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'guzzle-download');
        $client = new GuzzleHttpClient(array(
            'base_uri' => '',
            'verify' => false,
            'sink' => $tmpFile,
            'save_to' => $savePath,//\base_path('lw-updates/update.zip'),
            'curl.options' => array(
                'CURLOPT_RETURNTRANSFER' => true,
                'CURLOPT_FILE' => $tmpFile
            )
        ));
        $res = $client->get($url, [
            'form_params' => $requestData
        ]);

        $res->getStatusCode() . "\n";
        $res->getHeaderLine('content-type') . "\n";
    }

    /**
    * Recursive delete files & folders
    *
    * @param string $customerUid
    *
    * @return  void
    *---------------------------------------------------------------- */
    public function deleteRecursive($dir)
    {
        if (is_dir($dir)) { 
            $objects = scandir($dir); 
            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") { 
                    if (is_dir($dir."/".$object)) {
                        $this->deleteRecursive($dir."/".$object);
                    } else {
                        unlink($dir."/".$object); 
                    }
                } 
            }
            rmdir($dir); 
        } 
    }
}