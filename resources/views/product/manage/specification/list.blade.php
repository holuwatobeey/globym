<div ng-controller="ProductSpecificationController as productSpecificationCtrl">	
	<div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __tr( 'Specifications' ) ?></h3>
        <!-- /main heading -->
    </div>

    <!-- old specification list -->
    <div class="table-responsive" ng-if="productSpecificationCtrl.oldSpecification.length > 0 && canAccess('manage.product.specification.list')">
        <table class="table table-bordered table-sm">
            <tbody>
                <tr>
                    <td colspan="2"><strong><?= __tr( 'Specification' ) ?></strong></td>
                </tr>
                <tr>
                    <td  class="small"><strong><?= __tr( 'Name' ) ?></strong></td>
                    <td  class="small"><strong><?= __tr( 'Value' ) ?></strong></td>
                </tr>
                <tr ng-repeat="oldSpec in productSpecificationCtrl.oldSpecification">
                    <td ng-bind="oldSpec.name"></td>
                    <td ng-bind="oldSpec.value"></td>
                </tr>
            </tbody>
        </table>
    </div>    
    <!-- / old specification list -->  

    <h3 ng-if="productSpecificationCtrl.initialPageContentLoaded && productSpecificationCtrl.isSpecificationExist && canAccess('manage.product.specification.list') && canAccess('manage.product.specification.change_preset')">
        <span href class="badge badge-light">[[ productSpecificationCtrl.presetTitle ]]
            <a href aria-hidden="true" ng-click="productSpecificationCtrl.changeSpecificationPreset('delete')">&times;</a>
        </span>
    </h3>
    

    <!-- Assign Specification -->
    <lw-form-selectize-field field-for="specification_presets__id" label="<?= __tr( 'Assign Specification Preset' ) ?>" class="lw-selectize-item mb-4" ng-if="productSpecificationCtrl.initialPageContentLoaded && !productSpecificationCtrl.isSpecificationExist && canAccess('manage.product.specification.list') && canAccess('manage.product.specification.add')"> 
        <selectize 
            config='productSpecificationCtrl.assign_specification_select_config' 
            class="lw-form-field form-control" 
            name="specification_presets__id" 
            ng-model="productSpecificationCtrl.specification_presets__id"
            ng-change="productSpecificationCtrl.changeSpecificationPreset(productSpecificationCtrl.specification_presets__id)"
            options="productSpecificationCtrl.specificationList"
            placeholder="<?= __tr( 'Select Preset' ) ?>" 
        ></selectize>
    </lw-form-selectize-field>
    <!-- /Assign Specification -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="productSpecificationCtrl.[[ productSpecificationCtrl.ngFormName ]]" 
        novalidate>

        <table class="table table-bordered table-sm" ng-if="productSpecificationCtrl.initialPageContentLoaded">
            <tbody ng-repeat="(preset, productSpecifications) in productSpecificationCtrl.specificationData.specifications" ng-if="productSpecificationCtrl.specificationData.specifications != ''">
                <tr>
                    <th colspan="2">
                        [[ preset ]]
                        <button class="lw-btn btn btn-sm btn-light pull-right" title="<?= __tr( 'Add New Specifcation' ) ?>" ng-if="preset == 'Custom'" ng-click="productSpecificationCtrl.add()"><i class="fa fa-plus"></i> <?= __tr( 'Add New Specifcation' ) ?></button>
                    </th>              
                </tr>
                <tr ng-repeat="(index, specification) in productSpecifications" ng-if="specifications != ''">
                    <td align="center" class="lw-spec-table-align" width="10%">
                        [[ specification.label ]]
                    </td>
                    <td>
                        <ul class="list-inline lw-inline-specification">
                            <li class="list-inline-item" ng-repeat="existingValue in specification.existingSpecification">
                                <h5><span class="badge badge-pill badge-light">
                                    [[ existingValue.value ]] 
                                    <a href lw-transliterate entity-type="specification_value" entity-id="[[existingValue.specificationValueId]]" entity-key="value" entity-string="[[ existingValue.value ]]" input-type="1">
                                    <i class="fa fa-globe"></i></a>

                                    <a ng-if="canAccess('manage.product.specification.delete')" href aria-hidden="true" ng-click="productSpecificationCtrl.delete(existingValue._id, existingValue.value)">&times;</a>
                                </span></h5>
                            </li>
                        </ul>
                        
                        <lw-form-selectize-field field-for="specifications.[[ preset ]].[[ index ]].value" label="" class="lw-selectize-item" ng-if="canAccess('manage.product.specification.add') || canAccess('manage.product.specification.edit')"> 
                            <selectize config='productSpecificationCtrl.specification_select_config' 
                                class="lw-form-field form-control mb-3" 
                                name="specifications.[[ preset ]].[[ index ]].value" 
                                ng-model="productSpecificationCtrl.specificationData.specifications[preset][index]['value']"
                                options="specification.specValues"
                                placeholder="<?= __tr( 'Add New Specification Values' ) ?>">                        
                            </selectize>
                        </lw-form-selectize-field>
                    </td>
                </tr>
            </tbody>
            <tbody>
                <tr ng-if="canAccess('manage.product.specification.update')">
                    <td colspan="2">
                        <button ng-if="canAccess('manage.product.specification.update')" class="lw-btn btn btn-primary btn-sm" title="<?= __tr( 'Update' ) ?>" ng-if="productSpecificationCtrl.specifications != ''" ng-href="" ng-click="productSpecificationCtrl.addSpecification(1, productSpecificationCtrl.specifications)"><?= __tr( 'Update' ) ?></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <!-- <table ng-if="productSpecificationCtrl.initialPageContentLoaded && productSpecificationCtrl.specifications.length == 0">
        <tbody>
            <th>
                <?= __tr('There are no specification found.') ?>
            </th>
        </tbody>
    </table> -->
</div>