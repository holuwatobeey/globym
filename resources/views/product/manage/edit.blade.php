<div ng-controller="ManageEditProductDetailsController as editProductCtrl">
	
    <div class="lw-section-heading-block" ng-if="editProductCtrl.productName">
        <!-- main heading -->    
        <div class="lw-breadcrumb">
            <!-- main heading when child category or its product are selected -->
            <a ui-sref="products({mCategoryID:''})" ng-if="canAccess('manage.product.list')">
                <?= __tr( 'Manage Products' ) ?> &raquo;
            </a>
            <!-- /main heading when child category or its product are selected --> 
        </div>
        <!-- / main heading -->

        <span class="lw-section-heading" ng-bind="editProductCtrl.productName"></span>  
         <a href="[[editProductCtrl.detailsUrl]]" target="_new" title="
        <?= __tr('View Page') ?>"> <i class="fa fa-external-link edit-btn-link pl-2"></i>
        </a>
        <!-- /main heading -->
    </div>

    <div class="pull-right">

        <!-- update form status form -->
        <form class="lw-form lw-ng-form form-inline" name="editProductCtrl.[[ editProductCtrl.ngFormName ]]" 
            novalidate>

            <!-- Active -->
            <lw-form-checkbox-field field-for="active" label="<?= __tr( 'Publically Available' ) ?>" class="lw-form-item-box" ng-if="editProductCtrl.initialContentLoaded">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    title="<?= __tr( 'Update Status' ) ?>" 
                    ng-model="editProductCtrl.productData.active" 
                    ui-switch="" ng-change="editProductCtrl.submit()"/>
            </lw-form-checkbox-field>
            <!-- /Active -->

        </form>
        <!-- /update form status form -->

    </div>
    <br>
    <!-- Product Edit Tabs -->
    <div class="row mt-4">
        <div class="col-lg-3 col-sm-3 col-md-4 col-12 mb-2">
            <div class="card side-menu-container lw-setting-sidebar-menu">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link lw-conf-item" ui-sref="product_edit.details" ui-sref-active="active-nav" title="<?=  __tr('Details')  ?>"><i class="fa fa-cog fa-1x"></i> <span><?=  __tr('Details')  ?></span></a>
                    </li>
                    <li class="nav-item" ng-show="canAccess('manage.product.image.list')">
                        <a class="nav-link lw-conf-item" ui-sref="product_edit.images" ui-sref-active="active-nav" ui-sref="product_edit.images" title="<?=  __tr('Images')  ?>"><i class="fa fa-picture-o fa-1x"></i> <span><?=  __tr('Images')  ?></span></a>
                    </li>
                    <li class="nav-item" ng-show="canAccess('manage.product.option.list')">
                        <a class="nav-link lw-conf-item" ui-sref="product_edit.options" ui-sref-active="active-nav" title="<?=  __tr('Options')  ?>"><i class="fa fa-bars fa-1x"></i> <span><?=  __tr('Options')  ?></span></a>
                    </li>
                    <li class="nav-item" ng-show="canAccess('manage.product.specification.list')">
                        <a class="nav-link lw-conf-item" ui-sref="product_edit.specification" ui-sref-active="active-nav" title="<?=  __tr('Specifications')  ?>"><i class="fa fa-list-ul fa-1x"></i> <span><?=  __tr('Specifications')  ?></span></a>     
                    </li>
                    <li class="nav-item" ng-show="canAccess('manage.product.ratings.read.list')">
                        <a class="nav-link lw-conf-item" ui-sref="product_edit.ratings" ui-sref-active="active-nav" title="<?=  __tr('Ratings')  ?>"><i class="fa fa-star fa-1x"></i> <span><?=  __tr('Ratings')  ?></span></a>
                    </li>
                    <li class="nav-item" ng-show="canAccess('manage.product.faq.read.list')">
                        <a class="nav-link lw-conf-item" ui-sref="product_edit.faq" ui-sref-active="active-nav" title="<?=  __tr("FAQs")  ?>"><i class="fa fa-question-circle fa-1x"></i> <span><?=  __tr("FAQs")  ?></span></a>
                    </li>
                    <li class="nav-item" ng-show="canAccess('manage.product.awating_user.read.list')">
                        <a class="nav-link lw-conf-item" ui-sref="product_edit.awating_user" ui-sref-active="active-nav" title="<?=  __tr('Waiting List')  ?>"><i class="fa fa-user fa-1x"></i> <span><?=  __tr('Waiting List')  ?></span></a>
                    </li>
                    <li class="nav-item" ng-show="canAccess('manage.product.seo_meta.read')">
                        <a class="nav-link lw-conf-item" ui-sref="product_edit.seo_meta" ui-sref-active="active-nav" title="<?=  __tr('SEO Meta')  ?>"><i class="fa fa-search fa-1x"></i> <span><?=  __tr('SEO Meta')  ?></span></a>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-lg-9 col-sm-9 col-md-8 mt-1">
            <div ui-view></div>
        </div>
    </div>
    <!-- /Product Edit Tabs -->

    

</div>
