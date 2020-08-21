<?php 
/*
*  Component  : SpecificationPreset
*  View       : Preset Add Controller
*  Engine     : SpecificationPresetEngine  
*  File       : add-dialog.blade.php  
*  Controller : PresetAddController as PresetAddCtrl 
----------------------------------------------------------------------------- */
?>
<div ng-controller="PresetAddController as PresetAddCtrl" class="lw-dialog">
    <!-- Modal Heading -->
    <div class="modal-header">
        <!-- main heading -->
            <h3 class="modal-title"><?= __tr( 'Add New Preset' ) ?></h3>
        <!-- /main heading -->
    </div>
    <!-- /Modal Heading -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="PresetAddCtrl.[[ PresetAddCtrl.ngFormName ]]" 
        ng-submit="PresetAddCtrl.submit()" 
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
                  ng-model="PresetAddCtrl.presetData.title" />
            </lw-form-field>
            <!-- /Title -->

            <div class="card mt-5">
                <div class="card-body">
                    <h5 class="card-title"><?= __tr('Specifications') ?></h5>
                    <div ng-repeat="(index, specification) in PresetAddCtrl.presetData.specficationLabels">
                        <!-- specification -->
                        <lw-form-field field-for="specficationLabels.[[ index ]].label" v-label="<?= __tr('Label') ?>">
                            <div class="input-group">
                                <input type="text" 
                                class="lw-form-field form-control"
                                name="specficationLabels.[[ index ]].label"
                                ng-model="PresetAddCtrl.presetData.specficationLabels[index]['label']" />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="specficationLabels.[[ index ]].use_for_filter" id="specficationLabels.[[ index ]].use_for_filter" ng-model="PresetAddCtrl.presetData.specficationLabels[index]['use_for_filter']" title="<?= __tr('Use this for product filter') ?>">
                                            <label class="custom-control-label" for="specficationLabels.[[ index ]].use_for_filter" title="<?= __tr('Use this for product filter') ?>"><?= __tr('Use for Filter') ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" ng-click="PresetAddCtrl.removeRow(index)" ng-disabled="$first ? true : false"><i class="fa fa-remove"></i></button>
                                </div>
                            </div>
                        </lw-form-field>
                        <!-- /specification -->
                    </div>
                    <button type="button" class="btn btn-light" ng-click="PresetAddCtrl.addMoreRow()"><i class="fa fa-plus"></i> <?= __tr('Add More') ?></button>
                </div>
            </div>
        </div>
        <!-- Modal Body -->
        <br>
        <!-- /Modal footer -->
        <div class="modal-footer">
            
            <button type="submit" class="btn btn-primary lw-btn" title="<?= __tr('Add') ?>"><?= __tr('Add') ?></button>

            <button type="button" class="btn btn-light lw-btn" ng-click="PresetAddCtrl.closeDialog()" title="<?= __tr('Cancel') ?>"><?= __tr('Cancel') ?></button>

        </div>
        <!-- /Modal footer -->

    </form>
    <!-- /form action -->
</div>