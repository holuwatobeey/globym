<?php
/*
* ManagePagesController.php - Controller file
*
* This file is part of the Pages component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Pages\Controllers;

use Illuminate\Http\Request;
use App\Yantrana\Support\CommonPostRequest;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Pages\ManagePagesEngine;
use App\Yantrana\Components\Pages\Requests\ManagePagesAddRequest;
use App\Yantrana\Components\Pages\Requests\ManagePagesEditRequest;
use Carbon\Carbon;

class ManagePagesController extends BaseController
{
    /**
     * @var ManagePagesEngine - ManagePages Engine
     */

    /**
     * Constructor.
     *
     * @param ManagePagesEngine $managePagesEngine - ManagePages Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ManagePagesEngine $managePagesEngine)
    {
        $this->managePagesEngine = $managePagesEngine;
    }

    /**
     * Handle page list request.
     *
     * @param int $pageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function index(Request $request, $pageID)
    {
        $pages = $this->managePagesEngine
                            ->getDatableSource($pageID, $request->all());

        $requireColumns = [

            // created page date
            'formatted_created_at' => function ($key) {
                return formatDateTime($key['created_at']);
            },
            'formatCreatedData' => function ($key) {
                $createdDate = Carbon::parse($key['created_at']);
                return $createdDate->diffForHumans();
            },
            'slug_title' => function ($key) {
                return slugIt($key['title']);
            },
            // updated page date
            'formatted_updated_at' => function ($key) {
                return formatDateTime($key['updated_at']);
            },
            'formatUpdatedData' => function ($key) {
                $createdDate = Carbon::parse($key['updated_at']);
                return $createdDate->diffForHumans();
            },
            // page active or not
            'active' => function ($key) {
                return getTitle($key['status'], '__tech.pages_status_codes');
            },
            // page add to menu or not
            'add_to_menu' => function ($key) {
                return getTitle($key['add_to_menu'], '__tech.pages_status_codes');
            },
            // page type
            'formated_type' => function ($key) {
                return getTypeTitle($key['type']);
            },
            //  external_page
            'external_page' => function ($key) {
                return route('display.page.details', [
                        'pageID' => $key['id'],
                        'pageName' => slugIt($key['title']),
                    ]);
            },
            // page type
            'link' => function ($key) {
                $link = json_decode($key['link_details'], true);

                return [
                   'url' => $link['value'],
                   'target' => $link['type'],
                ];
            },
            'page_title' => function ($key) {
                return $this->isSystemDefinePage($key['id']);
            },
            'id',
            'title',
            'parent_id',
            'list_order',
            'type',
            'canAccessEdit' => function() {
                if (canAccess('manage.pages.get.details')) {
                    return true;
                }
                return false;
            },
            'canAccessDelete' => function() {
                if (canAccess('manage.pages.delete')) {
                    return true;
                }
                return false;
            }
        ];

        return __dataTable($pages, $requireColumns);
    }

    /**
     * Check if it is system define page or not.
     *
     * @param string $pageTitle
     *
     * @return array
     *---------------------------------------------------------------- */

    protected function isSystemDefinePage($pageId)
    {
        // Get system link array
        $systemLinkArray = config('__tech.system_links');

        $configItem = [];
        $pageName = '';

        // Get page name and page id
        foreach ($systemLinkArray as $key => $item) {
            if ($item == $pageId) {
                $pageName = $key;
            }
        }

        return [
            'pageName' => $pageName,
            'pageId'   => $pageId
        ];
    }

    /**
     * Handle get pages type request.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getPagesType()
    {
        $pageType = getSelectizeOptions(
                    '__tech.pages_types',
                    '__tech.pages_type_codes'
                );

        $pageLinks = getSelectizeOptions(
                    '__tech.link_target',
                    '__tech.link_target_array'
                );

        // get engine reaction
        return __processResponse(1,
                    [],
                    [
                        'type' => $pageType,
                        'link' => $pageLinks,
                        'fancytree_data' => $this->managePagesEngine
                                                        ->getPagesData(),
                 ]);
    }

    /**
     * Handle update list order request.
     *
     * @param array Request $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function updateListOrder(CommonPostRequest $request)
    {
        $processReaction = $this->managePagesEngine
                                ->prepareListOrder(
                                    $request->input('pages_list_order')
                                );

        // get engine reaction
        return __processResponse($processReaction, [
                    1 => __tr('List order updated successfully.'),
                    14 => __tr('Nothing updated'),
                ], $processReaction['data']);
    }

    /**
     * Handle add new page request.
     *
     * @param ManagePagesAddRequest $request
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function add(ManagePagesAddRequest $request)
    {
        $processReaction = $this->managePagesEngine
                                ->prepareForAddNewPage($request->all());

        return __processResponse($processReaction, [
                    1 => __tr('Page added successfully.'),
                    2 => __tr('Page not added.'),
                ]);
    }

    /**
     * Handle details data request.
     *
     * @param int $pageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getDetails($pageID)
    {
        $processReaction = $this->managePagesEngine->getDetails($pageID);

        return __processResponse($processReaction, [
                18 => __tr('Page does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * Handle update page request.
     *
     * @param numeric                         $pageID
     * @param array    ManagePagesEditRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function update($pageID, ManagePagesEditRequest $request)
    {
        $processReaction = $this->managePagesEngine
                                ->processUpdate($pageID, $request->all());

        return __processResponse($processReaction, [
                    1 => __tr('Page updated successfully.'),
                    18 => __tr('Page does not exist.'),
                    14 => __tr('Nothing update.'),
                ]);
    }

    /**
     * Handle delete page data request.
     *
     * @param int $pageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($pageID, CommonPostRequest $request)
    {
        $processReaction = $this->managePagesEngine->processDelete($pageID);

        return __processResponse($processReaction, [
                    1 => __tr('Page deleted successfully.'),
                    3 => __tr('Something went wrong.'),
                    18 => __tr('Page does not exist.'),
                    2 => __tr('This menu/page item can not be deleted because it contains childrens items.'),
                ]);
    }

    /**
     * Handle display page details request.
     *
     * @param int $pageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function displayPageDetails($pageID)
    {
        $processReaction = $this->managePagesEngine
                                ->getDetails($pageID);

        // get engine reaction
        return __processResponse($processReaction, [
                    18 => __tr('Page does not exist.'),
                ], null, true);
    }

    /**
     * Handle get parent page data request.
     *
     * @param int $pageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getParentPage($pageID)
    {
        $processReaction = $this->managePagesEngine
                                ->getParentPageData($pageID);

        return __processResponse($processReaction, [
                    18 => 'Page does not exist.',
                ], $processReaction['data']);
    }
}
