<?php
/*
* ManageProductEngine.php - Main component file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product;

use App\Yantrana\Core\BaseEngine;
use App\Yantrana\Components\Product\Repositories\ManageProductRepository;
use App\Yantrana\Components\Product\Blueprints\ManageProductEngineBlueprint;
use App\Yantrana\Components\Media\MediaEngine;
use App\Yantrana\Components\Category\ManageCategoryEngine;
use App\Yantrana\Components\Store\Repositories\ManageStoreRepository;
use App\Yantrana\Components\Brand\Repositories\BrandRepository;
use App\Yantrana\Components\Product\Repositories\ProductRepository;
use App\Yantrana\Components\SpecificationsPreset\Repositories\SpecificationRepository;
use Carbon\Carbon;

class ManageProductEngine extends BaseEngine implements ManageProductEngineBlueprint
{
    /**
     * @var ManageProductRepository - ManageProduct Repository
     */
    protected $manageProductRepository;

    /**
     * @var MediaEngine - Media Engine
     */
    protected $mediaEngine;

    /**
     * @var ManageStoreRepository - ManageStore Repository
     */
    protected $manageStoreRepository;

    /**
     * @var BrandRepository - ManageBrand Repository
     */
    protected $manageBrandRepository;

    /**
     * @var ProductRepository - Product Repository
     */
    protected $productRepository;

    /**
     * @var  SpecificationPresetRepository $specificationRepository - SpecificationPreset Repository
     */
    protected $specificationRepository;

    /**
     * Constructor.
     *
     * @param ManageProductRepository $manageProductRepository - ManageProduct Repository
     * @param MediaEngine             $mediaEngine             - Media Engine
     * @param ManageStoreRepository   $manageStoreRepository   - ManageStore Repository
     * @param BrandRepository         $manageBrandRepository   - ManageBrand Repository
     *-----------------------------------------------------------------------*/
    public function __construct(
        ManageProductRepository $manageProductRepository,
        MediaEngine $mediaEngine,
        ManageStoreRepository $manageStoreRepository,
        ManageCategoryEngine $manageCategoryEngine,
        BrandRepository $manageBrandRepository,
        ProductRepository $productRepository,
        SpecificationRepository $specificationRepository
    )
    {
        $this->manageProductRepository = $manageProductRepository;
        $this->mediaEngine = $mediaEngine;
        $this->manageStoreRepository = $manageStoreRepository;
        $this->manageCategoryEngine = $manageCategoryEngine;
        $this->manageBrandRepository = $manageBrandRepository;
        $this->productRepository = $productRepository;
        $this->specificationRepository = $specificationRepository;
    }

    /**
     * Prepare add product support data.
     *---------------------------------------------------------------- */
    public function prepareAddProoductSupportData($categoryId = null)
    {
        $parentData = null;
        $isParentExist = false;
        $categoryDetail = [];
        
        if ($categoryId != null) {
            $categoryData = $this->manageProductRepository
                                 ->fetchCategoryByID($categoryId);

            if (!__isEmpty($categoryData)) {
                $categoryId = $categoryData->parent_id;

                $categoryDetail = [
                    'catId' => $categoryData->id,
                    'name'  => $categoryData->name
                ];
            }

            $parentData = $this->manageCategoryEngine->getRelatedCategoryData($categoryId);

            $isParentExist = true;
        }

        $data = [
            'related_products' => $this->manageProductRepository
                                            ->fetchAll(),
            'store_currency_symbol' => getCurrencySymbol(),
            'store_currency' => getCurrency(),
            'activeBrands' => $this->getActiveBrands(),
            'categories' => fancytreeSource($this->manageCategoryEngine->getAll()),
            'parentData' => $parentData,
            'isParentExist' => $isParentExist,
            'categoryDetail' => $categoryDetail
        ];

        return __engineReaction(1, $data);
    }

    /**
     * Get Active brands.
     *---------------------------------------------------------------- */
    protected function getActiveBrands()
    {
        $activeBrands = [];
        $brands = $this->manageBrandRepository->fetchActiveWithoutCache();

        if (!empty($brands)) {
            foreach ($brands as $brand) {
                $activeBrands[] = [
                    'value' => $brand->_id,
                    'name' => $brand->name,
                ];
            }
        }

        return $activeBrands;
    }

    /**
     * Process add product based on post input data, checking if input -
     * data is valid then called the repository.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddProduct($input)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($input) {
                                 $image = $input['image'];

            // Check if selected product image thumbnail exist
            if (!$this->mediaEngine->isUserTempMedia($image)) {
                return 18;
            }

            // Check if categories exist
            if (empty($input['categories']) or !is_array($input['categories'])) {
                return 4;
            }

            // Check if product added then store image of product
            if ($productID = $this->manageProductRepository->store($input)) {
                if (!empty($productID)) {
                    $this->productId = $productID;
                }

                 // Check if product image added
                if ($this->mediaEngine->storeProductMedia($image, $productID, null, true, true)) {
                    return 1;
                }

                return 2;
            }

            return 2;
        });

        $newProductId = [];
        // Check reaction code and return productId
        if ($reactionCode == 1) {
            $newProductId = ['productId' => $this->productId];
        }

        return __engineReaction($reactionCode, $newProductId);
    }

    /**
    * Product Rating List datatable source 
    *
    * @return  array
    *---------------------------------------------------------------- */
    public function getProductRatingList()
    {
        $productRatingCollection = $this->manageProductRepository->fetchProductRatingDataTableSource();
       
        $requireColumns = [
            'productRating' => function($key) {
                return $key['productRating'];
            },
            'createdAt'  => function ($key) {
                return formatDateTime($key['created_at']);
            },
            'formatCreatedData' => function ($key) {
                $createdDate = Carbon::parse($key['created_at']);
                return $createdDate->diffForHumans();
            },
            'productName',
            'formatRating' => function($key) {
                $rating     = $key['productRating'];
                $decimal    = '';
                $rated      = floor($rating);
                $unrated    = floor(5 - $rated);
              
                if (fmod($key['productRating'], 1) != 0) { 
                    $decimal = '<i class="fa fa-star-half-o lw-color-gold"></i>';
                    $unrated = $unrated - 1;
                }
               
                return (str_repeat('<i class="fa fa-star lw-color-gold"></i>', $rated).
                    $decimal.
                    str_repeat('<i class="fa fa-star lw-color-gray"></i>', $unrated));
                
            }
        ];

        return $this->dataTableResponse($productRatingCollection, $requireColumns);

    }


    /**
     * Prepare product list.
     *---------------------------------------------------------------- */
    public function prepareProductList($categoryID = null, $brandId = null)
    {
        // Get product detail
        $productCollection = $this->manageProductRepository
                                  ->fetchDataTableSource($categoryID, $brandId);
                                   
        $requireColumns = [
            'creation_date' => function ($key) {
                return formatStoreDateTime($key['created_at']);
            },
            'formatCreatedData' => function ($key) {
                $createdDate = Carbon::parse($key['created_at']);
                return $createdDate->diffForHumans();
            },
            'thumbnail_url' => function ($key) {
                return getProductImageURL($key['id'], $key['thumbnail']);
            },
            'brand_thumbnail_url' => function ($key) {
                // __dd($key['brand']);
                return getBrandLogoURL($key['brands__id'], $key['brand']['logo']);
            },
            'detailPageURL' => function ($key) {
                $replaceString = str_replace('/', '', $key['name']);
                return route('product.details', [$key['id'], slugIt($replaceString)]);
            },
            'categories' => function ($key) {
                $categories = [];

                if (!empty($key['categories'])) {
                    foreach ($key['categories'] as $category) {
                        $categories[] = [
                            'name' => $category['name'],
                            'id' => $category['id'],
                            'status' => $category['status'],
                        ];
                    }
                }

                return $categories;
            },
            'id', 'name', 'status', 'thumbnail', 'out_of_stock', 'featured', 'brand',
            'canAccessEdit' => function() {
                if (canAccess('manage.product.edit')) {
                    return true;
                }
                return false;
            },
            'canAccessDelete' => function() {
                if (canAccess('manage.product.delete')) {
                    return true;
                }
                return false;
            },
            'canAccessViewBrandProduct' => function() {
                if (canAccess('manage.brand.product.list')) {
                    return true;
                }
                return false;
            }
        ];

        // Get category with category ID
        $fetchCategory = [];
        $parentCategoryId = null;
        $parentData = null;

        if (!empty($categoryID)) {
            $fetchCategory = $this->manageProductRepository
                                    ->fetchCategoryByID($categoryID);
            $parentCategoryId = $fetchCategory['parent_id'];
        }
        
        // Check if parent category id exist
        if (!__isEmpty($parentCategoryId)) {

            // Get parent category data
            $parentData = $this->manageCategoryEngine
                               ->getRelatedCategoryData($parentCategoryId);
        }

        // Get Brand by brandID
        $fetchBrand = [];
        if (!empty($brandId)) {
            $fetchBrand = $this->manageProductRepository
                                    ->fetchBrandByID($brandId);
        }

        return __dataTable($productCollection, $requireColumns, [
                'category'      => $fetchCategory,
                'brand'         => $fetchBrand,
                'parentData'    => $parentData
            ]);
    }

    /**
     * check if the this product is valid.
     *
     * @param object $product
     *
     * @return bool
     *---------------------------------------------------------------- */
    protected function checkIsValidCategory($productID)
    {
        // Get categories products
        $productsCategories = $this->manageProductRepository
                                   ->fetchProductsCategories($productID);

        $findActiveParents = [];

        // get all categories
        $categories = $this->productRepository->fetchCategories();

        // Check if products category exist and find its parent
        // category and check is active or not
        if (!empty($productsCategories)) {
            foreach ($productsCategories as $productCategory) {
                $categoriesIDs = $productCategory->categories_id;
                $findActiveParents[] = findActiveParents($categories, $categoriesIDs);
            }
        }

        // get active categories  & make in sigle level
        $makeArrayInSingleLevel = array_flatten($findActiveParents);

        // get active categories & get only unique
        return array_unique($makeArrayInSingleLevel);
    }

    /**
     * Process product delete request.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteProduct($productID)
    {
        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productID);

        // Chech if product exist
        if (empty($product)) {
            return __engineReaction(18);    // not exist product record
        }

        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($product) {

            // Check if product deleted & its directory deleted successfully
            if ($this->manageProductRepository->delete($product)) {
                $this->mediaEngine->processDeleteProductMedias($product->id);

                return 1; // success reaction
            }

                                 return 2; // error reaction
                             });

        // Check if return reaction code is success
        if ($reactionCode === 1) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Prepare product details.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductDetails($productID)
    {
        $product = $this->manageProductRepository->fetchDetails($productID);

        // Check if product exist
        if (empty($product)) {
            return __engineReaction(18);
        }

        return __engineReaction(1, ['product' => $product]);
    }

    /**
     * Prepare product edit details support data.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareEditDetailsSupportData($productID)
    {
        $product = $this->manageProductRepository->fetchByID($productID, true);

        // Check if product exist
        if (empty($product)) {
            return __engineReaction(18);
        }

        // Get product categories
        $product['categories'] = $this->manageProductRepository
                                         ->fetchProductCategoriesByProductID(
                                            $productID
                                         );

        // add dash(-) in the place of space
        $slugName = slugIt($product->name);

        $product['related_products'] = $this->manageProductRepository
                                            ->fetchRelatedProductsByProductID(
                                                $productID
                                            );
        // Create view page Url
        $product['viewPage'] = productsDetailsRoute($productID, $slugName);

        // Check product image and create media Url
        if (!empty($product['thumbnail'])) {
            $product['thumbnailURL'] = getProductMediaURL($productID);
        }

        // Check product status
        if ($product->status === 1) {
            $product['active'] = true;
        } else {
            $product['active'] = false;
        }

        // check product out of stock
        if (isset($product->__data['launching_date'])) {
            $product['launching_date'] = formatDate($product->__data['launching_date'], 'Y-m-d h:i');
        } 
 
        $product['outOfStock'] = $product->out_of_stock;
       
           // Prepare data for view
        $data = [
            'product' => $product,
            'related_products' => $this->manageProductRepository
                                        ->fetchAll($productID),
            'store_currency_symbol' => getCurrencySymbol(),
            'store_currency' => getCurrency(),
            'activeBrands' => $this->getActiveBrands(),
            'categories' => fancytreeSource($this->manageCategoryEngine->getAll()),
        ];

        return __engineReaction(1, $data);
    }

    /**
     * Process edit product details information.
     *
     * @param number $productID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processEditProduct($productID, $input)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $input) {

            // Get product detail
            $product = $this->manageProductRepository->fetchByID($productID);

            // Check if product exist
            if (empty($product)) {
                return 18;
            }

            // Check if categories exist
            if (empty($input['categories']) or !is_array($input['categories'])) {
                return 4;
            }

            // Check if product image selected
            if (isset($input['image'])) {
                $image = $input['image'];

                // Check if selected product image thumbnail exist
                if (!$this->mediaEngine->isUserTempMedia($image)) {
                    return 3;
                }

                $newImageThumbnail = $this->mediaEngine
                                          ->storeProductMedia(
                                                $image,
                                                $productID,
                                                $product->thumbnail,
                                                true, // generate thumb
                                                true
                                            );

                // Check if image file moved to product media
                if (!$newImageThumbnail) {
                    return 2; // error reaction
                }

                $input['image'] = $newImageThumbnail;
            }

            $inputRelatedProducts = $input['related_products'];
            $deleteRelatedProducts = [];
            $newRelatedProducts = [];
            $existingRelatedProducts = $this->manageProductRepository
                           ->fetchRelatedProductsByProductID($productID);

            if (empty($inputRelatedProducts)
                and !empty($existingRelatedProducts)) {
                $deleteRelatedProducts = $existingRelatedProducts;
            } elseif (!empty($inputRelatedProducts)
                and empty($existingRelatedProducts)) {
                $newRelatedProducts = $inputRelatedProducts;
            } elseif (!empty($inputRelatedProducts)
                and !empty($existingRelatedProducts)) {
                foreach ($inputRelatedProducts as $inputRelatedProduct) {
                    if (!in_array($inputRelatedProduct, $existingRelatedProducts)) {
                            array_push($newRelatedProducts, $inputRelatedProduct);
                        }
                    }

                foreach ($existingRelatedProducts as $existingRelatedProduct) {
                    if (!in_array($existingRelatedProduct, $inputRelatedProducts)) {
                        array_push($deleteRelatedProducts, $existingRelatedProduct);
                    }
                }
            }

            $input['related_products'] = $newRelatedProducts;
            $input['delete_related_products'] = $deleteRelatedProducts;

            $inputCategories = $input['categories'];
            $deleteCategories = [];
            $newCategories = [];
            $existingCategories = $this->manageProductRepository
                      ->fetchProductCategoriesByProductID($productID);

            // Check existance and input category
            if (empty($inputCategories)
                and !empty($existingCategories)) {
                $deleteCategories = $existingCategories;

            // Check if existingCategories are empty
            } elseif (!empty($inputCategories)
                and empty($existingCategories)) {
                $newCategories = $inputCategories;

            // Check if both are not empty
            } elseif (!empty($inputCategories)
                and !empty($existingCategories)) {

                // Check input category exist in existingCategories array or not
                foreach ($inputCategories as $inputCategory) {
                    if (!in_array($inputCategory, $existingCategories)) {
                        array_push($newCategories, $inputCategory);
                    }
                }

                // Check existing category is in inputCategories array or not
                foreach ($existingCategories as $existingCategory) {
                    if (!in_array($existingCategory, $inputCategories)) {
                        array_push($deleteCategories, $existingCategory);
                    }
                }
            }

            $input['categories'] = $newCategories;
            $input['delete_categories'] = $deleteCategories;

            // Check if product updated
            if ($this->manageProductRepository->update($product, $input)) {
                return 1;
            }

            return 2;
        });

        return __engineReaction($reactionCode);
    }

      /**
     * Process edit product details information.
     *
     * @param number $productID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processProductSendNotifyMail($productID, $notifyUserId = null, $input)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $notifyUserId, $input) {

            // Get product detail
            $product = $this->manageProductRepository->fetchByID($productID);

            // Check if product exist
            if (__isEmpty($product)) {
                return 18;
            }

            $messageData = [
                '{__productUrl__}' => productsDetailsRoute($product->id, slugIt($product->name)),
                '{__productName__}' => $product->name,
                '{__emailDescription_}' => $input['mail_description'],
            ];

            // send notification mail to user and admin
            $emailTemplateCustomer = configItem('email_template_view', 'notify_product_mail_to_customer');

            if ($input['mailType'] == 1) {
                $awatingUserMail = [];
                $deleteAwatingUserIds = [];
                if (!__isEmpty($input['awatingUserList'])) {
                    foreach ($input['awatingUserList'] as $userSelected) {
                        if ((isset($userSelected['isSelected']))
                            and ($userSelected['isSelected'] === true)) {
                            $awatingUserMail[] = $userSelected['email'];
                            $deleteAwatingUserIds[] = $userSelected['id'];
                        }
                    }
                }

                if (!__isEmpty($awatingUserMail) && !__isEmpty($deleteAwatingUserIds)) {

                    $sendNotifyBccMail = true;

                    sendDynamicMail('notify_product_mail_to_customer', $emailTemplateCustomer['emailSubject'], $messageData, $awatingUserMail, $sendNotifyBccMail);

                   // Check if Awaiting User deleted
                    if ($this->manageProductRepository->deleteMultipleAwatingUser($product->id, $deleteAwatingUserIds)) {
                       
                        return 1;
                    }
                    
                    return 2;
                }

            } else if ($input['mailType'] == 2) {
                $productNotificationUser = $this->manageProductRepository->fetchNotifyUser($notifyUserId);

                // Check if product notify user exist
                if (empty($productNotificationUser)) {
                    return 18;
                }

                sendDynamicMail('notify_product_mail_to_customer', $emailTemplateCustomer['emailSubject'], $messageData, $productNotificationUser['email']);

                // Check if product Awaiting User deleted
                if ($this->manageProductRepository->deleteAwatingUser($productNotificationUser)) {
                    return 1;
                }

                return 2;
            }
            
            return 2;
        });

        return __engineReaction($reactionCode);
    }                         

    /**
     * Add Product image if provided data valid called repository method
     * to store product image/.
     *
     * @param number productID
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddProductImage($productID, $input)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $input) {

            // fetch product
            $product = $this->manageProductRepository->fetchByID($productID);

            // Check if product exist
            if (empty($product)) {
                return 18;
            }

            $image = $input['image'];

            // Check if selected product image thumbnail exist
            if (!$this->mediaEngine->isUserTempMedia($image)) {
                return 3;
            }

            $newImageThumbnail = $this->mediaEngine
                                      ->storeProductMedia($image, $productID, null, 'productSliderImage', true);

            // Check if image file moved to product media
            if (!$newImageThumbnail) {
                return 2; // error reaction
            }

            $input['file_name'] = $newImageThumbnail;

            // Check if prdouct image added
            if ($this->manageProductRepository
                     ->storeImage($productID, $input)) {
                return 1;
            }

            return 2;
        });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare prdouct images for datatable source.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductImageList($productID)
    {
        $imagesCollection = $this->manageProductRepository
                                  ->fetchImagesDataTableSource($productID);

        $requireColumns = [
            'thumbnail_url' => function ($key) use ($productID) {
                return getProductImageURL($productID, $key['file_name']);
            },
            'id', 'title', 'list_order',
            'canAccessEdit' => function() {
                if (canAccess('manage.product.image.edit.supportdata') and canAccess('manage.product.image.edit')) {
                    return true;
                }
                return false;
            },
            'canAccessDelete' => function() {
                if (canAccess('manage.product.image.delete')) {
                    return true;
                }
                return false;
            }
        ];

        return __dataTable($imagesCollection, $requireColumns);
    }

    /**
     * Process for list order update request.
     *
     * @param array $input
     *
     * @return reaction number
     *---------------------------------------------------------------- */
    public function prepareImageListOrder($input)
    {
        $orderData = [];
        foreach ($input as $key => $pageOrder) {
            $orderData[] = [
                'id'           =>  $pageOrder['_id'],
                'list_order'   =>  $pageOrder['newPosition'] + 1
            ];
        }

        if (!__isEmpty($input)) {
            // Check if list order updated
            if ($this->manageProductRepository->updateImageListOrder($orderData)) {
                return __engineReaction(1);
            }
        }

        return __engineReaction(14);
    }

    /**
     * Delete product image.
     *
     * @param number $productID
     * @param number $imageID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteProductImage($productID, $imageID)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $imageID) {
                                 $productImage = $this->manageProductRepository
                                 ->fetchImage($productID, $imageID);

            // Check if product image exist
            if (empty($productImage)) {
                return 18;
            }

            // Check if prdouct image deleted
            if ($this->manageProductRepository->deleteImage($productImage)) {
                $this->mediaEngine->processDeleteProductMediaImage($productID,$productImage->file_name);

                return 1;
            }

            return 2;
        });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare prdouct image edit support data.
     *
     * @param number $productID
     * @param number $imageID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareEditImageSupportData($productID, $imageID)
    {
        $productImage = $this->manageProductRepository
                             ->fetchImage($productID, $imageID, true);

        // Check if product image exist
        if (empty($productImage)) {
            return 18;
        }

        return __engineReaction(1, ['prdouct_image' => $productImage]);
    }

    /**
     * Process edit product image.
     *
     * @param number $productID
     * @param number $imageID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processEditProductImage($productID, $imageID, $input)
    {
        $productImage = $this->manageProductRepository
                             ->fetchImage($productID, $imageID);

        // Check if product image exist
        if (empty($productImage)) {
            return __engineReaction(18);
        }

        // Check if product image updated
        if ($this->manageProductRepository
                 ->updateImage($productImage, $input)) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Prepare prdouct option datatable source.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductOptionList($productID)
    {
        $optionCollection = $this->manageProductRepository
                                  ->fetchOptionsDataTableSource($productID);

        $requireColumns = [
            'id',
            'name',
            'type',
            'formattedSelectionType' => function($key) {
                if ($key['type'] == 1) {
                   return __tr("Dropdown");
                } else if ($key['type'] == 2) {
                    return __tr("Image");
                } else if ($key['type']== 3) {
                    return __tr("Radio");
                } else {
                    return __tr("N/A (default: Dropdown)");
                }
            },
            'canAccessEdit' => function() {
                if (canAccess('manage.product.option.edit.supportdata') && canAccess('manage.product.option.value.list')) {
                    return true;
                }
                return false;
            },
            'canAccessDelete' => function() {
                if (canAccess('manage.product.option.delete')) {
                    return true;
                }
                return false;
            }
        ];

        return __dataTable($optionCollection, $requireColumns);
    }

    /**
     * Add Product option.
     *
     * @param number productID
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddProductOption($productID, $input)
    {   
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $input) {

            // Check if product exist
            if ($this->manageProductRepository
                     ->fetchCountByID($productID) == 0) {
                return 18;
            }

            // Check if values empty
            if (empty($input['values'])) {
                return 4;
            }

            $image = [];
            if ($input['type'] === 2) {
                foreach ($input['values'] as $value) {
                    $image =  $value['image'];

                    if (!$this->mediaEngine->isUserTempMedia($image)) {
                       
                        return 3;
                    }

                    $newImageThumbnail = $this->mediaEngine
                                          ->storeProductMedia($image, $productID, null, 'productOptionImage');

                    if (!$newImageThumbnail) {
                        return 2; // error reaction
                    }

                    $input['file_name'] = $newImageThumbnail;
                }
            }
            
            // Check if product option name already taken by any option
            if ($this->manageProductRepository
                     ->fetchProductOptionCount($productID, $input['name']) > 0) {
                return 3;
            }

            // Check if prdouct option added
            if ($this->manageProductRepository
                     ->storeOption($productID, $input)) {
                return 1;
            }

            return 2;
        });

        return __engineReaction($reactionCode);
    }

    /**
     * Delete product option.
     *
     * @param number $productID
     * @param number $optionID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteProductOption($productID, $optionID)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $optionID) {

            // Get product option
            $productOption = $this->manageProductRepository
                                  ->fetchOption($productID, $optionID);

            // Check if product option exist
            if (empty($productOption)) {
                return 18;
            }

            // Check if product option deleted
            if ($this->manageProductRepository->deleteOption($productOption)) {

                if ($productOption->type == 2) {
                    foreach ($productOption->optionValues as $key => $optionValue) {
                        $imageName = $optionValue['image_name'];

                        $this->mediaEngine->processDeleteProductMediaImage($productID,
                                        $imageName);
                    }
                }
                return 1;
            }

            return 2;
        });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare edit product option support data.
     *
     * @param number $productID
     * @param number $optionID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareEditOptionSupportData($productID, $optionID)
    {
        $productOption = $this->manageProductRepository
                              ->fetchOption($productID, $optionID, true);

        // Check if product option exist
        if (empty($productOption)) {
            return __engineReaction(18);
        }

        return __engineReaction(1, ['product_option' => $productOption]);
    }

    /**
     * Process edit prdouct option.
     *
     * @param number $productID
     * @param number $optionID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processEditProductOption($productID, $optionID, $input)
    {
        $productOption = $this->manageProductRepository
                             ->fetchOption($productID, $optionID);

        // Check if product option exist
        if (empty($productOption)) {
            return __engineReaction(18);
        }

        // Check if product option name already taken by any option
        if ($this->manageProductRepository
                 ->fetchProductOptionCount($productID, $input['name'], $optionID) > 0) {
            return __engineReaction(3);
        }

        // Check if prdouct option updated
        if ($this->manageProductRepository->updateOption($productOption, $input)) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Prepare product option values.
     *
     * @param number $productID
     * @param number $optionID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductOptionValues($productID, $optionID)
    {
        $productOption = $this->manageProductRepository
                              ->fetchOption($productID, $optionID);

        // Check if product option exist
        if (empty($productOption)) {
            return __engineReaction(18);
        }

        $selectedOpValuesIds = [];
        $optionValues = $this->manageProductRepository
                             ->fetchOptionValues($optionID);

        $isOptionValueImagesUsed = false;
        $otherOptionImages = $this->manageProductRepository
                             ->checkValueImagesExist($productID, $optionID);

        if (!__isEmpty($otherOptionImages)) {
            $isOptionValueImagesUsed = true;
        }
     
        if (!__isEmpty($optionValues)) {

            foreach ($optionValues as $key => $value) {

                if ($value->productOptionImages->isNotEmpty()) {
                    $selectedOpValuesIds[] = $value->id;
                }

                if ($value->image_name) {

                    $optionValues[$key]['existingThumbnailURL'] = getProductMediaURL($productID).'/'.$value->image_name;
                }

                $optionValues[$key]['existingImage'] = $value->image_name;
                $optionValues[$key]['slider_images'] = $value->productOptionImages->pluck('id')->all();
               
            }
        }
        
        return __engineReaction(1, [
            'optionType'    => $productOption->type,
            'option_values' => $optionValues,
            'images' => $this->engineData($this->prepareProductImages($productID, $selectedOpValuesIds))['images'],
            'isOptionValueImagesUsed' => $isOptionValueImagesUsed
        ]);
    }

    /**
     * Process product option value delete request.
     *
     * @param number $productID
     * @param number $optionID
     * @param number $optionValueID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteProductOptionValue($productID,
        $optionID, $optionValueID, $optionType)
    {
        $optionValue = $this->manageProductRepository
                            ->fetchOptionValue($optionID, $optionValueID);
              
        // Check if option value empty
        if (empty($optionValue)) {
            return __engineReaction(18);
        }

        // Check if product option value deleted
        if ($this->manageProductRepository->deleteOptionValue($optionValue)) {
            if ($optionType == 2) {
                $imageName = $optionValue['image_name'];

                $this->mediaEngine->processDeleteProductMediaImage($productID,
                                $imageName);
               
            }
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Process product option values add request.
     *
     * @param number $productID
     * @param number $optionID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddProductOptionValues($productID, $optionID, $input)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $optionID, $input) {
            $productOption = $this->manageProductRepository
                                  ->fetchOption($productID, $optionID);

            // Check if product option exist
            if (empty($productOption)) {
                return 18;
            }

            $optionValues = $input['values'];

            // Check if option values empty
            if (empty($optionValues)) {
                return 3;
            }

            // Check if product option values added
            if ($this->manageProductRepository
                     ->storeOptionValues($optionID, $optionValues)) {
                return 1;
            }

            return 2;
        });

        return __engineReaction($reactionCode);
    }

    /**
     * Process product option values edit request.
     *
     * @param number $productID
     * @param number $optionID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processEditProductOptionValues($productID, $optionID, $input)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $optionID, $input) {
            $productOption = $this->manageProductRepository
                                  ->fetchOption($productID, $optionID);
           
            // Check if product option exist
            if (empty($productOption)) {
                return 18;
            }

            $image = $existingProductImageIds = [];

            if ($input['type'] === 2) {

                foreach ($input['values'] as $value) {
                    $image =  isset($value['image']) ? $value['image'] : null;
                  
                    //delete product image after update new image in database or temp folder start
                    if (!empty($image) and !empty($value['id'])) {   
                        foreach ($productOption->optionValues as $key => $option) {
                            if ($value['id'] == $option->id) {
                                $imageName = $option['image_name'];

                                $this->mediaEngine->processDeleteProductMediaImage($productID,$imageName);   
                            }                      
                        }
                    }
                    //delete product image after update new image in database or temp folder end

                    if (!$this->mediaEngine->isUserTempMedia($image)) {
                        return 3;
                    }
                   
                    if (!empty($image)) {
                        $newImageThumbnail = $this->mediaEngine
                                              ->storeProductMedia($image, $productID, null, 'productOptionImage');

                        if (!$newImageThumbnail) {
                            return 2; // error reaction
                        }

                        $input['file_name'] = $newImageThumbnail;
                    }
                }
            }

            // Assign input data to optionValues variable
            $optionValues = $input['values'];

            $newInputValues = $updateProductImages = [];

            // Check if option values exist
            // then assign a detail and push in array
            foreach ($optionValues as $optionValue) 
            {
                // Check option value is empty
                if (empty($optionValue['id'])) {
                    $addonPrice = 0;
                    if (!empty($optionValue['addon_price'])) {
                        $addonPrice = $optionValue['addon_price'];
                    }

                    $optionId = '';
                    if (!empty($optionValue['id'])) {
                        $optionId = $optionValue['id'];
                    }

                    $newInputValues[] = [
                        'id' => $optionId,
                        'name' => $optionValue['name'],
                        'image_name' => isset($optionValue['image']) ? $optionValue['image'] : null,
                        'addon_price' => $addonPrice,
                        'slider_images' => $optionValue['slider_images']
                    ];
                }

                if (__ifIsset($optionValue['id'])) {

                    if (isset($optionValue['slider_images']) and !__isEmpty($optionValue['slider_images'])) {

                        foreach($optionValue['slider_images'] as $productImgId) 
                        {
                            $updateProductImages[] = [
                                'id' => $productImgId,
                                'product_option_values_id' => $optionValue['id']
                            ];
                        }
                    }  
                }
            }

            $productOptionValue = $this->manageProductRepository
                                         ->fetchOptionValues($optionID)->toArray();

            // Check Product option and option values and update data
            if ((!empty($productOption)) and (empty($productOptionValue))) { 
                $inputValues = $this->manageProductRepository
                                    ->updateNewOptionValues($productID, $optionID, $newInputValues);

                if ($inputValues) {
                    return 1; //success
                }
            }

            $inputValues = $this->manageProductRepository
                                ->updateNewOptionValues($productID, $optionID, $newInputValues);

            $valueInputs = [];

            // Check if option values exist
            // then assign a detail and push in array
            foreach ($optionValues as $optionValue) { 
                if (!empty($optionValue['id'])) {
                    $addonPrice = 0;
                    if (!empty($optionValue['addon_price'])) {
                        $addonPrice = $optionValue['addon_price'];
                    }

                    if (!empty($optionValue['image'])) {
                        $valueInputs[] = [
                            'id' => $optionValue['id'],
                            'name' => $optionValue['name'],
                            'image_name' => $optionValue['image'],
                            'addon_price' => $addonPrice,
                        ];
                    }

                    if (empty($optionValue['image'])) {
                        $valueInputs[] = [
                            'id' => $optionValue['id'],
                            'name' => $optionValue['name'],
                            'addon_price' => $addonPrice,
                        ];
                    }
                }
            }
            
            $updateProductOptionImages = false;
            
            if (!__isEmpty($updateProductImages)) {
                $updateProductOptionImages = $this->manageProductRepository->batchUpdate($updateProductImages, 'id'); 
            }

            // Check if product option values updated
            $updateValues = $this->manageProductRepository
                                 ->updateOptionValues($productID, $optionID, $valueInputs);

                if ($inputValues and $updateValues and $updateProductOptionImages) {
                    return 1; //success
                } elseif ($inputValues or $updateValues or $updateProductOptionImages) {
                    return 1; // success
                } else {
                    return 2; // failed
                }
            });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare prdouct specification for datatable source.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductSpecificationList($productID)
    {
        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productID);

        // Chech if product exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, __tr('Product does not exist'));
        }

        $specificationPresetId = $product->specification_presets__id;
        $presetTitle = '';

        if (!__isEmpty($specificationPresetId)) {
            $specificationPreset = $this->specificationRepository->fetch($specificationPresetId);

            if (!__isEmpty($specificationPreset)) {
                $presetTitle = $specificationPreset->title;
            }
        }

        $specificationCollection = $this->manageProductRepository
                                          ->fetchSpecificationDataTableSource($productID);

        $productSpecificationsPresets = $this->specificationRepository->fetchPresetWithSpecification($specificationPresetId, $productID);

        $productSpecificationIds = $specificationCollection->pluck('specifications__id')->unique()->values()->all();

        $specificationValues = $this->manageProductRepository->fetchSpecificationValues();

        $spsecificationValueData = [];
        foreach ($specificationValues as $key => $values) {
            $spsecificationValueData[] = [
                '_id' => $values->_id,
                'specification_id' => $values->specifications__id,
                'label' => $values->specification_value,
            ];
        }

        $productSpecifications = $assignSpecificationIds = [];
        // Check specfication preset exist
        if (!__isEmpty($productSpecificationsPresets)) {
            foreach ($productSpecificationsPresets as $key => $productPresets) {
                $existingSpecValueIds = [];
                foreach ($productPresets->presetItem as $productPreset) {

                    $existingSpecValueIds = $specificationCollection->where('specifications__id', $productPreset->specifications__id)->pluck('specification_values__id')->toArray();
                   
                    $productSpecifications[$productPresets->title][] = [
                        'id' => $productPreset->specification->_id,
                        'label' => $productPreset->specification->label,
                        'existingSpecification' => $specificationCollection->where('specifications__id', $productPreset->specifications__id)->toArray(),
                        'specValues' => array_values(collect($spsecificationValueData)->where('specification_id', $productPreset->specifications__id)->whereNotIn('_id', $existingSpecValueIds)->toArray())
                    ];
                    $assignSpecificationIds[] = $productPreset->specifications__id;
                }
            }
        }
        
        $otherSpecifications = [];
        $otherSpecificationList = $this->specificationRepository->fetchOtherProductSpecifications($productSpecificationIds);
        $otherSpecIds = $otherSpecificationList->pluck('_id')->toArray();

        $specificationValues = $this->manageProductRepository->fetchSpecificationValues();

        $specificationValueData = [];
        foreach ($specificationValues as $key => $values) {
            $specificationValueData[] = [
                '_id' => $values->_id,
                'specification_id' => $values->specifications__id,
                'label' => $values->specification_value,
            ];
        }

        if (!__isEmpty($otherSpecificationList)) {
            foreach ($otherSpecificationList as $key => $otherSpecification) {
                $otherSpecifications[] = [
                    'id' => $otherSpecification->_id,
                    'label' => $otherSpecification->label,
                    'specValues' => array_values(collect($specificationValueData)->where('specification_id', $otherSpecification->_id)->toArray())
                ];
            }
        }

        $oldSpecificationCollection = $this->manageProductRepository
                                          ->fetchExistingSpecificationData($productID);

        $oldSpecification = [];                                  
        if (!__isEmpty($oldSpecificationCollection)) {
            foreach ($oldSpecificationCollection as $key => $oldSpec) {
                if (__isEmpty($oldSpec->specifications__id) and __isEmpty($oldSpec->specification_values__id)) {
                    $oldSpecification[] = [
                        'id'        => $oldSpec->_id,
                        'name'      => $oldSpec->name,
                        'value'     => $oldSpec->value,
                        'products_id' => $oldSpec->products_id,
                    ];
                }
                
            }
        }

        
        return __engineReaction(1, [
            'presetId'              => $specificationPresetId,
            'specifications'        => $productSpecifications,
            'isSpecificationExist'  => (!__isEmpty($specificationPresetId)) ? true : false,
            'presetTitle'           => $presetTitle,
            'specificationList'     => $this->specificationRepository->fetchAllSpecificationPreset(),
            'otherSpecifications'   => $otherSpecifications,
            'oldSpecification'      => $oldSpecification,
            'canAccessEdit'         => canAccess('manage.product.specification.edit'),
            'canAccessDelete'       => canAccess('manage.product.specification.delete')
        ]);
    }

    /**
     * Process Change Specification Preset
     *
     * @param number $productID
     * @param number $presetId
     * @param array  $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processChangeSpecificationPreset($productID, $inputData)
    {
        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productID);

        // Chech if product exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, __tr('Product does not exist'));
        }

        $presetId = $inputData['presetId'];
        $isDelete = $inputData['isDelete'];
        
        if (is_numeric($presetId)) {
            $preset = $this->specificationRepository->fetch($presetId);

            // Chech if product exist
            if (__isEmpty($preset)) {
                return __engineReaction(18, null, __tr('Preset does not exist'));
            }
        }
        
        $updateData = [
            'id' => $product->id,
            'specification_presets__id' => ($isDelete) ? null : $preset->_id
        ];

        if ($isDelete == true) {
            $this->manageProductRepository->deleteProductSpecification($productID);
        }

        if ($this->manageProductRepository->updateProduct($product, $updateData)) {
            return __engineReaction(1, null, __tr('Specification preset assign successfully.'));
        }

        return __engineReaction(14, null, __tr('Nothing updated.'));
    }

    /**
     * Process product specification add request.
     *
     * @param number $productID
     * @param array  $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddProductSpecificationValues($productID, $presetType, $inputData)
    {  
        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productID);

        // Chech if product exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, __tr('Product does not exist'));
        }

        foreach ($inputData['specifications'] as $specKey => $input) {
            foreach ($input as $inputKey => $values) {  
                if (isset($values['value']) and is_array($values['value'])) {
                    foreach ($values['value'] as $valueKey => $label) {
                        foreach ($values['existingSpecification'] as $existSpec) {
                            if ($existSpec['value'] == $label) {
                                unset($inputData['specifications'][$specKey][$inputKey]['value'][$valueKey]);
                            }
                        }
                    }
                }
            }
        }
        
        $specificationValues = $this->manageProductRepository->fetchSpecificationValues();
      
        // if preset for category of product
        if ($presetType == 1) {
            $specficationLabels = $specificationStoreData = $specificationUpdateData = [];
            $valueExists = [];
            foreach ($inputData['specifications'] as $key => $input) {
                foreach ($input as $values) {  
                    if (isset($values['value']) and is_array($values['value'])) {
                        $valueExists = [];
                        foreach ($values['value'] as $label) {  

                            $valueExists = $specificationValues->where('specifications__id', $values['id'])->where('specification_value', $label)->first();

                            $specificationStoreData[] = [
                                'value' => $label,
                                'products_id' => $productID,
                                'specifications__id' => $values['id'],
                                'valueExists' => (isset($valueExists) and $valueExists == true) ? true : false,
                                'specValueId' => isset($valueExists->_id) ? $valueExists->_id : null
                            ];
                        }
                    }
                }
            }
         
            if (!__isEmpty($specificationStoreData)) {
                // Check if product option values added
                if ($this->manageProductRepository
                        ->storeCategoryOrProductPreset($productID, $specificationStoreData)) {
                    return __engineReaction(1);
                }
            }
        } elseif ($presetType == 2) {
            
            $specificationCollection = $this->manageProductRepository
                                          ->fetchSpecificationDataTableSource($productID);

            $otherSpecificationIds = [];                                  
            $otherSpecificationList = $this->specificationRepository->fetchOtherProductSpecifications($specificationCollection->pluck('specifications__id'));

            if (!__isEmpty($otherSpecificationList)) {
                $otherSpecificationIds = $otherSpecificationList->pluck('_id')->toArray();
            }

            $specificationValues = $this->manageProductRepository->fetchSpecificationValues();

            $specificationStoreData = $specificationProductStoreData = [];
            if (is_numeric($inputData['label'])
                and in_array($inputData['label'], $otherSpecificationIds)) {
                foreach ($inputData['value'] as $key => $value) {

                    $valueExists = $specificationValues->where('specifications__id', $inputData['label'])->where('specification_value', $value)->first();

                    $specificationProductStoreData[] = [
                        'value' => $value,
                        'products_id' => $productID,
                        'specifications__id' => $inputData['label'],
                        'valueExists' => (isset($valueExists) and $valueExists == true) ? true : false,
                        'specValueId' => isset($valueExists->_id) ? $valueExists->_id : null
                    ];
                }

                // Check if specifcations is stroed
                if ($this->manageProductRepository->storeCategoryOrProductPreset($productID, $specificationProductStoreData)) {
                    return __engineReaction(1);
                }           
            } else {
                $specificationStoreData = [
                    'label' => $inputData['label'],
                    'status' => 1,
                    'use_for_filter' => true,
                    'values' => $inputData['value']
                ];
             
                if ($this->manageProductRepository->storeSpecifications($specificationStoreData, $productID)) {
                    return __engineReaction(1);
                }
            }
        }

        return __engineReaction(2);
    }

    /**
     * Prepare product specification support Data.
     *
     * @param number $productID
     * @param number $specificationID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareSpecificationSupportData($productID, $specificationID)
    {
        $specificationData = $this->manageProductRepository
                             ->fetchSpecificationByID($specificationID);

        // Check specification data exist
        if (empty($specificationData)) {
            return __engineReaction(18);
        }                              
     
        return __engineReaction(1, [
            'secificationValues' => $specificationData
        ]);
    }

    /**
     * Process product specification update.
     *
     * @param number $specificationID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdateProductSpecificationValues($specificationID, $input)
    {
        $specificationData = $this->manageProductRepository
                             ->fetchSpecificationByID($specificationID);

        // Check if specification exist
        if (empty($specificationData)) {
            return __engineReaction(18);
        }

        $success = $this->manageProductRepository
                         ->updateSpecificationValues($specificationData, $input);

        // if update specification then return engine reaction
        if ($success == true) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Process product specification value delete request.
     *
     * @param number $optionValueID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteProductSpecificationValue($productID, $specificationID)
    {
        $specificationValue = $this->manageProductRepository
                             ->fetchSpecificationByID($specificationID);

        // Check if option value empty
        if (empty($specificationValue)) {
            return __engineReaction(18);
        }

        // Check if product option value deleted
        if ($this->manageProductRepository->deleteSpecificationValue($specificationValue)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * get product name for heading.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getName($productID)
    {
        $productData = $this->manageProductRepository
                            ->fetchDetails($productID);

        // If product record empty then return not exist reaction
        if (__isEmpty($productData)) {
            return __engineReaction(18);
        }

        $isMultipleCategory = false;

        $getCategoriesIds = $this->manageProductRepository
                                 ->fetchProductCategory($productID);

        if (count($getCategoriesIds) > 1) {
            $isMultipleCategory = true;
        }

        $categoryIds = [];

        // Get category ids
        foreach ($getCategoriesIds as $key => $categoryId) {
            $categoryIds[$key] = $categoryId->categories_id;
        }
        
        $multipleCategories = $this->manageProductRepository
                                   ->fetchMultipleCategories($categoryIds);

        $categoryData = [];

        if (!__isEmpty($multipleCategories)) {
            foreach ($multipleCategories as $key => $category) {
                $categoryData[] = [
                    'id'     => $category->id,
                    'name'   => $category->name
                ];
            }
        }

        $replaceString = str_replace('/', '', $productData->name);

        return __engineReaction(1, [
                'productName' => $productData,
                'status' => $productData->status == 1 ? true : false,
                'isMultipleCategory' => $isMultipleCategory,
                'categoryData' => $categoryData,
                'detailsUrl'   => productsDetailsRoute($productData->id, slugIt($replaceString))
            ]);
    }

    /**
     * Process update status delete request.
     *
     * @param number $productId
     * @param array  $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdateStatus($productId, $inputData)
    {
        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productId);

        // Chech if product exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, 'Product does not exist.');  // not exist product record
        }

        $status = $inputData['active'] == true ? 1 : 2;

        // If status updated successfully then return success response
        if ($this->manageProductRepository->updateStatus($product, $status)) {
            return __engineReaction(1, null, 'Product status updated successfully.');
        }

        return __engineReaction(14, null, 'Status not updated.');
    }

	/**
      * Prepare item rating list tabular data
      *
      * @param int $itemId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function prepareProductRatingTabularData($itemId)
    {
        $productRatingCollection = $this->manageProductRepository
                                        ->fetchProductRatingDatabaleSource($itemId);
                                    
        $requireColumns = [
            'rated_on' => function ($key) {
                return formatDateTime($key['updated_at']);
            },
            'human_readable_rated_on' => function ($key) {
                return humanFormatDateTime($key['updated_at']);
            },
            'fullName' => function ($key) {
                return $key['fname'].' '.$key['lname'].'('. $key['email'] .')';
            },
            'fname',
            'rating',
            'review',
            '_id',
            'formatRating' => function($key) {
                $rating     = $key['rating'];
                $decimal    = '';
                $rated      = floor($rating);
                $unrated    = floor(5 - $rated);
              
                if (fmod($rating, 1) != 0) { 
                    $decimal = '<i class="fa fa-star-half-o lw-color-gold"></i>';
                    $unrated = $unrated - 1;
                }
               
                return (str_repeat('<i class="fa fa-star lw-color-gold"></i>', $rated).
                    $decimal.
                    str_repeat('<i class="fa fa-star lw-color-gray"></i>', $unrated));
                
            },
            'canAccessDelete' => function() {
                if (canAccess('manage.product.rating.write.delete')) {
                    return true;
                }
                return false;
            }
        ];
 
        return __dataTable($productRatingCollection, $requireColumns, [
                'totalRatingAvg' => $this->getTotalAvg($productRatingCollection)
            ]);
    }

     /**
      * Prepare item rating list tabular data
      *
      * @param int $productId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function prepareProductNotifyUserData($productId)
    {
        $productNotifyUser = $this->manageProductRepository
                                        ->fetchProductNotifyByProductId($productId);

        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productId);
                                        
        $awatingUserData = [];
        if (!__isEmpty($productNotifyUser)) {
            foreach ($productNotifyUser as $key => $userData) {
                $awatingUserData[] = [
                    'id'        => $userData['_id'],
                    'email'     => $userData['email'],
                    'isSelected' => false,
                    'createdOn' => formatDateTime($userData['created_at'])
                ];
            }
        }
        
        return __engineReaction(1, [
            'awatingUserData' => $awatingUserData,
            'productOutOfStock' => isset($product->out_of_stock) ? $product->out_of_stock : null
        ]);
 
    }

    /**
     * Delete user temp media file.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteMultipleAwatingUser($productId, $UserDeletedData)
    {
        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productId);

        // Chech if product exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, 'Product does not exist.');  // not exist product record
        }

        $deleteAwatingUserIds = [];
        
        if (!__isEmpty($UserDeletedData)) {
            foreach ($UserDeletedData as $userDeleted) {
                if ((isset($userDeleted['isSelected']))
                    and ($userDeleted['isSelected'] === true)) {
                    $deleteAwatingUserIds[] = $userDeleted['id'];
                }
            }
        }
        
        // Check if Awaiting User deleted
        if ($data = $this->manageProductRepository->deleteMultipleAwatingUser($product->id, $deleteAwatingUserIds)) {
            // activityLog('Project Multiple Task', $data == 1, 'Deleted', '', $project->_id, 1);
            return __engineReaction(1, null, __tr('Awaiting User Deleted Successfully.'));
        }

        return __engineReaction(2, null, __tr('Awaiting User Not Deleted.'));
    }

    /**
      * Delete Task
      *
      * @param  mix $projectIdOrUid
      *
      * @return  array
      *---------------------------------------------------------------- */
    public function processDeleteAwatingUser($productId, $notifyUserId)
    {
        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productId);

        // Chech if product exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, 'Product does not exist.');  // not exist product record
        }
        
        //Fetch all notify User data
        $notifyUser = $this->manageProductRepository->fetchNotifyUser($notifyUserId);
        
        // Check if product exist
        if (__isEmpty($notifyUser)) {
            return __engineReaction(18, null, __tr('Awaiting User not found.'));
        }

        // Check if product Awaiting User deleted
        if ($this->manageProductRepository->deleteAwatingUser($notifyUser)) {
            return __engineReaction(1, null, __tr('Awaiting User Deleted Successfully.'));
        }

        return __engineReaction(2, null, __tr('Awaiting User Not Deleted.'));
    }

    /**
      * Prepare item rating list tabular data
      *
      * @param int $itemId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function prepareProductFaqTabularData($itemId)
    {
        $productFaqCollection = $this->manageProductRepository
                                        ->fetchProductFaqDatabaleSource($itemId);
                                    
        $requireColumns = [
            '_id',
            'status',
            'question',
            'formattedType' => function($key) {
                if ($key['type'] == 1) {
                    return "Admin";
                } else if ($key['type'] == 2) {
                    return "User";
                } else if ($key['type'] == 23) {
                    return "Buyer";
                } else {
                    return "N/A";
                }
            },
            'answer' => function ($key) {
                if (!empty($key['answer'])) {
                    return $key['answer'];
                }

                return 'N/A';
            },
            'type',
            'created_at',
            'userFullName',
            'canAccessEdit' => function() {
                if (canAccess('manage.product.faq.editData')) {
                    return true;
                }
                return false;
            },
            'canAccessDelete' => function() {
                if (canAccess('manage.product.faq.delete')) {
                    return true;
                }
                return false;
            }
        ];
 
        return __dataTable($productFaqCollection, $requireColumns);
    }

    /**
     * Process product specification  add request.
     *
     * @param number $productID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddProductFaqValues($productID, $input)
    {   
        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productID);

        // Chech if product exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, 'Product does not exist.');  // not exist product record
        }

        $faqsAddData = [ 
            'products_id' => $productID,
            'question'    => $input['question'],
            'answer'      => $input['answer'],
            'users_id'    => getUserID(),
            'status'      => 1,
            'type'        => 1
        ];

        // Check if product faq values added
        if ($this->manageProductRepository->storeFaqValues($faqsAddData)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Prepare product specification support Data.
     *
     * @param number $productID
     * @param number $specificationID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareFaqDetailSupportData($productID, $faqID)
    {   
        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productID);

        // Chech if product exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, 'Product does not exist.');  // not exist product record
        }

        $faqCollection = $this->manageProductRepository->fetchFaqByID($faqID);

        // Check specification data exist
        if (__isEmpty($faqCollection)) {
            return __engineReaction(18, null, 'Faq does not exist.');
        }

        $faqDetailData = [
            '_id'           => $faqCollection->_id,
            'question'      => $faqCollection->question,
            'createdAt'     => formatDateTime($faqCollection->created_at),
            'createdBy'     => $faqCollection->userFullName,
            'answer'        => $faqCollection->answer,
        ];
   
        return __engineReaction(1, [
            'faqDetailData' => $faqDetailData,
        ]);
    }

    /**
     * Prepare product specification support Data.
     *
     * @param number $productID
     * @param number $specificationID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareFaqEditSupportData($productID, $faqID)
    {   
        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productID);

        // Chech if product exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, 'Product does not exist.');  // not exist product record
        }

        $faqCollection = $this->manageProductRepository->fetchFaqByID($faqID);

        // Check specification data exist
        if (__isEmpty($faqCollection)) {
            return __engineReaction(18, null, 'Faq does not exist.');
        }

        $faqData = [
            '_id'           => $faqCollection->_id,
            'question'      => $faqCollection->question,
            'answer'        => $faqCollection->answer,
        ];
      
        return __engineReaction(1, [
            'faqData' => $faqData,
        ]);
    }

    /**
     * Process product specification update.
     *
     * @param number $specificationID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdateProductFaq($productID, $faqID, $input)
    {   
        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productID);

        // Chech if product exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, 'Product does not exist.');  // not exist product record
        }

        $faqCollection = $this->manageProductRepository->fetchFaqByID($faqID);

        // Check if faq exist
        if (empty($faqCollection)) {
            return __engineReaction(18);
        }

        // if update faq then return engine reaction
        if ($this->manageProductRepository->updateFaq($faqCollection, $input)) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
      * Process Delete $ratingId
      *
      * @param int $ratingId
      * @param int $itemId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function processDeleteFaq($productId, $faqID)
    {   
        // fetch product detail
        $product = $this->manageProductRepository->fetchByID($productId);

        // Chech if product exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, 'Product does not exist.');  // not exist product record
        }

        $faqCollection = $this->manageProductRepository->fetchFaqByID($faqID);

        // Check if faq exist
        if (empty($faqCollection)) {
            return __engineReaction(18);
        }

        if ($this->manageProductRepository->deleteFaq($faqCollection)) {
            return __engineReaction(1, null, __tr('Product FAQ deleted Successfully.'));
        }

        return __engineReaction(2, null, __tr('Product Faq not deleted.'));
    }

    /**
    * get total avg
    *
    * @return number
    *-----------------------------------------------------------------------*/

    protected function getTotalAvg($ratingCollection)
    {
        if (__isEmpty($ratingCollection['data'])) {
            return  0;
        }

        $itemRating = collect($ratingCollection['data']);

        $rating = $itemRating->avg('rating');

        return round($rating, 2);
    }
    


    /**
      * Process Delete $ratingId
      *
      * @param int $ratingId
      * @param int $itemId
      *
      * @return eloquent collection object
      *---------------------------------------------------------------- */

    public function processDeleteRating($ratingId)
    {
        $rating = $this->manageProductRepository->fetchRating($ratingId);

        if (__isEmpty($rating)) {
            return __engineReaction(18, null, __tr('Product rating not found.'));
        }

        if ($this->manageProductRepository->deleteRating($rating)) {
            return __engineReaction(1, null, __tr('Product rating deleted Successfully.'));
        }

        return __engineReaction(2, null, __tr('Product rating not deleted.'));
    }

    /**
     * Prepare product details.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareSeoMeta($productID)
    {
        $product = $this->manageProductRepository->fetchByID($productID);

        // Check if product exist
        if (empty($product)) {
            return __engineReaction(18);
        }
        
        $jsonData = $product->__data;

        $seoMeta = [];
        if (isset($jsonData['seo_meta_info'])) {
        	$seoMeta = [
        		'keywords' => $jsonData['seo_meta_info']['keywords'] ?? '',
        		'description' => $jsonData['seo_meta_info']['description'] ?? '',
        	];
        } else {
			$seoMeta = [
        		'keywords' => '',
        		'description' =>  '',
        	];
        }

        return __engineReaction(1, ['seo_meta' => $seoMeta]);
    }

    /**
     * Prepare product details.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdateSeoMeta($productID, $seoData)
    {
        $product = $this->manageProductRepository->fetchByID($productID);

        $jsonData = $product->__data;

        // Check if product exist
        if (empty($product)) {
            return __engineReaction(18);
        }
      	
      	$jsonData['seo_meta_info'] = [
    		'keywords' => $seoData['keywords'] ?? '',
    		'description' => $seoData['description'] ?? '',
    	];

      	if ($this->manageProductRepository->updateProduct($product, ['__data' => $jsonData])) {
        	return __engineReaction(1);
      	}

      	return __engineReaction(14);
    }


    public function prepareProductImages($productID, $optionValuesIds = [])
    {   
       
        $images = $this->manageProductRepository->fetchProductImages($productID, $optionValuesIds);

        if (!__isEmpty($images)) {
           
            foreach($images as $key => $image) 
            {
                $images[$key][ 'thumbnail_url'] = getProductImageURL($image['products_id'], $image['file_name']);
            }
        }
        
        return __engineReaction(1, [
            'images' => $images,
            'isOptionValueImagesUsed' => $this->manageProductRepository->checkProductImagesExist($productID)
        ]);
    }

    public function processUnsetOptionImage($productID, $imageId)
    {
        $image = $this->manageProductRepository->fetchProductImage($productID, $imageId);

        if (__isEmpty($image)) {
           
            return __engineReaction(1);
        }
        
        if ($this->manageProductRepository->updateData($image, [
            'product_option_values_id' => null
        ])) {
            return __engineReaction(1);     
        }

        return __engineReaction(1);
    }
}
