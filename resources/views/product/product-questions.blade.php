<div ng-controller="ProductAllQuestionsController as productAllQuestionsCtrl">
    <div class="lw-section-heading-block">
        <!--   main heading  -->
        <h3 class="lw-section-heading"><?=  __tr('Questions and Answers')  ?></h3>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
                <!-- Place somewhere in the <body> of your page -->
                @if (!empty($productData))
                <!-- product thumbnail image tag -->
                <a href="<?=  $productData['detailURL'] ?>" class="mt-2">
                    <img ng-src="<?=  $productData['productImage'] ?>" class="lw-product-reviews-img">
                </a>
                <!-- / product thumbnail image tag -->
                @endif

                <!-- product image data -->
                <div>
                    <span>
                        <h4><?=  $productData['title'] ?></h4>
                    </span>
                   
                    <br>
                </div>
                 <!-- / product image data -->
            </div>

            <div class="col-sm-9">
                <h3 class="lw-section-heading"><?=  __tr('Questions and Answers')  ?></h3>

                <div class="card mb-3">
                    <ul class="list-group list-group-flush" >
                        <li class="list-group-item" ng-repeat="questionData in productAllQuestionsCtrl.productQuestions">
                           
                            <strong><?= __tr('Q :') ?>
                                <span ng-bind="questionData.question"></span>
                            </strong><br>

                            <strong><?= __tr('A :') ?></strong>
                            <span ng-bind-html="questionData.answer"></span>
                            <br><br>
                            <p>
                                <em>[[questionData.humanReadableDate]] </em>
                                by [[questionData.fullName]]
                            </p>
                        </li>
                    </ul>
                </div>

                <div ng-if="productAllQuestionsCtrl.productQuestions.length == 0">
                    <p><?= __tr('There are no FAQs.') ?></p>
                </div>

                <!-- show this button when item load on button  -->
                <div class="lw-pagination-container lw-pagination-product-center"></div>
                <!-- / show this button when item load on button -->
                </div>
        </div>

    </div>


</div>