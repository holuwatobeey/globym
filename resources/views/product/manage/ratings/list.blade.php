<?php
/*
*  Component  : ManageItem
*  View       : Ratings List  
*  File       : list.blade.php  
*  Controller : ManageProductRatingsListController 
----------------------------------------------------------------------------- */ 
?>

<div ng-controller='ManageProductRatingsListController as manageItemRatingListCtrl'>

    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __tr( 'Ratings' ) ?></h3>
        <!-- /main heading -->
    </div>

    <br>
    <input type="hidden" id="lwItemRatingDeleteConfirmTextMsg" data-message="<?= __tr( 'you want to delete __name__ rating.') ?>" data-delete-button-text="<?= __tr('Yes, delete it') ?>" data-success-text="<?= __tr('Deleted !') ?>">

    <div class="alert alert-info" ng-show="manageItemRatingListCtrl.totalRatingAvg != 0">
      <?= __tr('Average rating for this item is __rate__' , ['__rate__' => '[[ manageItemRatingListCtrl.totalRatingAvg ]]']) ?>
    </div>

    <!-- datatable item ratings -->
    <div>
        <table id="lwItemRatingsTable" class=" table table-bordered" width="100%">
            <thead class="page-header">
                <tr>
                    <th><?= __tr('User') ?></th>
                    <th><?= __tr('Rate') ?></th>
                    <th><?= __tr('Review') ?></th>
                    <th><?= __tr('Rated On') ?></th>
                    <th><?= __tr('Action') ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>
    <!-- /datatable item ratings -->
</div>

<!-- ratings action column template -->
<script type="text/template" id="itemRatedOnTemplate">
    
    <span title="<%- __tData.rated_on %>"><%- __tData.human_readable_rated_on %></span>
        
</script>
<!-- /ratings action column template -->

<!-- ratings action column template -->
<script type="text/template" id="itemFullNameTemplate">
    
    <%- __tData.fullName %>
        
</script>
<!-- /ratings action column template -->

<!-- ratings action column template -->
<script type="text/template" id="itemRatingsColumnActionTemplate">
    <% if(__tData.canAccessDelete) { %>
        <button ng-if="canAccess('manage.product.rating.write.delete')" title="<?= __tr('Delete') ?>" class="lw-btn btn btn-danger btn-sm delete-sw" href="" ng-click="manageItemRatingListCtrl.delete('<%- __tData._id %>','<%- __tData.title %>')"><i class="fa fa-trash-o fa-lg"></i> <?= __tr('Delete') ?></button> 
    <% }  %>
</script>
<!-- /ratings action column template -->
