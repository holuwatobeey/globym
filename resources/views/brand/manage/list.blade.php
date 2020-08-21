<div ng-controller="BrandListController as brandListCtrl">
    
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <span>
            <?= __tr( 'Manage Brands' ) ?>
            </span>
        </h3>
        <!-- /main heading -->

        <!-- action button -->
        <div class="pull-right">
            <button ng-if="canAccess('manage.brand.add') && canAccess('manage.brand.list')" class="lw-btn btn btn-sm btn-light pull-right" title="<?= __tr( 'Add New Brand' ) ?>" ui-sref="brands.add()"><i class="fa fa-plus"></i> <?= __tr( 'Add New Brand' ) ?></button>
        </div>
        <!-- /action button -->
    </div>

    <!-- data table -->
    <table class="table table-striped table-bordered" id="manageBrandList" cellspacing="0" width="100%">
        <thead class="page-header">
            <tr>
                <th><?=  __tr('Logo')  ?></th>
                <th width="25px"><?=  __tr('Product Count')  ?></th>
                <th><?=  __tr('Created at')  ?></th>
                <th><?=  __tr('Status')  ?></th>
                <th><?=  __tr('Action')  ?></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <!-- /data table -->

    <div ui-view></div>

    <!-- brand list row logo column  _template -->
    <script type="text/template" id="brandLogoColumnTemplate">
        <span class="lw-image-thumbnail"><img src="<%- __tData.logo_url %>"></span>  
        <% if(__tData.canAccessDetail) { %>
            <span title="<?= __tr( 'Name' ) ?>">
                <a href ng-click="brandListCtrl.detailDialog('<%- __tData._id %>')" title="<%-__tData.country %>"><%- __tData.name %></a>
            </span>
        <% }  %>

        <% if(!__tData.canAccessDetail) { %>
            <span title="<?= __tr( 'Name' ) ?>">
                <%- __tData.name %>
            </span> 
        <% }  %>
    </script>
    <!-- /brand list row logo column  _template -->

    <!-- place on date column _template -->
    <script type="text/template" id="brandCreatedDateColumnTemplate">

        <span class="custom-page-in-menu" title="<%-__tData.creation_date %>">
            <%-__tData.formattedCreatedDate %>
        </span>
       
    </script>
    <!-- /place on date column _template -->

    <!-- brand list row name column  _template -->
    <script type="text/template" id="brandNameColumnTemplate">

        <% if(__tData.canAccessProductList) { %>
            <span>
                <a ui-sref="products({ brandID : '<%- __tData._id %>'})" title="<%- __tData.productCount %> <?= __tr('products under this brand') ?>" class="badge badge-pill badge-dark"> <%- __tData.productCount %> </a>
            </span>
        <% }  %>

        <% if(!__tData.canAccessProductList) { %>
            <span>
                <a title="<%- __tData.productCount %> <?= __tr('products under this brand') ?>" class="badge badge-pill badge-dark"> <%- __tData.productCount %> </a>
            </span>
        <% }  %>
    </script>
    <!-- /brand list row name column  _template -->

    <!-- brand list row status column  _template -->
    <script type="text/template" id="statusColumnTemplate">
        <% if (__tData.status === 1) { %> 
            <span title="<?= __tr( 'Active' ) ?>"><i class="fa fa-eye lw-success"></i></span>
       <% } else { %>
        <span title="<?= __tr( 'Inactive' ) ?>"><i class="fa fa-eye-slash lw-danger"></i></span>
       <% } %>
    </script>
    <!-- /brand list row status column  _template -->

    <!-- brand list row action column  _template -->
    <script type="text/template" id="brandColumnActionTemplate">
        <% if(__tData.canAccessEdit) { %>
            <a href ui-sref="brands.edit({brandID:<%- __tData._id %>})" class="lw-btn btn btn-light btn-sm" title="<?= __tr( 'Edit' ) ?>"><i class="fa fa-pencil-square-o"></i> <?= __tr( 'Edit' ) ?></a>
        <% }  %>

        <% if(__tData.canAccessDelete) { %>
            <a href="" ng-click="brandListCtrl.delete('<%-__tData._id %>', '<%- escape(__tData.name) %>')" class="lw-btn btn btn-danger btn-sm" title="<?= __tr( 'Delete' ) ?>">
                <i class="fa fa-trash-o"></i> <?= __tr( 'Delete' ) ?>
            </a>
        <% }  %>
    </script>
    <!-- /brand list row action column  _template -->

</div>