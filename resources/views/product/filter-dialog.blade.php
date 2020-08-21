<div ng-controller="ProductsFilterController as productsFilterCtrl">
	<!--  container  -->
	<div class="">
		<!--  main heading  -->
	  	<div class="lw-section-heading-block">
	        <h3 class="lw-header"><?=  __tr( 'Filter' )  ?></h3>
	    </div>
	  	<!--  /main heading  -->

	  	<!--  tab heading contain price & brand  -->
		<ul class="nav nav-tabs lw-tabs" role="tablist" id="manageFilterTabs">
			<!--  brand tab  -->
		    <li 
		    	ng-class="{ 'active' : productsFilterCtrl.pageType != 'brand' || productsFilterCtrl.brandExistStatus == true }" 
		    	ng-if="productsFilterCtrl.pageType != 'brand'" 
		    	ng-hide="!productsFilterCtrl.brandExistStatus" class="brand nav-item active">
	          	<a href="#brand" ng-click="productsFilterCtrl.tabClick($event)" role="tab" data-toggle="tab" class="nav-link active" aria-controls="brand">
	              	<?=  __tr('Brand')  ?>
	              	<span class="label label-warning" 
	              		  ng-if="productsFilterCtrl.selectedBrandID.length > 0" 
	              		  ng-bind="productsFilterCtrl.selectedBrandID.length"> 
	              	</span>
	          	</a>
	      	</li>
	      	<!-- / brand tab  -->
 
	      	<!--  price tab  -->
		    <li ng-class="{ 'active' : productsFilterCtrl.pageType == 'brand' || productsFilterCtrl.brandExistStatus == false}" class="price nav-item">
		    	<a href="#price" role="tab" data-toggle="tab" class="nav-link" aria-controls="price"><?=  __tr('Price')  ?></a>
	      	</li>
	      	<!-- / price tab  -->

		</ul><br><br>
		<!--  /tab heading contain price & brand   -->

		<div>
			<!--  form action  -->
			<form action="[[productsFilterCtrl.currentUrl]]" novalidate  name="filter"  method="get">

			  	<div class="tab-content">

			  		<input type="hidden" ng-if="productsFilterCtrl.searchTerm" name="search_term" value="[[productsFilterCtrl.searchTerm]]">

					<!--  To show the brand section  -->
				    <div class="tab-pane fade brand in active show" id="brand" ng-class="{ 'active in' : productsFilterCtrl.pageType != 'brand' || productsFilterCtrl.brandExistStatus == true }">

					    <div ng-if="productsFilterCtrl.brandPageType == false">

					    	<!--  Brand select all label  -->
                            <label for="selectedAll">
                            <lw-select-all-checkbox 
                                checkboxes="productsFilterCtrl.brandsData" 
                                all-selected="productsFilterCtrl.allSelectedItems"
                                id="selectedAll"
                                all-clear="productsFilterCtrl.noSelectedItems"
                                selectedrows="productsFilterCtrl.selectedRows"
                                >
                            </lw-select-all-checkbox> 
                            <?= __tr('Select All') ?>
                            </label>
							<!-- / Brand select all label  -->

						    <!--  Brand select all label  -->
							<ul class="list-group lw-select-filters">

								<li class="list-group-item" ng-repeat="brandData in productsFilterCtrl.brandsData track by $index">
                                
									<!--  To show brand name  -->
									<label for="brandname-[[brandData.brandID]]" class="lw-label-font-normal">
                                        <!--  generate checkbox on row  -->
                                        <input id="brandname-[[brandData.brandID]]" type="checkbox" name="sbid[]" 
                                        value="[[brandData.brandID]]" 
                                        class="lw-checkbox-select" 
                                        ng-model="brandData.isSelected" 
                                        ng-click="productsFilterCtrl.select($event, brandData.exist)">
                                        <!--  / generate checkbox on row  -->
										<span class="lw-filter-select-text" ng-bind="brandData.brandName"></span>
									</label>
									<!-- / To show brand name  -->

									<!--  To show product count related to brand  -->
									<span class="label label-warning pull-right" ng-bind="brandData.product_count"></span>
									<!-- / To show ....... brand  -->

								</li> 

							</ul>
							<!-- / Brand select all label  -->
					  	</div>

				    </div>
				    <!-- / To show the brand section   -->

					<!--  To show the price filter slider  -->
				    <div class="tab-pane fade" id="price" 
				    	 ng-class="{ 'active in' : productsFilterCtrl.pageType == 'brand' || productsFilterCtrl.brandExistStatus == false}">

                       
                        <div class="row ml-2 mr-2" ng-show="productsFilterCtrl.showPriceFilter">
                            <div class="col-sm-12" ng-if="productsFilterCtrl.currentUrl" ng-show="productsFilterCtrl.priceStatus == true"  >
                                <rzslider ng-init="productsFilterCtrl.loadView()" 
                                class="lw-price-range-slider" rz-slider-model="productsFilterCtrl.sliderBar.minValue"
                                  rz-slider-high="productsFilterCtrl.sliderBar.maxValue"
                                  rz-slider-options="productsFilterCtrl.sliderBar.options"></rzslider>
                            </div>
                        </div>
                        <br><br>
                        <div class="col-lg-8 offset-lg-2">
                            <div class="form-row">
                            <div class="form-group col-lg-5">
                            <!-- Min Value -->
                            <lw-form-field field-for="name" label=""> 
                                <strong><?=  __tr("Min Value:")  ?></strong> 
                                <input type="number" 
                                  class="lw-form-field form-control "
                                  name="minValue"
                                  ng-change="productsFilterCtrl.changeFilterPrice(productsFilterCtrl.sliderBar.minValue, productsFilterCtrl.sliderBar.maxValue)" min="0" max="[[productsFilterCtrl.sliderBar.maxValue]]" min="[[productsFilterCtrl.sliderBar.maxValue]]" ng-model="productsFilterCtrl.sliderBar.minValue"/>
                            </lw-form-field>
                            <!-- Min Value -->
                            </div>
                        
                            <div class="form-group col-lg-2 light-height text-center pt-lg-4">
                                <?=  __tr("To")  ?>&nbsp;
                            </div>

                            <div class="form-group col-lg-5">
                            <!-- Max Value -->
                            <lw-form-field field-for="name" label=""> 
                                <strong><?=  __tr("Max Value:")  ?></strong> 
                                <input type="number" 
                                  class="lw-form-field form-control "
                                  name="maxValue"
                                  ng-change="productsFilterCtrl.changeFilterPrice(productsFilterCtrl.sliderBar.minValue, productsFilterCtrl.sliderBar.maxValue)" min="[[productsFilterCtrl.sliderBar.minValue]]" ng-model="productsFilterCtrl.sliderBar.maxValue"/>
                            </lw-form-field>
                            <!-- Max Value -->
                            </div>
                        </div>
                        </div>
                        
						<!--  To show the warning msg when the price not availble for filter  -->
						<div ng-show="productsFilterCtrl.priceStatus == false">
							<div class="text-center alert alert-warning"><?=  __tr("No price Filter.")  ?></div>
						</div>
						<!--  / To show ...... filter  -->
				    </div>
				    <!--  / To show the price filter slider  -->
                    <br>

				  	<!--  To show apply & clear filter & close button  -->
				  	<div class="modal-footer">

						<!--  set hidden min & max price  -->
						<input type="hidden"  class="lw-min-price" value="">
						<input type="hidden" class="lw-max-price"  value="">
						<!-- / set hidden min & max price  -->

						<!--  apply filter btn  -->
						<button ng-show="productsFilterCtrl.priceStatus" class="lw-btn btn btn-warning" title="<?=  __tr('Apply')  ?>" type="submit">
							<?=  __tr("Apply")  ?>
						</button>

						<button ng-show="!productsFilterCtrl.priceStatus" ng-disabled="true" class="lw-btn btn btn-warning" title="<?=  __tr('Apply')  ?>">
							<?=  __tr("Apply")  ?>
						</button>
						<!--  / apply filter btn  -->

						<!--  Clear filter btn  -->

						<a ng-show="!productsFilterCtrl.searchTerm" ng-click="productsFilterCtrl.clearFilter()" class="lw-btn btn btn-light" title="<?=  __tr('Clear Filter')  ?>" href="[[productsFilterCtrl.currentUrl]]">
							<?=  __tr("Clear Filter")  ?>
						</a>

						<a ng-show="productsFilterCtrl.searchTerm" ng-click="productsFilterCtrl.clearFilter()" class="lw-btn btn btn-light" title="<?=  __tr('Clear Filter')  ?>" href="[[productsFilterCtrl.currentUrl]]?search_term=[[productsFilterCtrl.searchTerm]]"><?=  __tr("Clear Filter")  ?></a>

						<!--  /Clear filter btn  -->

						<!--  Close filter dialog  -->
						<a ng-click="productsFilterCtrl.clearFilter()" class="btn lw-clearfilter-btn btn-light border lw-btn" title="<?=  __tr('Close')  ?>">
							<?=  __tr("Close")  ?>
						</a>
						<!--  /Close filter dialog  -->

					</div>
					<!-- / To show apply & clear filter & close button  -->
				</div>

			</form>
			<!--  form action  -->
		</div>
	</div>
	<!--  /container  -->
</div>

