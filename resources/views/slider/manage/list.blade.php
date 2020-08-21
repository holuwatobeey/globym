<?php
/*
*  Component  : Settings
*  View       : Slider Settings
*  Engine     : ManageSliderSettingsEngine.js
*  File       : slider-setting.blade.php
*  Controller : SliderListController as sliderListCtrl
----------------------------------------------------------------------------- */
?>
<div ng-controller="SliderListController as sliderListCtrl">
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <span>
                <?= __tr( 'Manage Sliders' ) ?>
            </span>
        </h3>
        <!-- /main heading -->

        <!-- button -->
        <div class="pull-right">
            <button ng-if="canAccess('store.settings.slider.write.addSlider') && canAccess('store.settings.slider.read.list')" class="lw-btn btn btn-sm btn-light" title="<?= __tr( 'Add New Slider' ) ?>" ui-sref="slider_setting_add"><i class="fa fa-plus"></i> <?= __tr( 'Add New Slider' ) ?></button>
        </div>
        <!-- /button -->

        <input type="hidden" id="lwSliderDeleteTextMsg" data-message="<?= __tr( 'you want to delete __title__ slider') ?>" data-delete-button-text="<?= __tr('Yes, delete it') ?>" data-success-text="<?= __tr( 'Deleted!') ?>" data-error-text="<?= __tr( 'Unable to Delete!') ?>">
    </div>
  
    <!-- alert message -->
    <div class="alert alert-info">
        <?= __tr('These sliders can be used in home page ( __configureHomePagelink__ ).', [
            '__configureHomePagelink__' => '<a title="Configure Home Page" href ui-sref="store_settings_edit.landing_page">Configure Home Page</a>'
        ]) ?>
    </div> 
    <!-- / alert message -->

    <!-- datatable container -->
    <div class="table-responsive" ng-if="canAccess('store.settings.slider.read.list')">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><?= __tr('Title') ?></th>
                    <th><?= __tr('Action') ?></th>
                </tr>
            </thead>
        <tbody>
            <tr ng-repeat="slider in sliderListCtrl.sliderList">
                <td ng-bind="slider.title"></td>
                <td>
                    <!-- edit button -->
                    <a ng-if="canAccess('store.settings.slider.read.update.data') && canAccess('store.settings.slider.write.update')" class="btn btn-sm btn-light lw-btn" 
                        title="<?= __tr( 'Edit' ) ?>" 
                        ui-sref="slider_setting_edit({'sliderID' :slider.id})">
                        <i class="fa fa-pencil-square-o"></i> <?= __tr( 'Edit' ) ?>
                    </a>
                    <!--/edit button -->

                    <!-- delete button -->
                    <button ng-if="canAccess('store.settings.slider.read.list') && canAccess('store.settings.slider.write.delete')" class="btn btn-sm btn-danger lw-btn" 
                        title="<?= __tr( 'Delete' ) ?>" 
                        ng-click="sliderListCtrl.delete(slider.id, slider.title)">
                        <i class="fa fa-trash"></i> <?= __tr( 'Delete' ) ?>
                    </button>
                    <!--/delete button -->
                </td>
                
            </tr>
            <tr>
                <td colspan="2" class="text-center"  ng-if="sliderListCtrl.sliderList.length == 0">
                    <?= __tr( 'There are no records.' ) ?>
                </td>
            </tr>
            
        </tbody>
        </table>   
    </div>
    <!-- /datatable container -->
    
   <!--  <div ui-view></div> -->

</div>
