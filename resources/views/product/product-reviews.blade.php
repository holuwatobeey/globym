<div ng-controller="ProductAllReviewsController as productAllReviewsCtrl">
    <div class="lw-section-heading-block">
        <!--   main heading  -->
        <h3 class="lw-section-heading"><?=  __tr('All Reviews')  ?></h3>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
                <!-- Place somewhere in the <body> of your page -->
                    @if (!empty($productData))
                    <!-- product thumbnail image tag -->
                    <div class="">
                        <a href="<?=  $productData['detailURL'] ?>" class="mt-2">
                            <img src="<?=  $productData['productImage'] ?>" class="lw-product-reviews-img">
                        </a>
                    </div>
                    <!-- / product thumbnail image tag -->
                    @endif

                    <!-- product image data -->
                    <div>
                        <span>
                            <h4><?=  $productData['title'] ?></h4>
                        </span>
                       
                        <span class="badge badge-success">
                            <?=  $productData['itemRating']['rate'] ?>
                        </span>
                           <?=  $productData['itemRating']['totalVotes'] ?> <?= __tr('vote(s)') ?>
                        <br>
                    </div>
                     <!-- / product image data -->
            </div>

            <div class="col-sm-9">
                <h3 class="lw-section-heading"><?=  __tr('All Reviews')  ?></h3>

                <div class="card mb-3">
                    <ul class="list-group list-group-flush" >
                        <li class="list-group-item" ng-if="productAllReviewsCtrl.productReviews.length > 0 && review.review.length > 0" ng-repeat="review in productAllReviewsCtrl.productReviews">

                            <div>
                                <span class="badge" 
                                ng-class="{
                                    'badge-danger' :  review.rating == '1',
                                    'badge-warning' :  review.rating == '2',
                                    'badge-info' :  review.rating == '3',
                                    'badge-success' :  review.rating == '5' ||  review.rating == '4',
                                }">
                                    [[ review.rating ]]  <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
                                &nbsp;
                                <span>
                                    [[ review.userRatingReview ]]
                                </span>
                                
                            </div>

                            <br>
                            <span ng-if="review.review != ''">
                                <p ng-bind-html="review.review"></p>
                            </span>
                            

                            <p>
                                <em>[[review.humanReadableDate]] </em>
                                by [[review.fullName]]
                            </p>

                        </li>
                    </ul>
                </div>

                <!-- show this button when item load on button  -->
                <div class="lw-pagination-container lw-pagination-product-center"></div>
                <!-- / show this button when item load on button -->
                </div>
        </div>

    </div>


</div>