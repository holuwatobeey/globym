<div ng-controller="CssStyleSettingsController as cssStyleCtrl" class="col-lg-9">
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <span>
                <?= __tr( 'CSS Styles' ) ?>
            </span>
        </h3>
        <!-- /main heading -->
    </div>

	<!-- form action -->
	<form class="lw-form lw-ng-form" name="cssStyleCtrl.[[ cssStyleCtrl.ngFormName ]]" 
        ng-submit="cssStyleCtrl.submit()" 
        novalidate>

		<div class="form-group">
            <div class="alert alert-info">
              <?= __tr('<strong>Note : </strong> Please be carefully While adding your own CSS it will affect on site.') ?>
            </div>
        <label for="custom_css"><?= __tr('Write your own CSS Styles') ?></label>
        <textarea name="custom_css" id="custom_css" class="lw-css-style-editor lw-form-field form-control" ng-required="true"
            cols="30" rows="10" ng-model="cssStyleCtrl.editData.custom_css"></textarea>
        </div>  
          
		<!-- button -->
		<div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>">
            <?= __tr('Update') ?> <span></span></button>
        </div>
        <!-- /button -->

	</form>
	<!-- /form action -->
</div>