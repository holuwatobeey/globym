    <div ng-controller="ProductListController as ProductListCtrl">

	<div class="lw-section-heading-block">
		<!-- main heading when parent are selected -->
        <span ng-show="!ProductListCtrl.parentCategoryExist && !ProductListCtrl.categoryStatus">
            <h3 class="lw-section-heading" ng-if="canAccess('manage.product.list')"><?= __tr( 'Manage Products' ) ?></h3>
        </span>
        <!-- /main heading when parent are selected -->
		
        <div class="lw-breadcrumb">
            <!-- main heading when child category or its product are selected -->
            <span ng-show="ProductListCtrl.categoryStatus">
                <!-- first level -->
                <a ui-sref="categories({mCategoryID:''})">
                    <?= __tr( 'Manage Categories' ) ?>
                </a> &raquo;
                <!-- /first level -->

                <!-- second level -->
                <span ng-if="ProductListCtrl.parentData.parentCateId != null">
                    <a ui-sref="categories({mCategoryID:ProductListCtrl.parentData.parentCateId})" href>  ...
                    </a> &raquo;
                </span>
                <!-- /second level -->

                <!-- third level -->
                <span>
                    <a ui-sref="categories({mCategoryID:ProductListCtrl.parentData.id})" href>
                        <span ng-bind="ProductListCtrl.parentData.title"></span>
                    </a>
                </span>
                <!-- /third level -->
                <span ng-if="!ProductListCtrl.parentData.id">
                <a title="<?=  __tr('Back To Parent Category')  ?>"  class="lw-btn btn btn-light btn-sm pull-right" ui-sref="categories({mCategoryID:''})"><i class="fa fa-arrow-left"></i></a>
                </span>

                <span ng-if="ProductListCtrl.parentData.id">
                <a title="<?=  __tr('Back To Parent Category')  ?>"  class="lw-btn btn btn-light btn-sm pull-right" ui-sref="categories({mCategoryID:ProductListCtrl.parentData.id})" href><i class="fa fa-arrow-left"></i></a>
                </span>
            </span>
            <!-- /main heading when child category or its product are selected -->
        </div>

        <span ng-show="ProductListCtrl.categoryStatus" ng-show="canAccess('manage.category.get.details') && canAccess('manage.category.update')">
            <div>
            	<h3>
            		<span ng-bind="ProductListCtrl.category.name"></span>
                	<button href ui-sref="categories.edit({mCategoryID: '', catID:ProductListCtrl.category.id})" 
                			title="<?= __tr('Edit') ?>" class="lw-btn btn btn-light btn-sm"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?>
                	</button>
                <h3>
            </div>
        </span>
        <!-- /main heading -->
     
		<div class="pull-right">
			<!-- Add new category button -->
			<!-- <a ng-show="canAccess('manage.category.add') && canAccess('manage.category.list')" title="<?=  __tr('Add New Category')  ?>" class="lw-btn btn btn-light btn-sm" ui-sref="categories.add({mCategoryID  : ''})"><i class="fa fa-plus"></i> <?=  __tr('Add New Category')   ?></a> -->
			<!-- /Add new category button -->

			
			<!-- Add new product button -->
			<a ng-show="canAccess('manage.product.add') && canAccess('manage.product.list')" class="lw-btn btn btn-sm btn-light" title="<?= __tr( 'Add New Product' ) ?>" ui-sref="product_add"><i class="fa fa-plus"></i> <?= __tr( 'Add New Product' ) ?></a>
			<!-- /Add new product button -->
		</div>
    </div>
	

    <!-- tab heading -->
	<!-- <ul class="nav nav-tabs lw-tabs" role="tablist" id="manageProductTab"> -->
		<!-- category tab -->
		<!-- <li ng-show="canAccess('manage.category.list')" role="presentation" class="manageCategoriesTab nav-item active" id="manageCategoriesTab">
			<a href="#manageCategories" class="nav-link" ng-click="ProductListCtrl.goToCategories($event)" role="tab" title="<?= __tr( 'Categories' ) ?>" aria-controls="manageCategories" data-toggle="tab">
				<?=  __tr('Categories')  ?>
			</a>
		</li> -->
		<!-- /category tab -->

		<!-- product tab -->	
		<!-- <li ng-show="canAccess('manage.product.list') || canAccess('manage.brand.product.list') || canAccess('manage.category.product.list')" role="presentation" class="tabpanel manageProductsTab nav-item" id="manageProductsTab">
			<a href="#manageProducts" class="nav-link" ng-click="ProductListCtrl.goToProducts($event)" role="tab" title="<?= __tr( 'Products' ) ?>" aria-controls="manageProducts" data-toggle="tab">
				<?=  __tr('Products')  ?>
			</a>
		</li> -->
		<!-- /product tab -->
	<!-- </ul> -->
	<br>
	<!-- /tab heading -->
	
	<!-- manage products  datatable container -->
    <div id="section2">
        
        <!-- datatable container -->
        <table id="productsTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead class="page-header">
                <tr>
                    <th width="20%"><?=  __tr('Thumbnail')  ?></th>
                    <th width="50%"><?= __tr('Name')  ?></th>
                    <th width="5%"><?= __tr('Featured') ?></th>
                    <th><?=  __tr('Stock')  ?></th>
                    <th><?=  __tr('Active')  ?></th>
                    <th><?=  __tr('Created at')  ?></th>
                    <th><?=  __tr('Categories')  ?></th>
                    <th width="20%"><?= __tr('Brand') ?></th>
                    <th><?=  __tr('Action')  ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <!-- /datatable container -->
    </div>
    <!-- /manage products  datatable container -->

