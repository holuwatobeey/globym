<div ng-controller="CategoryController as categoryCtrl">

	<div class="lw-section-heading-block">
        <!-- main heading -->

			<!-- main heading when parent are selected -->
	        <span ng-show="!categoryCtrl.parentCategoryExist && !categoryCtrl.categoryStatus">
                <h3 class="lw-section-heading"><?= __tr( 'Manage Categories' ) ?></h3>
	        </span>
	        <!-- /main heading when parent are selected -->
			
            <!-- main heading when child category or its product are selected -->
            <div class="lw-breadcrumb">
                <span ng-show="categoryCtrl.parentCategoryExist">
                    <!-- first level -->
                    <!-- <a ui-sref="categories({mCategoryID:''})" ng-if="canAccess('manage.product.list') && canAccess('manage.category.list')">
                        <?= __tr( 'Manage Categories & Products' ) ?> &raquo;
                    </a>  -->
                    <a ui-sref="categories({mCategoryID:''})" ng-if=" canAccess('manage.category.list')">
                        <?= __tr( 'Manage Categories' ) ?> &raquo;
                    </a> 
                    <!-- /first level -->

                    <!-- second level -->
                    <span ng-if="categoryCtrl.parentData.parentCateId != null">
                        <a ui-sref="categories({mCategoryID:categoryCtrl.parentData.parentCateId})" href>  ...
                        </a> &raquo;
                    </span>
                    <!-- /second level -->

                    <!-- third level -->
                    <span>
                        <a ui-sref="categories({mCategoryID:categoryCtrl.parentData.id})" href>
                            <span ng-bind="categoryCtrl.parentData.title"></span>
                        </a>
                    </span>
                    <!-- /third level -->

                    <!-- Back to parent category button -->
                    <span >
                       <a title="<?=  __tr('Back To Parent Category')  ?>"  class="lw-btn btn btn-light btn-sm pull-right" ui-sref="categories({mCategoryID:categoryCtrl.parentCategory.parent_id})" href><i class="fa fa-arrow-left"></i></a>
                    </span>
                    <!-- /Back to parent category button -->
                </span>
            </div>
            <!-- /main heading when child category or its product are selected -->

            <span ng-if="categoryCtrl.parentCategoryExist" ng-show="canAccess('manage.category.get.details') && canAccess('manage.category.update')">
                <div><h3><span ng-bind="categoryCtrl.parentCategory.name"></span>
                    <button ui-sref="categories.edit({catID:categoryCtrl.parentCategory.id})" title="<?= __tr('Edit') ?>" class="lw-btn btn btn-light btn-sm lw-category-title"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button>
                </h3></div>
            </span>
		<!-- /main heading -->

		<div class="pull-right">
			<!-- Add new category button -->

			<a ng-if="canAccess('manage.category.add') && canAccess('category.fancytree.support-data') && canAccess('manage.category.list') && !categoryCtrl.parentCategory.id" title="<?=  __tr('Add New Category')  ?>" class="lw-btn btn btn-light btn-sm" ui-sref="categories.add({mCategoryID  : ''})"><i class="fa fa-plus"></i> <?=  __tr('Add New Category')   ?></a>

            <a ng-if="canAccess('manage.category.add') && canAccess('category.fancytree.support-data') && canAccess('manage.category.list') && categoryCtrl.parentCategory.id" title="<?=  __tr('Add New Category')  ?>" class="lw-btn btn btn-light btn-sm" ui-sref="categories.add({mCategoryID  : categoryCtrl.parentCategory.id})"><i class="fa fa-plus"></i> <?=  __tr('Add New Category')   ?></a>
			<!-- /Add new category button -->
			
			<!-- Add new product button -->
			<!-- <a ng-show="canAccess('manage.product.add') && canAccess('manage.product.list') && canAccess('manage.product.add.supportdata')" class="lw-btn btn btn-sm btn-light" title="<?= __tr( 'Add New Product' ) ?>" ui-sref="category_product_add({categoryID : categoryCtrl.categoryID})"><i class="fa fa-plus"></i> <?= __tr( 'Add New Product' ) ?></a> -->
			<!-- /Add new product button -->
		</div>
    </div>
	
    <!-- tab heading -->
	<!-- <ul class="nav nav-tabs lw-tabs" role="tablist" id="manageCategoryTab"> -->
		
        <!-- category tab -->
		<!-- <li ng-show="canAccess('manage.category.list')" role="presentation" class="manageCategories nav-item" id="manageCategoriesTab">
			<a href="#manageCategories" class="nav-link" ng-click="categoryCtrl.goToCategories($event)" role="tab" title="<?= __tr( 'Categories' ) ?>" aria-controls="manageCategories" data-toggle="tab">
				<?=  __tr('Categories')  ?>
			</a>
		</li> -->
		<!-- /category tab -->

		<!-- product tab -->	
		<!-- <li ng-show="canAccess('manage.product.list')" role="presentation" class="manageProducts nav-item" id="manageProductsTab">
			<a href="#manageProducts" class="nav-link" ng-click="categoryCtrl.goToProducts($event)" role="tab" title="<?= __tr( 'Products' ) ?>" aria-controls="manageProducts" data-toggle="tab">
				<?=  __tr('Products')  ?>
			</a>
		</li> -->
		<!-- /product tab -->

	<!-- </ul> -->
	<br>
	<!-- /tab heading -->
	
	<!-- manage categories datatable container -->
    <div id="section1">

        <!-- manage categories datatable header column name -->

        <div class="alert alert-warning" ng-show="categoryCtrl.isInactiveParent"><?=  __tr("One of it's parent may be Inactive")  ?></div>

        <table id="categoriesTabList" class="category-table table table-striped table-bordered" cellspacing="0" width="100%">
            <thead class="page-header">
                <tr>
                    <th><?=  __tr('Name')  ?></th>
                    <th><?=  __tr('Active')  ?></th>
                    <th><?=  __tr('Subcategories')  ?></th>
                    <th><?=  __tr('Products')  ?></th>
                    <th><?=  __tr('Action')  ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <!-- /manage categories datatable header column name -->
    </div>
    <!--/ manage categories datatable container -->
			
	<div ui-view></div>
		
	
