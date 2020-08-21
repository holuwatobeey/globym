<div ng-controller="MyWishListController as MyWishListCtrl">
    <div ng-if="MyWishListCtrl.pageStatus">
        <div class="lw-section-heading-block">
            <!-- main heading -->
            <h3 class="lw-section-heading"><?=  __tr( 'My Wish List' ) ?>
                @section('page-title') 
                    <?= 'My Wish List' ?>
                @endsection
            </h3>
            <!-- /main heading -->
        </div>

        <ul class="list-unstyled">
        <li class="media mb-5 border-bottom pb-5 lw-list-media" ng-repeat="product in MyWishListCtrl.wishListCollection track by $index">
             <a class="lw-product-row-list-item-thumbnail mr-3" href ng-href="[[ product.detailURL ]]">
                    <img ng-src="[[ product.productImage ]]" class="lw-my-wishlist-thumbnail" alt="">
                    </a>
            <div class="media-body">
                <h5 class="mt-0 mb-1">
                <a href ng-bind="product.productName" ng-href="[[ product.detailURL ]]" title="<?= __tr('Product Details') ?>"></a>
                </h5>

                <!-- product descripton -->
                <div id="lwWislListProductDesc[[product.productId]]"></div>
                <!-- / product descripton -->

                <div>
                    <a href class="lw-btn btn btn-danger lw-btn-xs mt-3" ng-click="MyWishListCtrl.removeFromWishlist(product.productId)"><i class="fa fa-remove"></i> <?= __tr('Remove From Wish List') ?></a>
                </div>
            </div>
            <div class="lw-my-rating-block">
            <div class="lw-product-rating-[[rating.productId]]">
                <div id="lw-product-rating-[[product.productId]]">
                    <span class="lw-product-rating">
                        <span ng-bind-html="MyWishListCtrl.calculateRating(product.itemRating.rate)"></span>
                    </span>     
                </div>
                <span class="title lw-current-rating">
                    <span ng-bind="product.itemRating.rate" title="<?= __tr('Average ratings') ?>" ng-show="product.itemRating.rate"></span>
                    <span title="<?= __tr('Average ratings') ?>" ng-show="!product.itemRating.rate"></span>
                    <small ng-show="product.itemRating.totalVotes == 0">
                        <em><?= __tr('No rating received yet.') ?></em> 
                    </small>
                    <small ng-show="product.itemRating.totalVotes > 0">
                        <em><?= __tr('__voteCount__ vote(s)',[
                        '__voteCount__' => '[[ product.itemRating.totalVotes ]]'
                        ]) ?>
                        </em> 
                    </small>
                </span>
                <small>
                    <em ng-show="product.itemRating.selfRating">- <?= __tr('You have rated __yourRating__', [
                        '__yourRating__' => '[[ product.itemRating.selfRating ]]'
                        ]) ?> 
                    </em>
                </small>

            </div>
            </div>
        </li>
        </ul>

        <div ng-if="MyWishListCtrl.wishListData.length == 0">
            <?= __tr('There are no products added in your wishlist.') ?>
        </div>
    </div>
    <!-- show this button when item load on button  -->
    <div ng-show="MyWishListCtrl.paginationLinks.length != 0" class="lw-pagination-container lw-pagination-product-center"></div>
    <!-- / show this button when item load on button -->
</div>

