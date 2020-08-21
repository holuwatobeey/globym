<div class="lw-dialog col-lg-12">

	<div class="lw-section-heading-block">
        <h3 class="lw-header">@section('page-title') 
		<?=  __tr( 'Terms & Conditions' )  ?>
	@endsection<?=  __tr( 'Terms & Conditions' )  ?></h3>
    </div>
  
	<?=  __transliterate('users_setting', null, 'term_condition', getStoreSettings('term_condition') ); ?>
</div>
