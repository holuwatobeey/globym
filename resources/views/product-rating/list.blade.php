<?php 
/*
*  Component  : Product
*  View       : ProductRatingList Controller
*  Engine     : Product  
*  File       : product-rating.list.blade.php  
*  Controller : ProductRatingListController 
----------------------------------------------------------------------------- */
?>
<div ng-controller="ProductRatingListController as productRatingListCtrl">
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <span>
                <?= __tr( 'Product Ratings' ) ?>
            </span>
        </h3>
        <!-- /main heading -->
    </div>

     <!-- /main heading -->
    <table class="table table-striped table-bordered" id="lwProductRatingList" cellspacing="0" width="100%">
          <thead>
             <tr>
                <th width="40%"><?= __tr('Product') ?></th>
                <th><?= __tr('Rating') ?></th>
                <th><?= __tr('Created On') ?></th>
             </tr>
          </thead>
          <tbody></tbody>
       </table>
       <div ui-view></div>
    </div>

</div>

<!-- Manage Product List creation date Column Template -->
<script type="text/_template" id="creationDateColumnTemplate">

   <span class="custom-page-in-menu" title="<%-__tData.createdAt %>">
    <%-__tData.formatCreatedData %></span>

</script>
<!-- /Manage Product List creation date Column Template -->