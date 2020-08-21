<?php
/*
*  Component  : Configuration
*  Engine     : ManageStoreEngine  
*  File       : landing-page-settings.blade.php  
*  Controller : LandingPageSettingController as landingPageSettingCtrl
----------------------------------------------------------------------------- */ 
?>
<div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
            <h4 class="lw-header"><?= __tr('Configure Home Page') ?></h4>        
        <!-- /main heading -->
    </div>

    <!-- form action -->
    <form class="lw-form lw-ng-form" name="landingPageSettingCtrl.[[ landingPageSettingCtrl.ngFormName ]]"
        novalidate>
 
        <lw-form-field field-for="home_page" label="<?= __tr( 'Default Home Page Content' ) ?>" advance="true">
	    	<selectize config='landingPageSettingCtrl.home_page_select_config' class="lw-form-field form-control" name="home_page" ng-model="landingPageSettingCtrl.editData.home_page" options='landingPageSettingCtrl.homePageSetting' placeholder="<?= __tr( 'Home Page' ) ?>"></selectize>
		</lw-form-field>

		<div ng-show="landingPageSettingCtrl.editData.home_page == 1">
			<?= __tr( 'Click' ) ?> <a ui-sref="pages.edit({ 'pageID' : 1 })"><?= __tr( 'here' ) ?></a> <?= __tr( 'to open Home Page Settings' ) ?> 
		</div>

		<div class="alert alert-info" ng-show="landingPageSettingCtrl.editData.home_page == 5">
	        <?= __tr('Click to configure and use __icon__ to change list order.', [
	            '__icon__' => '<i class="fa fa-arrows-v"></i>'
	        ]) ?>
	    </div>

        <div class="row" ng-show="landingPageSettingCtrl.editData.home_page == 5">
            <div class="col-lg-3 col-sm-3 col-md-4 col-12 mb-2">
                <div class="list-group lw-payment-list-group" id="lwListTab" role="tablist">
                    <span class="mt-2" ng-repeat="landingPage in landingPageSettingCtrl.editData.landingPageData track by $index" data-id="[[$index]]">
                       
                    <a class="list-group-item list-group-item-action" id="list-home-list" data-toggle="list" href="#[[ landingPage.identity ]]" role="tab" aria-controls="home" ng-click="landingPageSettingCtrl.showLandingPageTab($event, $index)">
                        <span ng-if="landingPage.identity != 'Slider' && !landingPage.title">[[ landingPageSettingCtrl.prepareTitle(landingPage.identity) ]]</span>
                        <span ng-if="landingPage.identity != 'Slider' && landingPage.title">[[ landingPageSettingCtrl.prepareTitle(landingPage.title) ]]</span>
                        <span ng-if="landingPage.identity == 'Slider'">Slider</span>
                        <span class="float-right"><i class="fa fa-arrows-v"></i></span>
                    </a>
                    </span>
                </div>
            </div>
            <div class="col-lg-9 col-sm-9 col-md-8 mt-1">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show card" id="Slider" role="tabpanel" aria-labelledby="list-home-list">
                        <div class="card-body">
                            <p>
                                <?= __tr('For Manage Slider go to : ') ?><a href ui-sref="slider_setting"><?= __tr('Manage Slider') ?></a>
                            </p>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" 
                                        class="custom-control-input" 
                                        id="landingPageData.[[ landingPageSettingCtrl.sliderIndex ]].title"
                                        name="landingPageData.[[ landingPageSettingCtrl.sliderIndex ]].title"
                                        ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.sliderIndex]['isEnable']">
                                    <label class="custom-control-label" for="landingPageData.[[ landingPageSettingCtrl.sliderIndex ]].title"><?= __tr('Enable Slider') ?></label>
                                </div><hr>

                                <lw-form-selectize-field field-for="landingPageData.[[ landingPageSettingCtrl.sliderIndex ]].title" label="<?= __tr( 'Select Slider' ) ?>" class="lw-selectize">
                                    <div class="input-group">
                                        <selectize 
                                            config='landingPageSettingCtrl.slider_option_config' 
                                            class="lw-form-field form-control lw-form-field-brand" 
                                            name="landingPageData.[[ landingPageSettingCtrl.sliderIndex ]].title" 
                                            ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.sliderIndex]['title']" 
                                            options='landingPageSettingCtrl.sliderList' 
                                            placeholder="<?= __tr( 'Select Slider' ) ?>">
                                        </selectize>
                                        <div class="input-group-append lw-selectize-custom-addon-btn" ng-if="canAccess('store.settings.slider.write.addSlider') && canAccess('store.settings.slider.read.list')">
                                           <a href ui-sref="slider_setting_add" 
                                           title="<?=  __tr('Add New Slider')  ?>" class="lw-btn btn-sm btn btn-light"><?=  __tr('Add New Slider')  ?></a>
                                        </div>
                                    </div>
                                </lw-form-selectize-field>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card" id="PageContent" role="tabpanel" aria-labelledby="list-home-list">
                        <div class="card-body">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" 
                                    class="custom-control-input" 
                                    id="landingPageData.[[ landingPageSettingCtrl.pageContentIndex ]].title"
                                    name="landingPageData.[[ landingPageSettingCtrl.pageContentIndex ]].title"
                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.pageContentIndex]['isEnable']">
                                <label class="custom-control-label" for="landingPageData.[[ landingPageSettingCtrl.pageContentIndex ]].title"><?= __tr('Enable Page Content') ?></label>
                            </div><hr>

                            <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.pageContentIndex ]].pageContent" label="<?= __tr( 'Page Content' ) ?>" > 
                                <textarea 
                                    name="landingPageData.[[ landingPageSettingCtrl.pageContentIndex ]].pageContent" 
                                    class="lw-form-field form-control" 
                                    cols="30" 
                                    rows="10" 
                                    ng-minlength="6" 
                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.pageContentIndex]['pageContent']" 
                                    lw-ck-editor 
                                    >
                                </textarea>
                            </lw-form-field>
                        </div>
                    </div>
                    <div class="tab-pane fade card" id="latestProduct" role="tabpanel" aria-labelledby="list-home-list">
                        <div class="card-body">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" 
                                        class="custom-control-input" 
                                        id="landingPageData.[[ landingPageSettingCtrl.latestProductIndex ]].title"
                                        name="landingPageData.[[ landingPageSettingCtrl.latestProductIndex ]].title"
                                        ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.latestProductIndex]['isEnable']">
                                    <label class="custom-control-label" for="landingPageData.[[ landingPageSettingCtrl.latestProductIndex ]].title"><?= __tr('Enable Latest Products') ?></label>
                                </div>

                            <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.latestProductIndex ]].productCount" label="<?= __tr( 'Latest Product' ) ?>"> 
                                <input type="number" 
                                      class="lw-form-field form-control"
                                      autofocus
                                      name="landingPageData.[[ landingPageSettingCtrl.latestProductIndex ]].productCount"
                                      ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.latestProductIndex]['productCount']" />
                            </lw-form-field>
                        </div>
                    </div>
                    <div class="tab-pane fade card" id="featuredProduct" role="tabpanel" aria-labelledby="list-home-list">
                        <div class="card-body">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" 
                                    class="custom-control-input" 
                                    id="landingPageData.[[ landingPageSettingCtrl.featureProductIndex ]].title"
                                    name="landingPageData.[[ landingPageSettingCtrl.featureProductIndex ]].title"
                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.featureProductIndex]['isEnable']">
                                <label class="custom-control-label" for="landingPageData.[[ landingPageSettingCtrl.featureProductIndex ]].title"><?= __tr('Enable Featured Product') ?></label>
                            </div>

                            <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.featureProductIndex ]].featuredProductCount" label="<?= __tr( 'Featured Product' ) ?>"> 
                                <input type="number" 
                                      class="lw-form-field form-control"
                                      autofocus
                                      name="landingPageData.[[ landingPageSettingCtrl.featureProductIndex ]].featuredProductCount"
                                      ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.featureProductIndex]['featuredProductCount']" />
                            </lw-form-field>
                        </div>
                    </div>
                    <div class="tab-pane fade card" id="popularProduct" role="tabpanel" aria-labelledby="list-home-list">
                        <div class="card-body">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" 
                                    class="custom-control-input" 
                                    id="landingPageData.[[ landingPageSettingCtrl.popularPageIndex ]].title"
                                    name="landingPageData.[[ landingPageSettingCtrl.popularPageIndex ]].title"
                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.popularPageIndex]['isEnable']">
                                <label class="custom-control-label" for="landingPageData.[[ landingPageSettingCtrl.popularPageIndex ]].title"><?= __tr('Enable Popular Product') ?></label>
                            </div>

                            <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.popularPageIndex ]].popularProductCount" label="<?= __tr( 'Popular Product' ) ?>"> 
                                <input type="number" 
                                      class="lw-form-field form-control"
                                      autofocus
                                      name="landingPageData.[[ landingPageSettingCtrl.popularPageIndex ]].popularProductCount"
                                      ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.popularPageIndex]['popularProductCount']" />
                            </lw-form-field>
                        </div>
                    </div>
                    <div class="tab-pane fade card" id="bannerContent1" role="tabpanel" aria-labelledby="list-home-list">
                        <div class="card-body">
                            <div class="custom-control custom-checkbox mr-sm-2">

                                <input type="checkbox" 
                                    class="custom-control-input" 
                                    id="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].title"
                                    name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].title"
                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['isEnable']">
                                <label class="custom-control-label" for="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].title"><?= __tr('Enable 3 Box Banner') ?></label>
                            </div><hr>
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="cart-title">
                                        <h5><?= __tr('Left Side Big Box') ?></h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <img ng-if="!landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_image_thumb']" src="<?= url('resources/assets/imgs/default-thumbnail.jpg') ?>" class="img-thumbnail" alt="Cinque Terre">

                                            <img ng-if="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_image_thumb']" ng-src="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_image_thumb'] ]]" class="img-thumbnail" alt="Cinque Terre">

                                            <div class="text-center mt-3">
                                                <span class="btn btn-primary btn-sm lw-btn-file"><i class="fa fa-upload"></i> <?=   __tr('Browse Images')   ?>
                                                    <input type="file" nv-file-select="" ng-click="landingPageSettingCtrl.addImages([[ landingPageSettingCtrl.banner1Index ]], 'banner_1_section_1_image_thumb',  'banner_1_section_1_image')" uploader="landingPageSettingCtrl.uploader"/>
                                                </span>

                                                <!-- image validation msg -->
                                                <lw-form-checkbox-field field-for="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_1_image_thumb" v-label="<?= __tr('Image')   ?>" class="text-center">
                                                    <input type="hidden" class="lw-form-field hidden" name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_1_image_thumb" ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_image_thumb']"/>
                                                </lw-form-checkbox-field>
                                                <!-- / image validation msg -->
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="form-row">
                                                <div class="col">
                                                <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_1_heading_1" label="<?= __tr( 'Heading 1' ) ?>"> 
                                                    <div class="input-group">
                                                        <input type="text" 
                                                            class="lw-form-field form-control"
                                                            ng-maxlength="150"
                                                            name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_1_heading_1"
                                                            placeholder="Heading 1" 
                                                            ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_heading_1']" />
                                                        <div class="input-group-append">
                                                        	<button type="btton" class="btn btn-secondary" lw-transliterate
                                                        		entity-type="landing_page_settings" entity-id="null" 
                                                        		entity-key="banner_1_section_1_heading_1" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_heading_1'] ]]" input-type="1">
									                            <i class="fa fa-globe"></i></button>
                                                            <button type="btton" 
                                                                class="btn btn-light border"
                                                                title="<?= __tr('Heading 1 Color') ?>"
                                                                style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_heading_1_color'] ]]"
                                                                name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_1_heading_1_color"
                                                                ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_heading_1_color']" 
                                                                lw-color-picker>#
                                                            </button>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                </div>
                                                <div class="col">
                                                <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_1_heading_2" label="<?= __tr( 'Heading 2' ) ?>">
                                                    <div class="input-group">
                                                        <input type="text" 
                                                            class="lw-form-field form-control"
                                                            ng-maxlength="150"
                                                            name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_1_heading_2"
                                                            placeholder="Heading 2" 
                                                            ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_heading_2']" />
                                                        <div class="input-group-append">
                                                        	<button type="btton" class="btn btn-secondary" lw-transliterate
                                                        		entity-type="landing_page_settings" entity-id="null" 
                                                        		entity-key="banner_1_section_1_heading_2" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_heading_2'] ]]" input-type="1">
									                            <i class="fa fa-globe"></i></button>

                                                            <button type="btton" 
                                                                class="btn btn-light border"
                                                                title="<?= __tr('Heading 1 Color') ?>"
                                                                style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_heading_2_color'] ]]"
                                                                name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_1_heading_2_color"
                                                                ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_heading_2_color']" 
                                                                lw-color-picker>#
                                                            </button>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                </div>
                                            </div>
                                            <lw-form-field field-for="slides.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_1_description" label="<?= __tr( 'Description' ) ?>">
                                            	<a href type="btton" lw-transliterate class="float-right" 
                                            		entity-type="landing_page_settings" entity-id="null" 
                                            		entity-key="banner_1_section_1_description" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_description'] ]]" input-type="1">
						                            <i class="fa fa-globe"></i></a>
                                                <textarea class="lw-form-field form-control"
                                                    name="slides.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_1_description"
                                                    ng-maxlength="1000"
                                                    placeholder="Description" 
                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_description']" ></textarea>
                                            </lw-form-field>
                                            <!-- Selected Theme Color -->
                                            <lw-form-field field-for="banner_1_section_1_background_color" label="<?= __tr( 'Background Color' ) ?>" > 
                                                <div class="input-group">
                                                    <div class="input-group-prepend" id="basic-addon1">
                                                        <span class="input-group-text" style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_background_color'] ]]">#
                                                        </span>
                                                    </div>
                                                    <input type="text" 
                                                        class="lw-form-field form-control"
                                                        name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_1_background_color"
                                                        lw-color-picker
                                                        readonly 
                                                        ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_1_background_color']" />
                                                    </div>
                                            </lw-form-field>
                                            <!-- Selected Theme Color -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="cart-title">
                                        <h5><?= __tr('Upper Right Side Box') ?></h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <img ng-if="!landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_2_image_thumb']" src="<?= url('resources/assets/imgs/default-thumbnail.jpg') ?>" class="img-thumbnail" alt="Cinque Terre">

                                            <img ng-if="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_2_image_thumb']" ng-src="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_2_image_thumb'] ]]" class="img-thumbnail" alt="Cinque Terre">

                                            <div class="text-center mt-3">
                                                <span class="btn btn-primary btn-sm lw-btn-file"><i class="fa fa-upload"></i> <?=   __tr('Browse Images')   ?>
                                                    <input type="file" nv-file-select="" ng-click="landingPageSettingCtrl.addImages([[ landingPageSettingCtrl.banner1Index ]], 'banner_1_section_2_image_thumb', 'banner_1_section_2_image')" uploader="landingPageSettingCtrl.uploader"/>
                                                </span>

                                                <!-- image validation msg -->
                                                <lw-form-checkbox-field field-for="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_2_image_thumb" v-label="<?= __tr('Image')   ?>" class="text-center">
                                                    <input type="hidden" class="lw-form-field hidden" name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_2_image_thumb" ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_2_image_thumb']"/>
                                                </lw-form-checkbox-field>
                                                <!-- / image validation msg -->
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="form-row">
                                                <div class="col">
                                                <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_2_heading_1" label="<?= __tr( 'Heading 1' ) ?>">
                                                    <div class="input-group">
                                                        <input type="text" 
                                                            class="lw-form-field form-control"
                                                            ng-maxlength="150"
                                                            name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_2_heading_1"
                                                            placeholder="Heading 1" 
                                                            ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_2_heading_1']" />
                                                        <div class="input-group-append">
                                                        	
                                                        	<button type="btton" class="btn btn-secondary" lw-transliterate
                                                        		entity-type="landing_page_settings" entity-id="null" 
                                                        		entity-key="baner_1_section_2_heading_1" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_2_heading_1'] ]]" input-type="1">
									                            <i class="fa fa-globe"></i></button>

                                                            <button type="btton" 
                                                                class="btn btn-light border"
                                                                title="<?= __tr('Heading 1 Color') ?>"
                                                                style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_2_heading_1_color'] ]]"
                                                                name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_2_heading_1_color"
                                                                ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_2_heading_1_color']" 
                                                                lw-color-picker>#
                                                            </button>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                </div>
                                                <div class="col">
                                                <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_2_heading_2" label="<?= __tr( 'Heading 2' ) ?>"> 
                                                    <div class="input-group">
                                                        <input type="text" 
                                                            class="lw-form-field form-control"
                                                            ng-maxlength="150"
                                                            name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_2_heading_2"
                                                            placeholder="Heading 2" 
                                                            ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_2_heading_2']" />
                                                        <div class="input-group-append">

                                                        	<button type="btton" class="btn btn-secondary" lw-transliterate
                                                        		entity-type="landing_page_settings" entity-id="null" 
                                                        		entity-key="baner_1_section_2_heading_2" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_2_heading_2'] ]]" input-type="1">
									                            <i class="fa fa-globe"></i></button>

                                                            <button type="btton" 
                                                                class="btn btn-light border"
                                                                title="<?= __tr('Heading 2 Color') ?>"
                                                                style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_2_heading_2_color'] ]]"
                                                                name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_2_heading_2_color"
                                                                ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_2_heading_2_color']" 
                                                                lw-color-picker>#
                                                            </button>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                </div>
                                            </div>
                                            <lw-form-field field-for="slides.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_2_description" label="<?= __tr( 'Description' ) ?>">
                                            	<a href type="btton" lw-transliterate class="float-right" 
													entity-type="landing_page_settings" entity-id="null" 
													entity-key="baner_1_section_2_description" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_2_description'] ]]" input-type="1">
													<i class="fa fa-globe"></i></a>
                                                <textarea class="lw-form-field form-control"
                                                    name="slides.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_2_description"
                                                    ng-maxlength="1000"
                                                    placeholder="Description"
                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_2_description']" ></textarea>
                                            </lw-form-field>

                                            <!-- Selected Theme Color -->
                                            <lw-form-field field-for="banner_1_section_1_background_color" label="<?= __tr( 'Background Color' ) ?>" > 
                                                <div class="input-group">
                                                    <div class="input-group-prepend" id="basic-addon1">
                                                        <span class="input-group-text" style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_2_background_color'] ]]">#
                                                        </span>
                                                    </div>
                                                    <input type="text" 
                                                        class="lw-form-field form-control"
                                                        name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_2_background_color"
                                                        lw-color-picker
                                                        readonly 
                                                        ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_2_background_color']" />
                                                    </div>
                                            </lw-form-field>
                                            <!-- Selected Theme Color -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="cart-title">
                                        <h5><?= __tr('Bottom Right Side Box') ?></h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <img ng-if="!landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_3_image_thumb']" src="<?= url('resources/assets/imgs/default-thumbnail.jpg') ?>" class="img-thumbnail" alt="Cinque Terre">

                                            <img ng-if="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_3_image_thumb']" ng-src="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_3_image_thumb'] ]]" class="img-thumbnail" alt="Cinque Terre">

                                            <div class="text-center mt-3">
                                                <span class="btn btn-primary btn-sm lw-btn-file"><i class="fa fa-upload"></i> <?=   __tr('Browse Images')   ?>
                                                    <input type="file" nv-file-select="" ng-click="landingPageSettingCtrl.addImages([[ landingPageSettingCtrl.banner1Index ]], 'banner_1_section_3_image_thumb', 'banner_1_section_3_image')" uploader="landingPageSettingCtrl.uploader"/>
                                                </span>

                                                <!-- image validation msg -->
                                                <lw-form-checkbox-field field-for="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_3_image_thumb" v-label="<?= __tr('Image')   ?>" class="text-center">
                                                    <input type="hidden" class="lw-form-field hidden" name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].banner_1_section_3_image_thumb" ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['banner_1_section_3_image_thumb']"/>
                                                </lw-form-checkbox-field>
                                                <!-- / image validation msg -->
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="form-row">
                                                <div class="col">
                                                <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_3_heading_1" label="<?= __tr( 'Heading 1' ) ?>">
                                                    <div class="input-group">
                                                    <input type="text" 
                                                        class="lw-form-field form-control"
                                                        ng-maxlength="150"
                                                        name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_3_heading_1"
                                                        placeholder="Heading 1" 
                                                        ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_3_heading_1']" />
                                                        <div class="input-group-append">

                                                        	<button type="btton" class="btn btn-secondary" lw-transliterate
																entity-type="landing_page_settings" entity-id="null" 
																entity-key="baner_1_section_3_heading_1" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_3_heading_1'] ]]" input-type="1">
															    <i class="fa fa-globe"></i></button>

                                                            <button type="btton" 
                                                                class="btn btn-light border"
                                                                title="<?= __tr('Heading 1 Color') ?>"
                                                                style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_3_heading_1_color'] ]]"
                                                                name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_3_heading_1_color"
                                                                ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_3_heading_1_color']" 
                                                                lw-color-picker>#
                                                            </button>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                </div>
                                                <div class="col">
                                                <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_3_heading_2" label="<?= __tr( 'Heading 2' ) ?>"> 
                                                    <div class="input-group">
                                                    <input type="text" 
                                                        class="lw-form-field form-control"
                                                        ng-maxlength="150"
                                                        name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_3_heading_2"
                                                        placeholder="Heading 2" 
                                                        ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_3_heading_2']" />
                                                        <div class="input-group-append">

                                                        	<button type="btton" class="btn btn-secondary" lw-transliterate
																entity-type="landing_page_settings" entity-id="null" 
																entity-key="baner_1_section_3_heading_2" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_3_heading_2'] ]]" input-type="1">
															    <i class="fa fa-globe"></i></button>

                                                            <button type="btton" 
                                                                class="btn btn-light border"
                                                                title="<?= __tr('Heading 1 Color') ?>"
                                                                style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_3_heading_2_color'] ]]"
                                                                name="landingPageData.5.baner_1_section_3_heading_2_color"
                                                                ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_3_heading_2_color']" 
                                                                lw-color-picker>#
                                                            </button>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                </div>
                                            </div>
                                            <lw-form-field field-for="slides.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_3_description" label="<?= __tr( 'Description' ) ?>">
                                            	
                                            	<a href type="btton" lw-transliterate class="float-right" 
													entity-type="landing_page_settings" entity-id="null" 
													entity-key="baner_1_section_3_description" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_3_description'] ]]" input-type="1">
													<i class="fa fa-globe"></i></a>

                                                <textarea class="lw-form-field form-control"
                                                    name="slides.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_3_description"
                                                    ng-maxlength="1000"
                                                    placeholder="Description"
                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_3_description']" ></textarea>
                                            </lw-form-field>
                                            <!-- Selected Theme Color -->
                                            <lw-form-field field-for="baner_1_section_3_background_color" label="<?= __tr( 'Background Color' ) ?>" > 
                                                <div class="input-group">
                                                    <div class="input-group-prepend" id="basic-addon1">
                                                        <span class="input-group-text" style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_3_background_color'] ]]">#
                                                        </span>
                                                    </div>
                                                    <input type="text" 
                                                        class="lw-form-field form-control"
                                                        name="landingPageData.[[ landingPageSettingCtrl.banner1Index ]].baner_1_section_3_background_color"
                                                        lw-color-picker
                                                        readonly 
                                                        ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner1Index]['baner_1_section_3_background_color']" />
                                                    </div>
                                            </lw-form-field>
                                            <!-- Selected Theme Color -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card" id="bannerContent2" role="tabpanel" aria-labelledby="list-home-list">
                        <div class="card-body">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" 
                                    class="custom-control-input" 
                                    id="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].title"
                                    name="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].title"
                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['isEnable']">
                                <label class="custom-control-label" for="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].title"><?= __tr('Enable 2 Box Banner') ?></label>
                            </div><hr>
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="cart-title">
                                        <h5><?= __tr('Left Side Box') ?></h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <img ng-if="!landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_image_thumb']" src="<?= url('resources/assets/imgs/default-thumbnail.jpg') ?>" class="img-thumbnail" alt="Cinque Terre">

                                            <img ng-if="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_image_thumb']" ng-src="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_image_thumb'] ]]" class="img-thumbnail" alt="Cinque Terre">

                                            <div class="text-center mt-3">
                                                <span class="btn btn-primary btn-sm lw-btn-file"><i class="fa fa-upload"></i> <?=   __tr('Browse Images')   ?>
                                                    <input type="file" nv-file-select="" ng-click="landingPageSettingCtrl.addImages([[ landingPageSettingCtrl.banner2Index ]], 'banner_2_section_1_image_thumb', 'banner_2_section_1_image')" uploader="landingPageSettingCtrl.uploader"/>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="form-row">
                                                <div class="col">
                                                <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].banner_2_section_1_heading_1" label="<?= __tr( 'Heading 1' ) ?>"> 
                                                    <div class="input-group">
                                                    <input type="text" 
                                                        class="lw-form-field form-control"
                                                        ng-maxlength="150"
                                                        name="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].banner_2_section_1_heading_1"
                                                        placeholder="Heading 1" 
                                                        ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_heading_1']" />
                                                        <div class="input-group-append">

                                                        	<button type="button" class="btn btn-secondary" lw-transliterate
																entity-type="landing_page_settings" entity-id="null" 
																entity-key="banner_2_section_1_heading_1" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_heading_1'] ]]" input-type="1">
															    <i class="fa fa-globe"></i></button>

                                                            <button type="btton" 
                                                                class="btn btn-light border"
                                                                title="<?= __tr('Heading 1 Color') ?>"
                                                                style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_heading_1_color'] ]]"
                                                                name="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].banner_2_section_1_heading_1_color"
                                                                ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_heading_1_color']" 
                                                                lw-color-picker>#
                                                            </button>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                </div>
                                                <div class="col">
                                                <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].banner_2_section_1_heading_2" label="<?= __tr( 'Heading 2' ) ?>"> 
                                                    <div class="input-group">
                                                        <input type="text" 
                                                            class="lw-form-field form-control"
                                                            ng-maxlength="150"
                                                            name="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].banner_2_section_1_heading_2"
                                                            placeholder="Heading 2" 
                                                            ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_heading_2']" />
                                                        <div class="input-group-append">

                                                        	<button type="button" class="btn btn-secondary" lw-transliterate
																entity-type="landing_page_settings" entity-id="null" 
																entity-key="banner_2_section_1_heading_2" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_heading_2'] ]]" input-type="1">
															    <i class="fa fa-globe"></i></button>

                                                            <button type="btton" 
                                                                class="btn btn-light border"
                                                                title="<?= __tr('Heading 1 Color') ?>"
                                                                style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_heading_2_color'] ]]"
                                                                name="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].banner_2_section_1_heading_2_color"
                                                                ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_heading_2_color']" 
                                                                lw-color-picker>#
                                                            </button>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                </div>
                                            </div>
                                            <lw-form-field field-for="slides.[[ landingPageSettingCtrl.banner2Index ]].banner_2_section_1_description" label="<?= __tr( 'Description' ) ?>">

                                            	<a href type="btton" lw-transliterate class="float-right" 
													entity-type="landing_page_settings" entity-id="null" 
													entity-key="banner_2_section_1_description" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_description'] ]]" input-type="1">
													<i class="fa fa-globe"></i></a>

                                                <textarea class="lw-form-field form-control"
                                                    name="slides.[[ landingPageSettingCtrl.banner2Index ]].banner_2_section_1_description"
                                                    ng-maxlength="1000"
                                                    placeholder="Description"
                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_description']" ></textarea>
                                            </lw-form-field>
                                            <!-- Selected Theme Color -->
                                            <lw-form-field field-for="banner_2_section_1_background_color" label="<?= __tr( 'Background Color' ) ?>" > 
                                                <div class="input-group">
                                                    <div class="input-group-prepend" id="basic-addon1">
                                                        <span class="input-group-text" style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_background_color'] ]]">#
                                                        </span>
                                                    </div>
                                                    <input type="text" 
                                                        class="lw-form-field form-control"
                                                        name="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].banner_2_section_1_background_color"
                                                        lw-color-picker
                                                        readonly 
                                                        ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_1_background_color']" />
                                                    </div>
                                            </lw-form-field>
                                            <!-- Selected Theme Color -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="cart-title">
                                        <h5><?= __tr('Right Side Box') ?></h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <img ng-if="!landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_2_image_thumb']" src="<?= url('resources/assets/imgs/default-thumbnail.jpg') ?>" class="img-thumbnail" alt="Cinque Terre">

                                            <img ng-if="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_2_image_thumb']" ng-src="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['banner_2_section_2_image_thumb'] ]]" class="img-thumbnail" alt="Cinque Terre">

                                            <div class="text-center mt-3">
                                                <span class="btn btn-primary btn-sm lw-btn-file"><i class="fa fa-upload"></i> <?=   __tr('Browse Images')   ?>
                                                    <input type="file" nv-file-select="" ng-click="landingPageSettingCtrl.addImages([[ landingPageSettingCtrl.banner2Index ]], 'banner_2_section_2_image_thumb', 'banner_2_section_2_image')" uploader="landingPageSettingCtrl.uploader"/>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="form-row">
                                                <div class="col">
                                                <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].baner_2_section_2_heading_1" label="<?= __tr( 'Heading 1' ) ?>"> 
                                                    <div class="input-group">
                                                        <input type="text" 
                                                            class="lw-form-field form-control"
                                                            ng-maxlength="150"
                                                            name="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].baner_2_section_2_heading_1"
                                                            placeholder="Heading 1" 
                                                            ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['baner_2_section_2_heading_1']" />
                                                        <div class="input-group-append">

                                                        	<button type="button" class="btn btn-secondary" lw-transliterate
																entity-type="landing_page_settings" entity-id="null" 
																entity-key="baner_2_section_2_heading_1" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['baner_2_section_2_heading_1'] ]]" input-type="1">
															    <i class="fa fa-globe"></i></button>

                                                            <button type="btton" 
                                                                class="btn btn-light border"
                                                                title="<?= __tr('Heading 1 Color') ?>"
                                                                style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['baner_2_section_2_heading_1_color'] ]]"
                                                                name="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].baner_2_section_2_heading_1_color"
                                                                ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['baner_2_section_2_heading_1_color']" 
                                                                lw-color-picker>#
                                                            </button>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                </div>
                                                <div class="col">
                                                <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].baner_2_section_2_heading_2" label="<?= __tr( 'Heading 2' ) ?>"> 
                                                    <div class="input-group">
                                                    <input type="text" 
                                                        class="lw-form-field form-control"
                                                        ng-maxlength="150"
                                                        name="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].baner_2_section_2_heading_2"
                                                        placeholder="Heading 2" 
                                                        ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['baner_2_section_2_heading_2']" />
                                                        <div class="input-group-append">

                                                        	<button type="button" class="btn btn-secondary" lw-transliterate
																entity-type="landing_page_settings" entity-id="null" 
																entity-key="baner_2_section_2_heading_2" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['baner_2_section_2_heading_2'] ]]" input-type="1">
															    <i class="fa fa-globe"></i></button>

                                                            <button type="btton" 
                                                                class="btn btn-light border"
                                                                title="<?= __tr('Heading 1 Color') ?>"
                                                                style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['baner_2_section_2_heading_2_color'] ]]"
                                                                name="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].baner_2_section_2_heading_2_color"
                                                                ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['baner_2_section_2_heading_2_color']" 
                                                                lw-color-picker>#
                                                            </button>
                                                        </div>
                                                    </div>
                                                </lw-form-field>
                                                </div>
                                            </div>
                                            <lw-form-field field-for="slides.[[ landingPageSettingCtrl.banner2Index ]].baner_2_section_2_description" label="<?= __tr( 'Description' ) ?>">

                                            	<a href type="btton" lw-transliterate class="float-right" 
													entity-type="landing_page_settings" entity-id="null" 
													entity-key="baner_2_section_2_description" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['baner_2_section_2_description'] ]]" input-type="1">
													<i class="fa fa-globe"></i></a>

                                                <textarea class="lw-form-field form-control"
                                                    name="slides.[[ landingPageSettingCtrl.banner2Index ]].baner_2_section_2_description"
                                                    ng-maxlength="1000"
                                                    placeholder="Description"
                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['baner_2_section_2_description']" ></textarea>
                                            </lw-form-field>
                                            <!-- Selected Theme Color -->
                                            <lw-form-field field-for="baner_2_section_2_background_color" label="<?= __tr( 'Background Color' ) ?>" > 
                                                <div class="input-group">
                                                    <div class="input-group-prepend" id="basic-addon1">
                                                        <span class="input-group-text" style="background: #[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['baner_2_section_2_background_color'] ]]">#
                                                        </span>
                                                    </div>
                                                    <input type="text" 
                                                        class="lw-form-field form-control"
                                                        name="landingPageData.[[ landingPageSettingCtrl.banner2Index ]].baner_2_section_2_background_color"
                                                        lw-color-picker
                                                        readonly 
                                                        ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.banner2Index]['baner_2_section_2_background_color']" />
                                                    </div>
                                            </lw-form-field>
                                            <!-- Selected Theme Color -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card" id="productTabContent" role="tabpanel" aria-labelledby="list-home-list">
                        <div class="card-body">
                            <div class="custom-control custom-checkbox mr-sm-2">
                                <input type="checkbox" 
                                    class="custom-control-input" 
                                    id="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].title"
                                    name="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].title"
                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['isEnable']">
                                <label class="custom-control-label" for="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].title"><?= __tr('Enable Product Tab') ?></label>
                            </div><hr>
                            
                            <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_section_title" label="<?= __tr( 'Tab Heading' ) ?>"> 
                                
                                <div class="input-group">
	                                <input type="text" 
	                                    class="lw-form-field form-control"
	                                    ng-maxlength="150"
	                                    name="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_section_title"
	                                    placeholder="<?= __tr('Tab Heading') ?>" 
	                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_section_title']" />

                                    <div class="input-group-append">
                                    	<button type="button" class="btn btn-secondary" lw-transliterate
											entity-type="landing_page_settings" entity-id="null" 
											entity-key="tab_section_title" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_section_title'] ]]" input-type="1">
										    <i class="fa fa-globe"></i></button>
                                    </div>
                                </div>
                            </lw-form-field>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= __tr('Product Tab 1') ?></h5>

                                            
                                            <!-- Tab 1 Title -->
                                            <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_1_title" label="<?= __tr( 'Tab 1 Title' ) ?>">
                                            	<div class="input-group">
	                                                <input type="text" 
	                                                    class="lw-form-field form-control"
	                                                    ng-maxlength="150"
	                                                    name="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_1_title"
	                                                    placeholder="<?= __tr('Tab 1 Title') ?>"
	                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_1_title']" />

                                                    <div class="input-group-append">
			                                    		<button type="button" class="btn btn-secondary" lw-transliterate
															entity-type="landing_page_settings" entity-id="null" 
															entity-key="tab_1_title" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_1_title'] ]]" input-type="1">
														    <i class="fa fa-globe"></i></button>
                                    				</div>
                                            	</div>
                                            </lw-form-field>
                                            <!-- /Tab 1 Title -->

                                            <!-- Products -->
                                            <lw-form-selectize-field field-for="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_1_products" label="<?= __tr( 'Select Products' ) ?>" class="lw-selectize">
                                                <selectize 
                                                    config='landingPageSettingCtrl.productSelectConfig' 
                                                    class="lw-form-field" 
                                                    name="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_1_products" 
                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_1_products']" 
                                                    options='landingPageSettingCtrl.productList' 
                                                    placeholder="<?= __tr( 'Select Products' ) ?>">
                                                </selectize>
                                            </lw-form-selectize-field>
                                            <!-- /Products -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= __tr('Product Tab 2') ?></h5>
                                            <!-- Tab 2 Title -->
                                            <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_2_title" label="<?= __tr( 'Tab 2 Title' ) ?>"> 

                                            	<div class="input-group">
	                                                <input type="text" 
	                                                    class="lw-form-field form-control"
	                                                    ng-maxlength="150"
	                                                    name="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_2_title"
	                                                    placeholder="<?= __tr('Tab 2 Title') ?>"
	                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_2_title']" />

                                                    <div class="input-group-append">
			                                    		<button type="button" class="btn btn-secondary" lw-transliterate
															entity-type="landing_page_settings" entity-id="null" 
															entity-key="tab_2_title" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_2_title'] ]]" input-type="1">
														    <i class="fa fa-globe"></i></button>
                                    				</div>
                                            	</div>
                                            </lw-form-field>
                                            <!-- /Tab 2 Title -->

                                            <!-- Products -->
                                            <lw-form-selectize-field field-for="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_2_products" label="<?= __tr( 'Select Products' ) ?>" class="lw-selectize">
                                                <selectize 
                                                    config='landingPageSettingCtrl.productSelectConfig' 
                                                    class="lw-form-field" 
                                                    name="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_2_products" 
                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_2_products']" 
                                                    options='landingPageSettingCtrl.productList' 
                                                    placeholder="<?= __tr( 'Select Products' ) ?>">
                                                </selectize>
                                            </lw-form-selectize-field>
                                            <!-- /Products -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card mt-2">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= __tr('Product Tab 3') ?></h5>
                                            <!-- Tab 3 Title -->
                                            <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_3_title" label="<?= __tr( 'Tab 3 Title' ) ?>">

                                            	<div class="input-group">
	                                                <input type="text" 
	                                                    class="lw-form-field form-control"
	                                                    ng-maxlength="150"
	                                                    name="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_3_title"
	                                                    placeholder="<?= __tr('Tab 3 Title') ?>"
	                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_3_title']" />
                                                    
                                                    <div class="input-group-append">
			                                    		<button type="button" class="btn btn-secondary" lw-transliterate
															entity-type="landing_page_settings" entity-id="null" 
															entity-key="tab_3_title" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_3_title'] ]]" input-type="1">
														    <i class="fa fa-globe"></i></button>
                                    				</div>
                                            	</div>
                                            </lw-form-field>
                                            <!-- /Tab 3 Title -->

                                            <!-- Products -->
                                            <lw-form-selectize-field field-for="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_3_products" label="<?= __tr( 'Select Products' ) ?>" class="lw-selectize">
                                                <selectize 
                                                    config='landingPageSettingCtrl.productSelectConfig' 
                                                    class="lw-form-field" 
                                                    name="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_3_products" 
                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_3_products']" 
                                                    options='landingPageSettingCtrl.productList' 
                                                    placeholder="<?= __tr( 'Select Products' ) ?>">
                                                </selectize>
                                            </lw-form-selectize-field>
                                            <!-- /Products -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card mt-2">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= __tr('Product Tab 4') ?></h5>
                                            <!-- Tab 4 Title -->
                                            <lw-form-field field-for="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_4_title" label="<?= __tr( 'Tab 4 Title' ) ?>"> 
                                                
                                                <div class="input-group">
	                                            	<input type="text" 
	                                                    class="lw-form-field form-control"
	                                                    ng-maxlength="150"
	                                                    name="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_4_title"
	                                                    placeholder="<?= __tr('Tab 4 Title') ?>"
	                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_4_title']" />
                                                    <div class="input-group-append">
			                                    		<button type="button" class="btn btn-secondary" lw-transliterate
															entity-type="landing_page_settings" entity-id="null" 
															entity-key="tab_4_title" entity-string="[[ landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_4_title'] ]]" input-type="1">
														    <i class="fa fa-globe"></i></button>
                                    				</div>
                                            	</div>
                                            </lw-form-field>
                                            <!-- /Tab 4 Title -->

                                            <!-- Products -->
                                            <lw-form-selectize-field field-for="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_4_products" label="<?= __tr( 'Select Products' ) ?>" class="lw-selectize">
                                                <selectize 
                                                    config='landingPageSettingCtrl.productSelectConfig' 
                                                    class="lw-form-field" 
                                                    name="landingPageData.[[ landingPageSettingCtrl.productTabIndex ]].tab_4_products" 
                                                    ng-model="landingPageSettingCtrl.editData.landingPageData[landingPageSettingCtrl.productTabIndex]['tab_4_products']" 
                                                    options='landingPageSettingCtrl.productList' 
                                                    placeholder="<?= __tr( 'Select Products' ) ?>">
                                                </selectize>
                                            </lw-form-selectize-field>
                                            <!-- /Products -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" ng-click="landingPageSettingCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
                <?= __tr('Update') ?><span></span> 
            </button>

            <a class="lw-btn btn btn-light" href="<?= route('landing_page') ?>" 
                        target="_new" title="<?= __tr('Preview') ?>"><?= __tr('Preview') ?> <i class="fa fa-external-link"></i></a>
        </div>
    </form>
    <!-- /form action -->
</div>