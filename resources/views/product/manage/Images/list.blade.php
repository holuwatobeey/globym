<div ng-controller="ProductImagesController as productImagesCtrl">

    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __tr( 'Images' ) ?></h3>
        <!-- /main heading -->
        <div class="pull-right">
            <button ng-if="canAccess('manage.product.image.list') && canAccess('manage.product.image.add')" class="lw-btn btn btn-light pull-right btn-sm" title="<?= __tr( 'Add New Image' ) ?>" ng-href="" ng-click="productImagesCtrl.add()"><i class="fa fa-plus"></i> <?= __tr( 'Add New Image' ) ?></button>
        </div>
    </div>

    <!-- datatable container -->
    <div>
        <!-- datatable -->
        <table class="table table-striped table-bordered" id="productImagesList" cellspacing="0" width="100%">
            <thead class="page-header">
                <tr>
                    <th width="12%"><?=  __tr('List Order')  ?></th>
                    <th><?=  __tr('Thumbnail')  ?></th>
                    <th><?=  __tr('Title')  ?></th>
                    <th><?=  __tr('Action')  ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <!-- /datatable -->
    </div>
    <!-- /datatable container -->
    
</div>

<!-- pages list Order template -->
<script type="text/template" id="productImageColumnListOrderTemplate">
    <span class="fa fa-arrows-v"> - <%- __tData.list_order %></span>
</script>
<!-- /pages list Order template -->

<!-- productImageThumbnailColumnTemplate -->
<script type="text/_template" id="productImageThumbnailColumnTemplate">
   <a href="<%- __tData.thumbnail_url %>" lw-ng-colorbox class="image-name lw-image-thumbnail"><img src="<%- __tData.thumbnail_url %>"></a> 
</script>
<!-- /productImageThumbnailColumnTemplate -->


<!-- productActionColumnTemplate -->
<script type="text/_template" id="productImageActionColumnTemplate">
    <% if(__tData.canAccessEdit) { %>
        <button title="<?= __tr('Edit') ?>" class="lw-btn btn btn-light btn-sm" ng-click="productImagesCtrl.edit('<%- __tData.id %>')" ng-href> 
        <i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button>
    <% }  %>   

    <% if(__tData.canAccessDelete) { %>
        <button title="<?= __tr('Delete') ?>" class="btn btn-danger btn-sm delete-sw lw-btn" ng-click="productImagesCtrl.delete('<%- __tData.id %>','<%- __tData.title %>' )" ng-href>
            <i class="fa fa-trash-o fa-lg"></i> <?= __tr('Delete') ?>
        </button>
    <% }  %>
</script>
<!-- /productActionColumnTemplatee -->