</div>

<!--                            Manage Category section Script start here
------------------------------------------------------------------------------------------------- -->


<!-- categories list row Action column _template -->
<script type="text/template" id="categoriesColumnActionTemplate">
    <% if(__tData.canAccessEdit) { %>
        <button ui-sref="categories.edit({catID:'<%- __tData.id %>'})" title="<?= __tr('Edit') ?>" class="lw-btn btn btn-light btn-sm"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button> 
    <% }  %>

    <% if(__tData.canAccessDelete) { %>
        <button title="<?= __tr('Delete') ?>" ng-click="categoryCtrl.deleteDialog('<%- __tData.id %>', '<%- _.escape(__tData.name) %>');" class="lw-btn btn btn-danger btn-sm delete-sw"><i class="fa fa-trash-o"></i> <?= __tr('Delete')  ?></button>
    <% }  %>
</script>
<!-- /categories list row Action column _template -->

<!-- categories list row products column   _template -->
<script type="text/template" id="categoriesColumnActionAddProduct">

    <% if(__tData.canAccessAddProduct) { %>
  	     <a ng-show="canAccess('manage.product.add.supportdata') && canAccess('manage.product.add')" ui-sref="category_product_add({categoryID : <%- __tData.id %>})" title="<?= __tr('Add Product') ?>" class="lw-btn btn btn-light btn-sm"><i class="fa fa-plus"></i> <?= __tr('Add Product') ?></a>
    <% }  %>
    
    <% if(__tData.canAccessCategoryProductList) { %>
      	<a class="lw-btn btn btn-light btn-sm" title="<?= __tr('This category contain __currentCategoryProductsCount__ product(s) out of __totalCategoryProductCount__', ['__totalCategoryProductCount__' => '<%= __tData.totalCategoryProductCount %>', '__currentCategoryProductsCount__' => '<%- __tData.currentCategoryProductsCount %>']) ?>"  ui-sref="products({mCategoryID:'<%- __tData.id %>'})">
      		<span class="badge badge-dark"> <%- __tData.currentCategoryProductsCount %> </span> / 
      		<span class="badge badge-dark"> <%- __tData.totalCategoryProductCount %> </span> <?= __tr('Products')  ?>
       </a>
    <% }  %>

    <% if(!__tData.canAccessCategoryProductList) { %>
       <a class="lw-btn btn btn-light btn-sm" title="<?= __tr('This category contain __currentCategoryProductsCount__ product(s) out of __totalCategoryProductCount__', ['__totalCategoryProductCount__' => '<%= __tData.totalCategoryProductCount %>', '__currentCategoryProductsCount__' => '<%- __tData.currentCategoryProductsCount %>']) ?>">
            <span class="badge badge-dark"> <%- __tData.currentCategoryProductsCount %> </span> / 
            <span class="badge badge-dark"> <%- __tData.totalCategoryProductCount %> </span> <?= __tr('Products')  ?>
       </a>
   <% }  %>
