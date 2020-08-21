<?php
/*
* ManageCategoryEngine.php - Main component file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category;

use App\Yantrana\Components\Category\Repositories\ManageCategoryRepository;
use App\Yantrana\Components\SpecificationsPreset\Repositories\SpecificationRepository;

class ManageCategoryEngine
{
    /**
     * @var - Category Repository
     */
    protected $manageCategoryRepository;

    /**
     * @var  SpecificationPresetRepository $specificationRepository - SpecificationPreset Repository
     */
    protected $specificationRepository;

    /**
     * Constructor.
     *
     * @param ManageCategoryRepository $manageCategoryRepository - Category Repository
     *-----------------------------------------------------------------------*/
    public function __construct(ManageCategoryRepository $manageCategoryRepository,
     SpecificationRepository $specificationRepository)
    {
        $this->manageCategoryRepository = $manageCategoryRepository;
        $this->specificationRepository = $specificationRepository;
    }

    /**
     * get all categores list.
     *
     * @param int $categoryID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getCategories($categoryID, $searchText = null)
    {
        return $this->manageCategoryRepository
                        ->fetchCategories(
                                $categoryID,
                                $searchText
                            );
    }

    /**
     * description.
     *
     * @param $categoryID
     * @param $input
     *
     * @return reaction code number
     *---------------------------------------------------------------- */
    public function updateStatus($categoryID, $input)
    {
        // fetch category record
        $repoResponse = $this->manageCategoryRepository->fetch($categoryID);

        // if check reaction of request
        if (empty($repoResponse)) {
            return __engineReaction(2);
        }

        $updateResponse = $this->manageCategoryRepository
                               ->updateStatus($repoResponse, $input);
        // response reaction from repository
        return __engineReaction(1, $updateResponse);
    }

    /**
     * process delete category.
     *
     * @param int $categoryID
     *
     * @return engine rection
     *---------------------------------------------------------------- */
    public function processDelete($categoryID, $input)
    {
        // fetch category record
        $category = $this->manageCategoryRepository->fetch($categoryID);

        // if check reaction of request
        if (empty($category)) {
            return __engineReaction(18);
        }

        $deleteResponse = $this->manageCategoryRepository
                               ->delete($category, $input);

        if ($deleteResponse == 1) {
            // response on request delete
            return __engineReaction(1);
        }

        return __engineReaction(3);
    }

    /**
     * get all categories list.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getAll()
    {
        // fetch category record
        return $this->manageCategoryRepository->fetchWithoutCacheAll();
    }

    /**
     * get all categories list.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getAllCategories()
    {   
        return __engineReaction(1, [
                'categories' => fancytreeSource($this->getAll())
            ]);
    }

    /**
     * Process add caregory.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAdd($inputData)
    {
        if (isset($inputData['parent_cat'])
            and !__isEmpty($inputData['parent_cat'])
            and !$inputData['parent_cat'] == 0) {
            $parentCategoryID = $inputData['parent_cat'];

            $parentCategory = $this->manageCategoryRepository
                                     ->findByID($parentCategoryID);

            // Check if parent category empty
            if (__isEmpty($parentCategory)) {
                return __engineReaction(18);
            }
        } else {
            $inputData['parent_cat'] = null;
        }

        // Check if category added
        if ($this->manageCategoryRepository->store($inputData)) {
            return __engineReaction(1);
        }

        return __engineReaction(18);
    }

    /**
     * get detail of category.
     *
     * @param int $categoryID
     *---------------------------------------------------------------- */
    public function getSupportData($categoryID)
    {
        // fetch category record
        $repoResponse = $this->manageCategoryRepository->fetch($categoryID);

        if (__isEmpty($repoResponse)) {
            return __engineReaction(18);
        }

        $parentData = null;
        
        if (!__isEmpty($repoResponse->parent_id)) {
            $parentData = $this->getRelatedCategoryData($repoResponse->parent_id);
        }

        $category = isParentCategoryInactive(getAllCategories(), $categoryID);
        
        // if check reaction of request
        return __engineReaction(1, [
                'categoryData' => $repoResponse,
                'parentData'   => $parentData,
                'isInactiveParent' => ($category === false) ? true : false
            ]);
    }

    /**
     * get related category data.
     *
     * @param int $categoryID
     *---------------------------------------------------------------- */
    public function getRelatedCategoryData($categoryID)
    {
        // fetch category record
        $categoryData = $this->manageCategoryRepository->fetch($categoryID);

        // Check if category data is exist
        if (__isEmpty($categoryData)) {
            return __engineReaction(18);
        }

        $parentCategoryId = null;

        if (!__isEmpty($categoryData->parent_id)) {
            $parentCategoryId = $categoryData->parent_id;
        }

        $parentData = [
            'id'            => $categoryData->id,
            'title'         => $categoryData->name,
            'parentCateId'  => $parentCategoryId,
        ];

        return $parentData;
    }

    /**
     * get detail of category.
     *
     * @param int $categoryID
     *---------------------------------------------------------------- */
    public function getDetails($categoryID)
    {
        // fetch category record
        $repoResponse = $this->manageCategoryRepository->fetch($categoryID);

        if (__isEmpty($repoResponse)) {
            return __engineReaction(18);
        }

        $repoResponse->active = ($repoResponse->status == 1) ? true : false;

        $repoResponse['categories'] = fancytreeSource($this->getAll());
        
        // if check reaction of request
        if ($repoResponse) {
            return __engineReaction(1, $repoResponse);
        }
    }

    /**
     * process update of category data.
     *
     * @param array $input
     * @param int   $catID
     *
     * @return engine response
     *---------------------------------------------------------------- */
    public function processUpdate($input, $catID)
    {
        // fetch category record
        $category = $this->manageCategoryRepository->fetch($catID);

        // if check reaction of request
        if (empty($category)) {
            return __engineReaction(18);
        }

        $status = 0;
        if ($input['active'] == true) {
            $status = 1;
        }

        $input['status'] = $status;
        // check in database input name is unique or not
        $uniqueCategory = $this->manageCategoryRepository
                                ->checkUniqueRecord($input, $category['id']);

        // check response
        if (empty($uniqueCategory)) {
            return __engineReaction(3);
        }

         // updated record response
        $repoResponse = $this->manageCategoryRepository
                              ->update($input, $category);

           // if check reaction of request
        if ($repoResponse) {
            return __engineReaction(1, $repoResponse);
        }

        return __engineReaction(14);
    }

    /**
     * get categories products.
     *
     * @param int $categoryID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getChildCategoriesProducts($childCategories)
    {
        $productsCategoriesCollection = $this->manageCategoryRepository->fetchAllProductsCategories();
        $categoriesGroupCollection = $productsCategoriesCollection->groupBy('categories_id')->all();
        $childCategoriesProductCount = [];
        // Check if child category and category group collection exists.
        if ((!__isEmpty($childCategories)) and (!__isEmpty($categoriesGroupCollection))) {
            foreach ($childCategories as $childCategoryKey => $childCategoryValue) {
                $categoryCount = 0;
                foreach ($childCategoryValue as $childCategory) {
                    if (array_key_exists($childCategory['id'], $categoriesGroupCollection)) {
                        $categoryCount = $categoryCount + count($categoriesGroupCollection[$childCategory['id']]);
                        $childCategoriesProductCount[$childCategoryKey] = $categoryCount;
                    }
                }
            }
        }
        
        return $childCategoriesProductCount;
    }

    /**
     * get current categories products.
     *
     * @param int $categoryID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getCurrentCategoryProducts($categoryIds)
    {
        $categoryProductsCounts = [];
        // fetch categories products
        $categoryProducts = $this->manageCategoryRepository->fetchCategoriesProducts($categoryIds);
        
        if (!__isEmpty($categoryProducts)) {
            $groupProductsCategory = $categoryProducts->groupBy('categories_id')->toArray();
            foreach ($groupProductsCategory as $productCategoryKey => $productCategoryValue) {
                $categoryProductsCounts[$productCategoryKey] = count($productCategoryValue);
            }
        }
        
        return $categoryProductsCounts;
    }
}
