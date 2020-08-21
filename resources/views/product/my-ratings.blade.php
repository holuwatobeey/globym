<div ng-controller="MyRatingListController as MyRatingListCtrl">
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"> <?= __tr('My Ratings') ?>
        @section('page-title') 
            <?= __tr('My Ratings') ?>
        @endsection
        </h3>
        <!-- /main heading -->
    </div>

    <ul class="list-unstyled">
        <li class="media mb-5 border-bottom pb-5 lw-list-media" ng-repeat="rating in MyRatingListCtrl.ratingData track by $index">
            <a class="lw-product-row-list-item-thumbnail  mr-3" href="[[ rating.detailURL ]]">
                <img ng-src="[[ rating.productImage ]]" class="lw-my-wishlist-thumbnail" alt="">
            </a>
            <div class="media-body">
                <h5 class="mt-0 mb-1">
                    <a href="[[ rating.detailURL ]]">[[ rating['productName'] ]] </a>
                </h5>

                <!-- product descripton -->
                <div id="lwProductDesc[[rating.productId]]"></div>
                <!-- / product descripton -->
                
            </div>
            <div class="lw-my-rating-block">
                <span class="text-success" ng-if="rating.itemRating.selfRating > 0">
                    <?= __tr('You have rated __yourRating__', [
                        '__yourRating__' => "[[ rating.itemRating.selfRating ]]" ]) ?> 
                    <div class="text-dark">
                        <small>
                            <?= __tr('On __ratedDate__', [
                    '__ratedDate__' => '[[ rating.createdOn ]]'
                ]) ?>
                        </small>
                    </div>
                </span>
                <div id="lwRateIt">
                    <div id="lw-product-rating-[[rating.productId]]">
                        <span class="lw-product-rating">
                        	<span ng-bind-html="MyRatingListCtrl.calculateRating(rating.itemRating.rate)"></span>
                        </span>     
                    </div>
                    <div class="title lw-current-rating">
                        <span title="<?= __tr('Average ratings') ?>" ng-if="rating.itemRating.rate">
                               <?= __tr('Average Rating __avgRating__',[
                                   '__avgRating__' => '[[ rating.itemRating.rate ]]'
                               ]) ?></span>

                            <span title="<?= __tr('Average ratings') ?>" ng-if="!rating.itemRating.rate"></span>

                            <small ng-if="rating.itemRating.totalVotes == 0">
                                <em><?= __tr('No rating received yet.') ?></em> 
                            </small>

                            <small ng-if="rating.itemRating.totalVotes > 0">
                                <em><?= __tr('__voteCount__ vote(s)',[
                                    '__voteCount__' => "[[ rating.itemRating.totalVotes ]]" ]) ?>
                                </em>
                            </small>
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <div class="alert alert-info" ng-if="MyRatingListCtrl.ratingListData.length == 0">
        <?= __tr('There are no rated products.') ?>
    </div>

    <!-- show this button when item load on button  -->
    <div class="lw-pagination-container lw-pagination-product-center"></div>
    <!-- / show this button when item load on button -->
</div>

