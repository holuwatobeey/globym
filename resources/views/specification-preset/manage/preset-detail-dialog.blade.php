<?php 
/*
*  Component  : SpecificationPreset
*  View       : SpecificationPreset Controller
*  Engine     : SpecificationPresetEngine  
*  File       : list.blade.php  
*  Controller : PresetDetailController 
----------------------------------------------------------------------------- */
?>
<div class="lw-dialog" ng-controller="PresetDetailController as presetDetailCtrl">
    
    <!--  main heading  -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Preset Details' )  ?></h3>
    </div>
    <!--  /main heading  -->

    <!--  Labels  --> 
    <div class="card" ng-if="presetDetailCtrl.presetData.labels.length != 0">
       <div class="card-header"><strong>[[presetDetailCtrl.presetData.presetTitle]]</strong></div>
           <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <?= __tr('Labels') ?> :
                    <p ng-bind="presetDetailCtrl.presetData.labels"></p>
                </li>
           </ul>
    </div>
    <!-- / Labels  --> 

    <div class="alert alert-info" role="alert" ng-if="presetDetailCtrl.presetData.labels.length == 0">
        <?= __tr('There are no labels.') ?>
    </div>

    <!--  close dialog button  -->
    <div class="modal-footer">
        <button type="button" class="lw-btn btn btn-light" ng-click="presetDetailCtrl.closeDialog()" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
    </div>
   <!--  /close dialog button  -->
</div>