</div>
<br>
<!-- manage products section secripts -->

	<!-- productThumbnailColumnTemplate -->
	<script type="text/_template" id="productThumbnailColumnTemplate">

	<a title="<?= __tr('Product Edit') ?>" href="<%- __tData.thumbnail_url %>" ui-sref="product_edit.details({productID : '<%- __tData.id %>'})" class="lw-image-thumbnail">
        <img src="<%- __tData.thumbnail_url %>">
    </a> 
	   
	</script>
	<!-- /productThumbnailColumnTemplate -->

	<!-- Manage Product List Name Column Template -->
	<script type="text/_template" id="nameColumnTemplate">

	  	<span class="custom-page-in-menu">
            <a href title="<?= __tr('Product Edit') ?>" ui-sref="product_edit.details({productID : '<%- __tData.id %>'})"><%-__tData.name %> </a>
	  	<a target="_blank" href="<%-__tData.detailPageURL %>" title="<?= __tr('Product Details') ?>"><i class="fa fa-external-link"></i></a>

		</span>
	</script>
	<!-- /Manage Product List Name Column Template -->

	<!-- productActionColumnTemplate -->
	<script type="text/_template" id="productActionColumnTemplate">
        <% if(__tData.canAccessEdit) { %>
	        <button title="<?= __tr('Edit') ?>" class="lw-btn btn btn-light btn-sm" ui-sref="product_edit.details({productID : '<%- __tData.id %>'})"> 
	        <i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button>
        <% }  %>    

        <% if(__tData.canAccessDelete) { %>
    	    <button title="<?= __tr('Delete') ?>" class="lw-btn btn btn-danger btn-sm delete-sw" ng-click="ProductListCtrl.productDelete('<%- __tData.id %>','<%- escape(__tData.name) %>' )" ng-href>
    	        <i class="fa fa-trash-o"></i> <?= __tr('Delete') ?>
    	    </button>
        <% }  %>
	</script>
	<!-- /productActionColumnTemplatee -->

	<!-- /productBrandColumnTemplate -->
	<script type="text/_template" id="productBrandColumnTemplate">
		<% if (__tData.brand != null) { %>

			<% var bandStatus = (__tData.brand.status == 2) ? 'lw-danger' : '';  %>
	    	<% var brandStatusValue = (__tData.brand.status == 2) ? '<?= __tr( 'Inactive' ) ?>' : '<?= __tr( 'Active' ) ?>';  %>

            <% if(__tData.canAccessViewBrandProduct) { %>
			    <span><a class="<%- bandStatus %> lw-image-thumbnail" title="<%- brandStatusValue %>" ui-sref="products({ brandID : '<%- __tData.brand._id %>'})">
                    <img src="<%- __tData.brand_thumbnail_url %>">
				</a></span>
            <% }  %>      

		<% } %>
        <% if (__tData.brand != null) { %>

            <% var bandStatus = (__tData.brand.status == 2) ? 'lw-danger' : '';  %>
            <% var brandStatusValue = (__tData.brand.status == 2) ? '<?= __tr( 'Inactive' ) ?>' : '<?= __tr( 'Active' ) ?>';  %>

            <% if(!__tData.canAccessViewBrandProduct) { %>
                <span><a class="<%- bandStatus %> lw-image-thumbnail" title="<%- brandStatusValue %>">
                <img src="<%- __tData.brand_thumbnail_url %>"></a></span>
            <% }  %>    
        <% } %>
	</script>
	<!-- /productBrandColumnTemplate -->
			
	<!-- Manage Product List creation date Column Template -->
	<script type="text/_template" id="creationDateColumnTemplate">

	   <span class="custom-page-in-menu" title="<%-__tData.creation_date %>">
        <%-__tData.formatCreatedData %></span>

	</script>
	<!-- /Manage Product List creation date Column Template -->
			
	<!-- Manage Product List out of stock product Column Template -->
	<script type="text/_template" id="outOfStockColumnTemplate">
	
	    <% if (__tData.out_of_stock == 0) { %> 
	        <span class="lw-success" title="<?= __tr( 'In Stock' ) ?>"><?=  __tr('In Stock')  ?></span>
        <% } else if(__tData.out_of_stock == 2) { %>
            <span class="lw-warn-color" title="<?= __tr( 'Coming Soon' ) ?>"><?=  __tr('Coming Soon')  ?></span>
        <% } else if(__tData.out_of_stock == 3) { %>
            <span class="lw-warn-color" title="<?= __tr( 'Launching On' ) ?>"><?=  __tr('Launching On')  ?></span>
	    <% } else if(__tData.out_of_stock == 1) { %>
	       <span class="lw-danger" title="<?= __tr( 'Out of Stock' ) ?>"><?=  __tr('Out of Stock')  ?></span>
	    <% } %>
	</script>
	<!-- /Manage Product List out of stock product Column Template -->

	<!-- Manage Product List out of stock product Column Template -->
	<script type="text/_template" id="featuredProductColumnTemplate">
	
	   <% if (__tData.featured == true) { %> 
	        <span class="lw-success"><?=  __tr('Yes')  ?></span>
	   <% } else { %>
	    <span class="lw-danger" ><?=  __tr('No')  ?></span>
	   <% } %>
	</script>
	<!-- /Manage Product List out of stock product Column Template -->


	<!-- Manage Product List Status Column Template -->
	<script type="text/_template" id="productStatusColumnTemplate">
	   <% if (__tData.status === 1) { %> 
	        <span title="<?= __tr( 'Active' ) ?>"><i class="fa fa-eye lw-success"></i></span>
	   <% } else { %>
	    <span title="<?= __tr( 'Inactive' ) ?>"><i class="fa fa-eye-slash lw-danger"></i></span>
	   <% } %>
	</script>
	<!-- /Manage Product List Status Column Template -->

	<!-- product categories -->
	<script type="text/_template" id="productCategoriesColumnTemplate">
	    <% var categories = __tData.categories; %>
	    <% _.each(categories, function(category, index) { %>

	    	<% var status = (category.status == 0) ? 'lw-inactive' : '';  %>
	    	<% var statusValue = (category.status == 0) ? '<?= __tr( 'Inactive' ) ?>' : '<?= __tr( 'Active' ) ?>';  %>

	        <span class="<%- status %>">
                <a href ui-sref="categories({mCategoryID:'<%- category.id %>'})">
                    <%- category.name %> <%= (index == categories.length - 1) ? '' : '|' %>
                </a>
            </span>
	        
	    <% }); %> 

	</script>
	<!-- /product categories -->
