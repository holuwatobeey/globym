<?php

namespace App\Yantrana\__Laraware\Core;

use App\Http\Controllers\Controller;
use View;
/**
 * CoreController - 0.1.1 - 02 MAY 2018.
 * 
 *-------------------------------------------------------- */
abstract class CoreController extends Controller 
{

    /**
     * Load view helper
     *
     * @param string  $viewName    - View Name
     * @param array  $data         - Array of data if needed
     * 
     * @return array
     *-------------------------------------------------------------------------- */
    public function loadView($viewName, $data = [])
    {
        $output = View::make($viewName, $data)->render();

        if (env('APP_DEBUG', false) === false) {
            $filters = array(
                '/(?<!\S)\/\/\s*[^\r\n]*/'  => '',  // Remove comments in the form /* */
                '/\s{2,}/'                  => ' ', // Shorten multiple white spaces
                '/(\r?\n)/'                 => '',  // Collapse new lines
            );

            $output = preg_replace(
                array_keys($filters),
                array_values($filters),
                $output
            );
        } 

        $clogSessItemName = '__clog';
        if (!empty(config('app.'.$clogSessItemName, []))) {

            $responseData = [
                '__dd'              => true,
                '__clogType'        => 'NonAjax',
                $clogSessItemName   => config('app.'. $clogSessItemName),
            ];

            //reset the __clog items in session
            config(['app.' . $clogSessItemName => [] ]);
            $output = $output.'<script type="text/javascript">__globals.clog('.json_encode($responseData).');</script>';
        }

        return $output;
    }
}