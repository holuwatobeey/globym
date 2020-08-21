<div ng-controller="ProductOptionsController as productOptionsCtrl">
       
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __tr( 'Options' ) ?></h3>
        <!-- /main heading -->
    </div>

    <!-- button -->
    <div class="pull-right">
        <div class="btn-group" ng-if="canAccess('manage.product.option.add') && canAccess('manage.product.option.list')" role="group" aria-label="Button group with nested dropdown">
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle lw-btn btn-light pull-right btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-plus"></i> <?= __tr( 'Add New Option' ) ?>
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="" ng-click="productOptionsCtrl.selectAddOption(1)"><?= __tr( 'Dropdown Option' ) ?></a>
                    <a class="dropdown-item" href="" ng-click="productOptionsCtrl.selectAddOption(2)"><?= __tr( 'Image Option' ) ?></a>
                    <a class="dropdown-item" href="" ng-click="productOptionsCtrl.selectAddOption(3)"><?= __tr( 'Radio Option' ) ?></a>
                </div>
            </div>
        </div>
    </div>
    <!-- /button -->
<br><br>
    <!-- datatable container -->
    <div>
        <!-- datatable -->
        <table class="table table-striped table-bordered" id="productOptionList" cellspacing="0" width="100%">
            <thead class="page-header">
                <tr>
                    <th><?=  __tr('Name')  ?></th>
                    <th><?=  __tr('Option Type')  ?></th>
                    <th><?=  __tr('Values')  ?></th>
                    <th><?=  __tr('Action')  ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <!-- /datatable -->
    </div>
    <!-- /datatable container -->
    
</div>

<!-- product Option Values Column Template -->
<script type="text/_template" id="productOptionValuesColumnTemplate">
    <% if(__tData.canAccessEdit) { %>
        <a  title="<?= __tr('Add / Edit values for') ?> <%- __tData.name %>" 
    	    class="lw-btn btn btn-light btn-sm" 
    	    ng-click="productOptionsCtrl.values('<%- __tData.id %>', '<%- __tData.name %>')" 
    	    ng-href>
    	    <i class="fa fa-plus"></i> <?= __tr('Add / ') ?>
    	    <i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?>
        </a>
    <% }  %>
</script>
<!-- product Option Values Column Template -->

<!-- product Action Column Template -->
<script type="text/_template" id="productOptionActionColumnTemplate">
    <% if(__tData.canAccessEdit) { %>    
        <button title="<?= __tr('Edit') ?>" class="lw-btn btn btn-light btn-sm" ng-click="productOptionsCtrl.edit('<%- __tData.id %>')"> 
        <i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></button>
    <% }  %>

    <% if(__tData.canAccessDelete) { %>
        <button title="<?= __tr('Delete') ?>" class="lw-btn btn btn-danger btn-sm delete-sw" ng-click="productOptionsCtrl.delete('<%- __tData.id %>','<%- __tData.name %>' )">
        <i class="fa fa-trash-o fa-lg"></i> <?= __tr('Delete') ?>
    </button>
    <% }  %>
</script>
<!-- /product Action Column Templatee -->


<!-- productImageThumbnailColumnTemplate -->
<script type="text/_template" id="typeColumnTemplate">
  <%= __tData.formattedSelectionType %>
</script>
<!-- /productImageThumbnailColumnTemplate -->



<!-- productImageThumbnailColumnTemplate -->
<script type="text/_template" id="imageListItemImageTemplate">
    <div id="<%= __tData.id %>" class="lw-selectize-item">
        <span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/></span> 
        <span class="lw-selectize-item-label"><%= __tData.name %></span>
    </div>
</script>
<!-- /productImageThumbnailColumnTemplate -->




<!-- New logo drop down list item template -->
<script type="text/_template" id="productImageItemTemplate">
    <div>
        <span class="lw-selectize-item lw-selectize-item-selected"><img src="<%= __tData.item.thumbnail_url %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.item.title %></span>
    </div>
</script>
<!-- /New logo drop down list item template -->
