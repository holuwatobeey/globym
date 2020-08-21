<div class="row">
    <div class="col-12">
        <h3 class="title float-left">
            <?= __tr('Compare Products') ?>
    </h3>
    </div>
    <div class="col-12">
        <hr>
    </div>
</div>
<div ng-controller="ProductCompareController as productCompareCtrl">
    <div class="table-responsive" ng-if="productCompareCtrl.productCompareData.length > 0">
       <table class="table table-bordered mt-4 lw-compare-table" style="width:[[ (productCompareCtrl.productCompareData.length * 350) + 350 ]]px">
        <tbody>
            <tr>
                <th class="lw-compare-col"><?= __tr('Product Specifications') ?></th>
                <td class="lw-compare-col" ng-repeat="product in productCompareCtrl.productCompareData">
                    <div class="lw-product-row-list-item-thumbnail">
                        <!-- remove product in compare -->
                        <span class="float-right">
                            <a class="btn btn-link" href ng-click="productCompareCtrl.removeProduct(product.id)" title="<?= __tr('Remove Product') ?>">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                        </span>
                        <br>
                        <!-- remove product in compare -->
                        <a href="[[product.detailURL]]"><img class="product-item-thumb-image lw-product-compare-img lazy" ng-src="[[product.productImage]]" alt=""></a>
                    </div>
                    <div class="mt-3">
                        <a href="[[product.detailURL]]">
                            <strong ng-bind="product.name"></strong> 
                        </a>
                        <br>
                        
                        <strong ng-bind="product.price"></strong>

                        <small><strike class="lw-price" ng-bind="product.old_price"></strike></small>
                    </div>
                    <div class="mt-2">
                         <a class="btn btn-sm btn-warning" href="[[product.detailURL]]">
                            <?= __tr('Add to Cart') ?>
                        </a>
                    </div>
                </td>
                <td class="lw-compare-col">
                    <a href="<?= route('products') ?>" class="btn btn-primary"><?= __tr('Add Product') ?></a>
                </td>
            </tr>
            <tr ng-repeat="specification in productCompareCtrl.specificationCollection">
                <th class="lw-compare-col" ng-bind="specification.label">
                </th>
                <td class="lw-compare-col" ng-repeat="product in productCompareCtrl.productCompareData" ng-init="parent = $last">

                    <span ng-if="productCompareCtrl.getPaire(product.specCollection, specification.specId).length > 0" ng-repeat="specData in productCompareCtrl.getPaire(product.specCollection, specification.specId)" ng-bind="specData.value">        
                    </span>

                    <span ng-if="productCompareCtrl.getPaire(product.specCollection, specification.specId).length == 0">-</span>
                </td>
                <td class="lw-compare-col lw-blank-td">
            </td>
            </tr>
        </tbody>
        </table>
    </div>

    <div  ng-if="productCompareCtrl.productCompareData.length == 0">
        <div class="alert alert-info">
            <?= __tr('There are no product in compare list.') ?>
        </div>
        <a class="btn btn-outline-secondary" href="{{ URL::previous() }}">
            <i class="fa fa-reply" aria-hidden="true"></i> Go Back</a>
    </div>
</div>