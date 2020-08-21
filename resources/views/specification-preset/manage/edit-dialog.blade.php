<?php 
/*
*  Component  : SpecificationPreset
*  View       : Preset Edit Controller
*  Engine     : SpecificationPresetEngine  
*  File       : edit-dialog.blade.php  
*  Controller : PresetEditController as PresetEditCtrl 
----------------------------------------------------------------------------- */
?>
<div  ng-controller="PresetEditController as PresetEditCtrl" class="lw-dialog">
    <!-- Modal Heading -->
    <div class="modal-header">
        <!-- main heading -->
            <h3 class="modal-title"><?= __tr( 'Edit Preset' ) ?></h3>
        <!-- /main heading -->
    </div>
    <!-- /Modal Heading -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="PresetEditCtrl.[[ PresetEditCtrl.ngFormName ]]" 
        ng-submit="PresetEditCtrl.submit()" 
        novalidate>

        <!-- Modal Body -->
        <div class="modal-body">
           
            <!-- Title -->
            <lw-form-field field-for="title" label="<?= __tr( 'Title' ) ?>">
                <input type="text" 
                      class="lw-form-field form-control"
                      name="title"
                      ng-required="true"
                      ng-minlength="2"
                      ng-maxlength="250"
                      ng-model="PresetEditCtrl.presetEditData.title" />
            </lw-form-field>
            <!-- /Title -->

            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title"><?= __tr('Specifications') ?></h5>
                    <div ng-repeat="(index, specification) in PresetEditCtrl.presetEditData.specficationLabels">

                        <!-- specification -->
                        <lw-form-field field-for="specficationLabels.[[ index ]].label" v-label="<?= __tr('Label') ?>">
                            <div class="input-group">
                               <input type="text" 
                                class="lw-form-field form-control"
                                name="specficationLabels.[[ index ]].label"
                                ng-required="true"
                                ng-model="PresetEditCtrl.presetEditData.specficationLabels[index]['label']" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="specficationLabels.[[ index ]].use_for_filter" id="specficationLabels.[[ index ]].use_for_filter" ng-model="PresetEditCtrl.presetEditData.specficationLabels[index]['use_for_filter']" title="<?= __tr('Use this for product filter') ?>">
                                            <label class="custom-control-label" for="specficationLabels.[[ index ]].use_for_filter" title="<?= __tr('Use this for product filter') ?>"><?= __tr('Use for Filter') ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group-append" ng-if="specification._id">
                                    <button type="button" class="btn btn-secondary" lw-transliterate entity-type="specification_preset" entity-id="[[specification._id]]" entity-key="label" entity-string="[[ PresetEditCtrl.presetEditData.specficationLabels[index]['label'] ]]" input-type="1"> <i class="fa fa-globe"></i></button>
                                </div>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" ng-if="!specification._id" ng-click="PresetEditCtrl.removeRow(index)" ng-disabled="$first ? true : false"><i class="fa fa-remove"></i></button>

                                    <button type="button" class="btn btn-outline-secondary" ng-if="specification._id" ng-click="PresetEditCtrl.deleteSpecification(specification._id, index)" ng-disabled="$first ? true : false"><i class="fa fa-remove"></i></button>
                                </div>
                            </div>
                        </lw-form-field>
                        <!-- /specification -->
                    </div>
                    <button type="button" class="btn btn-light" ng-click="PresetEditCtrl.addMoreRow()"><i class="fa fa-plus"></i> <?= __tr('Add More') ?></button>
                </div>
            </div>

        </div>
        <!-- Modal Body -->
        <br>
        <!-- /Modal footer -->
        <div class="modal-footer">
            
            <button type="submit" class="btn btn-primary lw-btn" title="<?= __tr('Update') ?>"><?= __tr('Update') ?></button>

            <button type="button" class="btn btn-light lw-btn" ng-click="PresetEditCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>

        </div>
        <!-- /Modal footer -->

    </form>
    <!-- /form action -->
</div>