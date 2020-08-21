<?php
/*
*  Component  : Settings
*  View       : Slider Settings
*  Engine     : ManageSliderSettingsEngine.js
*  File       : edit.blade.php
*  Controller : SliderEditController as sliderEditCtrl
----------------------------------------------------------------------------- */
?>
<div ng-controller="SliderEditController as sliderEditCtrl">
    <div class="lw-section-heading-block">
        <div class="lw-breadcrumb">
            <a ui-sref="slider_setting" title="<?= __tr('Go To Manage Sliders List') ?>"> <?= __tr( 'Manage Sliders' ) ?> </a> &raquo;
        </div>
        <!-- main heading -->
        <div class="lw-section-heading">
            <h3><?= __tr( 'Edit [[sliderEditCtrl.sliderTitle]] Slider' ) ?></h3>
        </div>
        <!-- /main heading -->
    </div>


    <form class="lw-form lw-ng-form" 
        name="sliderEditCtrl.[[ sliderEditCtrl.ngFormName ]]" 
        novalidate>

        <!-- Title -->
        <lw-form-field field-for="title" label="<?= __tr( 'Title' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="title"
              ng-required="true"
              ng-minlength="2"
              ng-maxlength="250"
              ng-model="sliderEditCtrl.sliderEditData.title" />
        </lw-form-field>
        <!-- /Title -->

         <!-- /Slider Config -->   
        <div class="form-row">
            <div class="col-md-3 col-lg-3">
                <!-- Out of Stock -->
                <lw-form-checkbox-field field-for="auto_play" class="lw-form-item-box">
                    <div class="custom-control custom-checkbox">
                    <input 
                    type="checkbox" 
                    name="auto_play"
                    class="custom-control-input" 
                    id="autoPlayCheck1"
                    ng-model="sliderEditCtrl.sliderEditData.auto_play">
                    <label class="custom-control-label" for="autoPlayCheck1"><?= __tr( 'Enable Autoplay' ) ?></label>
                    </div>
                </lw-form-checkbox-field>
                <!-- Out of Stock -->
            </div>
            <div class="col-md-6 col-lg-4" >
                <!-- Name --> 
                <lw-form-field field-for="autoplayTimeout" label="<?= __tr( 'Autoplay Speed' ) ?>"> 
                	<div class="input-group">
                    	<input type="number" 
							class="lw-form-field form-control"
							name="autoplayTimeout"
							ng-model="sliderEditCtrl.sliderEditData.autoPlayTimeout" />
                  		<div class="input-group-append">
							<label class="input-group-text" for="inputGroupSelect02"><?= __tr( 'Seconds' ) ?></label>
						</div>
					</div>
                </lw-form-field>
                <!-- Name -->
            </div>
        </div>
        <!-- /Slider Config -->
        <div id="accordion">
            <div id="lw-slides-wrapper">
                <div class="card mb-3 mt-3" ng-repeat="value in sliderEditCtrl.sliderEditData.slides track by $index" data-id="[[ $index ]]">
                    <div class="card-header" id="manageSliderHeading[[$index]]">
                        <div class="lw-handle-div" data-toggle="collapse" data-target="#manageSliderBlock[[$index]]" aria-expanded="false" aria-controls="manageSliderBlock[[$index]]"> 
                            <img ng-if="!value.thumbnailURL" src="<?= url('resources/assets/imgs/default-thumbnail.jpg') ?>" class="img-thumbnail lw-slider-thumbnail" alt="Cinque Terre">

                            <img ng-if="value.thumbnailURL" ng-src="[[value.thumbnailURL]]" class="img-thumbnail lw-slider-thumbnail" alt="Cinque Terre">
                            <a href><i class="fa fa-arrows-v"></i> - <?= __tr('Image Slide') ?></a>

                            <a href ng-disabled="$first" ng-click="sliderEditCtrl.remove($index)" title="<?= __tr( 'Remove' ) ?>" class="float-right"><i class="fa fa-times"></i> </a>
                        </div>
                    </div>
                    <div id="manageSliderBlock[[$index]]" class="collapse" aria-labelledby="manageSliderHeading[[$index]]" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">

                                <div class="col-sm-3">

                                    <div class="card">
                                        <img ng-if="!value.thumbnailURL" src="<?= url('resources/assets/imgs/default-thumbnail.jpg') ?>" class="img-thumbnail" alt="Cinque Terre">
                                        <img ng-if="value.thumbnailURL" ng-src="[[value.thumbnailURL]]" class="img-thumbnail" alt="Cinque Terre">
                                    </div>

                                    <!-- Select Image -->
                                    <div class="form-group">

                                        <div class="text-center mt-3">
                                            <span class="btn btn-primary btn-sm lw-btn-file">
                                                <i class="fa fa-upload"></i> 
                                                        <?=   __tr('Browse Images')   ?>
                                                <span id="lw-spinner-widget-[[$index]]" class="lw-spinner-widget">
                                                    <i class="fa fa-refresh fa-spin"></i>
                                                </span>
                                                <input id="lwFileupload-[[$index]]" type="file" nv-file-select="" ng-click="sliderEditCtrl.addImages($index)" uploader="sliderEditCtrl.uploader" ng-required="true"/>
                                            </span>
                                        </div>

                                        <!-- image validation msg -->
                                        <lw-form-checkbox-field field-for="slides.[[ $index ]].image" v-label="<?= __tr('Image')   ?>" class="text-center">
                                            <input type="hidden" ng-required="true" class="lw-form-field hidden" name="slides.[[ $index ]].image" ng-model="sliderEditCtrl.sliderEditData.slides[$index]['image']"/>
                                        </lw-form-checkbox-field>
                                        <!-- / image validation msg -->
                                    </div>
                                    <!-- /Select Image -->
                                </div>

                                <div class="col-sm-9">
                                    <div class="form-row">
                                        <div class="col-lg-6">
                                            <!-- Name --> 
                                            <lw-form-field field-for="slides.[[ $index ]].caption_1" label="<?= __tr( 'Heading 1' ) ?>">
                                                <div class="input-group">
                                                <input type="text"
													ng-maxlength="150"
													class="lw-form-field form-control"
													placeholder="heading 1" 
													name="slides.[[ $index ]].caption_1"
													ng-model="sliderEditCtrl.sliderEditData.slides[$index]['caption_1']" />
                                                    <div class="input-group-append">

                                                        <button type="btton" class="btn btn-secondary" lw-transliterate entity-type="slider" entity-id="[[ $index ]]" entity-key="caption_1" entity-string="[[ sliderEditCtrl.sliderEditData.slides[$index]['caption_1'] ]]" input-type="1">
                                                            <i class="fa fa-globe"></i>
                                                        </button>

                                                        <button type="btton" 
                                                        class="btn btn-light border"
                                                        title="<?= __tr('Heading 1 Color') ?>" 
                                                        style="background: #[[ sliderEditCtrl.sliderEditData.slides[$index]['caption_1_color'] ]]"
                                                        name="slides.[[ $index ]].caption_1_color"
                                                        ng-model="sliderEditCtrl.sliderEditData.slides[$index]['caption_1_color']" 
                                                        lw-color-picker >
                                                            #
                                                        </button>
                                                    </div>
                                                </div>
                                            </lw-form-field>
                                            <!-- Name -->
                                        </div>
                                        <div class="col-lg-6">
                                            <!-- Name --> 
                                            <lw-form-field field-for="slides.[[ $index ]].caption_2" label="<?= __tr( 'Heading 2' ) ?>"> 
                                                <div class="input-group">
                                                <input type="text" 
													ng-maxlength="150"
													class="lw-form-field form-control"
													name="slides.[[ $index ]].caption_2"
													placeholder="heading 2" 
													ng-model="sliderEditCtrl.sliderEditData.slides[$index]['caption_2']" />
                                                    <div class="input-group-append">
                                                        <button type="btton" class="btn btn-secondary" lw-transliterate entity-type="slider" entity-id="[[ $index ]]" entity-key="caption_2" entity-string="[[ sliderEditCtrl.sliderEditData.slides[$index]['caption_2'] ]]" input-type="1">
                                                            <i class="fa fa-globe"></i>
                                                        </button>
                                                        
                                                        <button type="btton" 
                                                        class="btn btn-light border"
                                                        title="<?= __tr('Heading 2 Color') ?>"
                                                        style="background: #[[ sliderEditCtrl.sliderEditData.slides[$index]['caption_2_color'] ]]"
                                                        name="slides.[[ $index ]].caption_2_color"
                                                        ng-model="sliderEditCtrl.sliderEditData.slides[$index]['caption_2_color']" 
                                                        lw-color-picker >
                                                            #
                                                        </button>
                                                    </div>
                                                </div>
                                            </lw-form-field>
                                            <!-- Name -->
                                        </div>

                                        <div class="col-lg-12">
                                            <!-- Name --> 
                                            <lw-form-field field-for="slides.[[ $index ]].caption_3" 
                                            	label="<?= __tr( 'Description' ) ?>">
                                                <a href lw-transliterate entity-type="slider" entity-id="[[ $index ]]" entity-key="caption_3" entity-string="[[ sliderEditCtrl.sliderEditData.slides[$index]['caption_3'] ]]" input-type="3">
                                                <i class="fa fa-globe"></i></a>

                                            	<textarea class="lw-form-field form-control"
													ng-maxlength="1000"
													placeholder="description"
													name="slides.[[ $index ]].caption_3"
													ng-model="sliderEditCtrl.sliderEditData.slides[$index]['caption_3']">
												</textarea>
                                            </lw-form-field>
                                            <!-- Name -->
                                        </div>

                                        <div class="col-lg-12">
                                            <!-- Selected Theme Color -->
                                            <lw-form-field field-for="bg_color" label="<?= __tr( 'Background Color' ) ?>" > 
                                                <div class="input-group">
                                                    <div class="input-group-prepend" id="basic-addon1">
                                                        <span class="input-group-text" style="background: #[[ sliderEditCtrl.sliderEditData.slides[$index]['bg_color'] ]]">#
                                                        </span>
                                                    </div>
                                                    <input type="text" 
                                                        class="lw-form-field form-control"
                                                        autofocus
                                                        name="slides.[[ $index ]].bg_color"
                                                        lw-color-picker
                                                        readonly 
                                                        ng-model="sliderEditCtrl.sliderEditData.slides[$index]['bg_color']" />
                                                    </div>
                                            </lw-form-field>
                                            <!-- Selected Theme Color -->
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                             
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- button action -->
        <div class="form-group">
            <button class="btn btn-light btn-sm lw-btn" title="<?= __tr( 'Add More' ) ?>" ng-click="sliderEditCtrl.addNewValue()"><i class="fa fa-plus"></i> <?= __tr( 'Add More' ) ?></button>
        </div>

        <br>
        <div class="modal-footer">
            
            <button type="submit" ng-click="sliderEditCtrl.submit()" class="btn btn-primary lw-btn" title="<?= __tr('Save') ?>"><?= __tr('Save') ?></button>

            <button type="button" ui-sref="slider_setting" class="btn btn-light lw-btn" ui-sref="sample" title="<?= __tr('Go Back') ?>"><?= __tr('Back') ?></button>

        </div>

    </form>
    <!-- /form action -->
</div>

<!-- image path and name -->
<script type="text/_template" id="imageListItemTemplate">
  <div class="lw-selectize-item lw-selectize-item-selected">
        <span class="lw-selectize-item-thumb">
        <img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /image path and name -->

<!-- image path and name -->
<script type="text/_template" id="imageListOptionTemplate">
    <div class="lw-selectize-item"><span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /image path and name -->