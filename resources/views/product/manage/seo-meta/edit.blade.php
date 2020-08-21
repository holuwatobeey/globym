<?php
/*
*  Component  : ManageItem
*  View       : Faqs Add  
*  Engine     : ManageItemEngine  
*  File       : add-dialog.blade.php  
*  Controller : ProductSeoMetaController as ProductSeoMetaCtrl
----------------------------------------------------------------------------- */ 
?>
<div ng-controller="ProductSeoMetaController as ProductSeoMetaCtrl">
    <!-- main heading -->
     <div class="lw-section-heading-block">
        <h3 class="lw-section-heading"> <?= __tr("SEO Meta") ?> </h3>
    </div>
    <!-- /main heading -->

    <!-- form action -->
     <form class="lw-form lw-ng-form" 
        name="ProductSeoMetaCtrl.[[ ProductSeoMetaCtrl.ngFormName ]]"
        ng-submit="ProductSeoMetaCtrl.submit()"
        novalidate>
		<!-- keywords -->
		<lw-form-field field-for="keywords" label="<?= __tr( 'Keywords' ) ?>">
			<input type="text" 
			    class="lw-form-field form-control"
			    name="keywords"
			    ng-model="ProductSeoMetaCtrl.seoMeta.keywords" />
		</lw-form-field>
		<!-- /keywords -->

		<!-- description -->
		<lw-form-field field-for="description" label="<?= __tr( 'Description') ?>"> 
			<textarea 
			    name="description" 
			    class="lw-form-field form-control"
			    ng-model="ProductSeoMetaCtrl.seoMeta.description">
			</textarea>
		</lw-form-field>
		<!-- /description -->

        <div class="modal-footer">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __tr('Update') ?>"><?= __tr('Update') ?> <span></span></button>
        </div>
        <!-- /button -->
    </form>
    <!-- /form action -->
</div>