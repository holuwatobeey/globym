<div class="lw-dialog col-lg-12">

	<div class="lw-section-heading-block">
        <h3 class="lw-header">
        @section('page-title') 
			<?=  __tr( 'Privacy Policy' )  ?>
		@endsection
		<?=  __tr( 'Privacy Policy' )  ?></h3>
    </div>

	<?= __transliterate('privacy_policy_setting', null, 'privacy_policy', getStoreSettings('privacy_policy') ); ?>
</div>
