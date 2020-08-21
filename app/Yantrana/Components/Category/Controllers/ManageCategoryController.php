<?php
/*
* ManageCategoryController.php - Controller file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category\Controllers;

use App\Yantrana\Core\BaseController;
use Illuminate\Http\Request;
use App\Yantrana\Components\Category\Categories;
use App\Yantrana\Components\Category\ManageCategoryEngine;
use App\Yantrana\Components\Category\Requests\CategoryAddRequest;
use App\Yantrana\Components\Category\Requests\ManageCategoryEditRequest;
use App\Yantrana\Components\Category\Requests\CategoryDeleteRequest;

class ManageCategoryController extends BaseController
{
    /**
     * @var ManageCategoryEngine - Category Engine
     */
    protected $manageCategoryEngine;

    /**
     * @var Categories - Categories
     */
    protected $categories;

    /**
     * Constructor.
     *
     * @param ManageCategoryEngine $manageCategoryEngine - Category Engine
     * @param Categories           $categories           - Categories
     *-----------------------------------------------------------------------*/
    public function __construct(ManageCategoryEngine $manageCategoryEngine, Categories $categories)
    {
        $this->manageCategoryEngine = $manageCategoryEngine;
        $this->categories = $categories;
    }

    /**
     * Take all_child_categories.
     *
     * @param (object) $itemCollection.
     * @param (int)    $itemID.
     * @param (array)  $activeItemsContainer.
     *
     * @return array
     *------------------------------------------------------------------------ */
    private function findChildrens($itemCollection, $itemID = null, $activeItemsContainer = [])
    {
        $itemID = (int) $itemID;

        foreach ($itemCollection as $item) {
            if ($item->parent_id == (int) $itemID) {
                $activeItemsContainer[] = [
                    'id' => $item->id,
                    'status' => $item->status,
                ];

                $activeItemsContainer = $this->findChildrens(
                                            $itemCollection,
                                            $item->id,
                                            $activeItemsContainer
                                        );
            }
        }

        return $activeItemsContainer;
    }

    /**
     * Manipulation for active  & inactive category count.
     *
     * @param int $ID
     *
     * @return array
     *---------------------------------------------------------------- */
    private function findChildStatus($childCategories)
    {
        $activeCatcount = $inActiveCatcount = [];

        // Check if child category exists
        if (!__isEmpty($childCategories)) {
            foreach ($childCategories as $category) {
                if ($category['status'] === 1) {
                    $activeCatcount[] = $category['id'];
                } else {
                    $inActiveCatcount[] = $category['id'];
                }
            }
        }        

        return [
            'active' => count($activeCatcount),
            'inActive' => count($inActiveCatcount),
        ];
    }

    /**
     * Prepare Children Status.
     *
     * @param array $categoryCollection
     * @param array $colleectionContainer
     *
     * @return json
     *---------------------------------------------------------------- */
    private function prepareChildrenStatus($categoryCollection, $collectionContainer = [])
    {
        foreach ($categoryCollection['child_categories_recursive'] as $category) {
            $collectionContainer[] = [
                'id'     => $category['id'],
                'status' => $category['status']
            ];
            // Check if children category exists
            if(isset($category['child_categories_recursive']) and !__isEmpty($category['child_categories_recursive'])) {
                $collectionContainer = $this->prepareChildrenStatus($category, $collectionContainer);
            }
        }
        return $collectionContainer;
    }

    /**
     * get all categories.
     *
     * @return json
     *---------------------------------------------------------------- */
    public function index(Request $request, $mCategoryID)
    {
        $categories = $this->manageCategoryEngine
                            ->getCategories($mCategoryID, $request->all());

        $currentCategories = $currentCategoryProductsCounts = $categoryChildrenCounts = [];
        if (!__isEmpty($categories['data'])) {
            $categoryIds = array_pluck($categories['data'], 'id');
            $currentCategoryProductsCounts = $this->manageCategoryEngine->getCurrentCategoryProducts($categoryIds);
            foreach ($categories['data'] as $category) {
                $currentCategories[$category['id']] = $this->prepareChildrenStatus($category);
            }
            $categoryChildrenCounts = $this->manageCategoryEngine->getChildCategoriesProducts($currentCategories);
        }
        
        $requireColumns = [

           'childCount' => function ($key) use ($currentCategories) {
                // return active | deactive category count
                if (array_key_exists($key['id'], $currentCategories)) {
                    return $this->findChildStatus($currentCategories[$key['id']]);
                }

                return 0;
           },
            'currentCategoryProductsCount' => function ($key) use($currentCategoryProductsCounts) {
                return array_key_exists($key['id'], $currentCategoryProductsCounts) ? $currentCategoryProductsCounts[$key['id']] : 0;
            },
            'totalCategoryProductCount' => function ($key) use($categoryChildrenCounts) {
                if (array_key_exists($key['id'], $categoryChildrenCounts)) {
                    return $categoryChildrenCounts[$key['id']];
                }
                return 0;
            },
            'id', 'name', 'status',
            'canAccessEdit' => function() {
                if (canAccess('manage.category.get.details') and canAccess('manage.category.update')) {
                    return true;
                }
                return false;
            },
            'canAccessDelete' => function() {
                if (canAccess('manage.category.delete')) {
                    return true;
                }
                return false;
            },
            'canAccessAddProduct' => function() {
                if (canAccess('manage.product.add.supportdata') and canAccess('manage.product.add') and canAccess('manage.product.list')) {
                    return true;
                }
                return false;
            },
            'canAccessCategoryProductList' => function() {
                if (canAccess('manage.category.product.list') and canAccess('manage.product.list')) {
                    return true;
                }
                return false;
            },
            'canAccessAddSubCategory' => function() {
                if (canAccess('manage.category.add')) {
                    return true;
                }
                return false;
            },
            'canAccessCategoryList' => function() {
                if (canAccess('manage.category.list')) {
                    return true;
                }
                return false;
            }
        ];

        return __dataTable($categories, $requireColumns);
    }

    /**
     * getDetails.
     *
     * @param catID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getSupportData($catID)
    {
        if (empty($catID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->manageCategoryEngine
                                ->getSupportData($catID);

        // get engine reaction
        return __processResponse($processReaction, [
                    18 => __tr('Category does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * get all categories for fancytree.
     *---------------------------------------------------------------- */
    public function fancytreeSupportData()
    {
        $processReaction = $this->manageCategoryEngine
                                ->getAllCategories();

        return __processResponse($processReaction, [], $processReaction['data']);
    }

    /**
     * Handle add category request.
     *
     * @param object CategoryAddRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function add(CategoryAddRequest $request)
    {
        $processReaction = $this->manageCategoryEngine
                                ->processAdd($request->all());

        // get engine reaction
        return __processResponse($processReaction, [
                    1 => __tr('Category added successfully.'),
                    18 => __tr('Parent category not exist.'),
                    3 => __tr('Category name already exist.'),
                ], $processReaction['data']);
    }

    /**
     * getDetails.
     *
     * @param catID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getDetails($catID)
    {
        if (empty($catID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->manageCategoryEngine
                                ->getDetails($catID);

        // get engine reaction
        return __processResponse($processReaction, [
                    18 => __tr('Category does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * update category data.
     *
     * @param $catID
     * @param ManageCategoryEditRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function update(ManageCategoryEditRequest $request, $catID)
    {
        if (empty($catID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->manageCategoryEngine
                                ->processUpdate($request->all(), $catID);

        // get engine reaction
        return __processResponse($processReaction, [
                    1 => __tr('Category updated successfully.'),
                    14 => __tr('Nothing updated.'),
                    18 => __tr('Category does not exist.'),
                    3 => __tr('Category name already exist.'),
                ], $processReaction['data']);
    }

    /**
     * delete category.
     *
     * @param int $categoryID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($categoryID, CategoryDeleteRequest $request)
    {
        if (empty($categoryID)) {
            return __apiResponse([], 7);
        }

        $enginReaction = $this->manageCategoryEngine->processDelete($categoryID, $request->all());

        // get engine reaction
        return __processResponse($enginReaction, [
                    1 => __tr('Category deleted successfully.'),
                    3 => __tr('Invalid password. please enter correct password.'),
                    18 => __tr('Category does not exist.'),
                ], $enginReaction['data']);
    }
}
