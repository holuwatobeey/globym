<?php
/**
  *  Component  : Item
  *  View       : Show the item rating & review list
  *  Engine     : ItemEngine  
  *  File       : rating-and-reviews.blade.php  
  *  Controller : ItemRatingAndReviewDialogController 
  * ----------------------------------------------------------------------------- */
?>
<div class="lw-dialog">

    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <?= __tr('Rating & Reviews for __title__', [
                '__title__' => '[[ RatingAndReviewCtrl.itemTitle ]]'
            ]) ?>
        </h3>
        <!-- /main heading -->
    </div>

    <!-- loader -->
    <div class="lw-main-loader lw-show-till-loading" ng-if="RatingAndReviewCtrl.pageStatus == false">
        <div class="loader"><?=  __tr('Loading...')  ?></div>
    </div>
    <!-- / loader -->

    <div ng-if="RatingAndReviewCtrl.pageStatus">

        <!-- rating list -->
        <div ng-if="RatingAndReviewCtrl.itemRatingAndReview.length > 0">

            <div class="card mb-3" 
                ng-repeat="rateAndReview in RatingAndReviewCtrl.itemRatingAndReview">
                
                <div class="card-header">

                    <?= __tr('__formattedDate__ by __fullName__', [
                        '__formattedDate__' => '<em title="[[ rateAndReview.ratedOn ]]">[[ rateAndReview.humanReadableDate ]]</em>',
                        '__fullName__'      => '[[ rateAndReview.fullName ]]'
                    ]) ?>

                </div>
                <ul class="list-group">
                    <li class="lw-dash-status lw-purchase-item-content list-group-item">
                        
                        <!-- <div class="rateit lw-bigstars rateit-bg">
                            <div id="rateit-range-2" title="<?= __tr('__rating__ stars from __fullName__.', [
                                '__rating__' => '[[ rateAndReview.rating ]]',
                                '__fullName__' => '[[ rateAndReview.fullName ]]'
                                ]) ?>" class="rateit-range lw-rate-height">
                                <div class="rateit-selected rateit-preset lw-star-height" lw-rate-it="[[ rateAndReview.rating ]]">                      
                                </div>
                            </div>
                        </div> -->
                        
                        <p ng-if="rateAndReview.review != ''" ng-bind-html="rateAndReview.review"></p>
                    </li>
                </ul>
            </div>
        </div>
        <!-- rating list -->
        <div ng-if="RatingAndReviewCtrl.itemRatingAndReview.length == 0">
            <?= __tr('There are no rating & review available.') ?>
        </div>

        <!-- Close dialog btn -->
        <div class="lw-form-actions">
            <button type="button" class="btn btn-light lw-btn btn-sm" title="<?= __tr('Close') ?>" ng-click="RatingAndReviewCtrl.closeDialog()"><?= __tr('Close') ?></button>
        </div>
        <!-- / Close dialog btn -->
    </div>

</div>