</script>

<!-- categories list row Subcategories column  _template -->
<script type="text/template" id="categoriesColumnActionAddcategory">
    <% if(__tData.canAccessAddSubCategory) { %>
        <a href ng-click="categoryCtrl.add(<%- __tData.id %>)" title="<?= __tr('Add Subcategory ') ?>" class="lw-btn btn btn-light btn-sm"><i class="fa fa-plus"></i> <?= __tr('Add category') ?></a> 
    <% }  %>

    <% if(__tData.canAccessCategoryList) { %>
        <a ui-sref="categories({mCategoryID:'<%- __tData.id %>'})"
      	title="<?= __tr('This category contain __activeChildCount__ active and __activeChildInactive__ inactive subcategories', [ '__activeChildCount__' => '<%= __tData.childCount.active %>','__activeChildInactive__' => '<%= __tData.childCount.inActive %>']) ?>" 
      	class="lw-btn btn btn-light btn-sm">
      	<span class="badge badge-success lw-active"><%- __tData.childCount.active %></span> 
      	| <span class="badge badge-dark" ><%- __tData.childCount.inActive %> </i></span> <?= __tr('Subcategories') ?></a>
    <% }  %>
</script>
 
<!-- categories list row name column  _template -->
<script type="text/template" id="categoriesColumnActionSubcategories">
    <% if(__tData.canAccessCategoryList) { %>
        <a ui-sref="categories({mCategoryID:'<%- __tData.id %>'})" title="<?= __tr('View') ?>" class="tch-name word-wrap"><%- __tData.name %></a>
    <% }  %>
</script>
<!-- categories list row name column  _template -->

<!-- categories list row status column  _template -->
<script type="text/template" id="categoriesColumnStatus">
 	<% if (__tData.status === 1) { %> 
        <span title="<?= __tr( 'Active' ) ?>"><i class="fa fa-eye lw-success"></i></span>
   <% } else { %>
    <span title="<?= __tr( 'Inactive' ) ?>"><i class="fa fa-eye-slash lw-danger"></i></span>
   <% } %>
</script>
<!-- categories list row status column  _template -->

<!-- product categories -->
<script type="text/_template" id="categoryDeleteAlertTemplate">
    <div class="text-center">

    	<%= __ngSupport.getText(
	        __globals.getJSString('category_delete_note_text'), {
	            ':name'    : unescape(__tData.name)
	        }
    	)%>
    <h3 class="disabledText"><%= __tData.captchaImg %></h3>
  	</div>
  	<div class="form-group custom-form-control">
  		<label for="exampleInputEmail1"></label>
  		<div class="input-group">
  		<span class="input-group-addon"><%= __globals.getJSString('category_delete_confirm_note') %></span>
  		<input type="text" placeholder="<%= __globals.getJSString('category_delete_input_placeholder_text') %>" class="form-control deleteCate"></div><div class="custom-error">
  		</div>
  	</div>
</script>
<!-- /product categories -->

<!--                             Manage Category section Script end here
------------------------------------------------------------------------------------------------- -->