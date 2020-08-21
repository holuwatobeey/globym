<div class="lw-dialog">
	<!--  main heading  -->
	<div class="lw-section-heading-block"> 
		@section('page-title') 
			<?=  __tr( 'Terms & Conditions' )  ?>
		@endsection
	    <h3 class="lw-header"><?=  __tr( 'Terms & Conditions' )  ?></h3>
    </div>
    <!--  /main heading  -->

	<?=  __transliterate('users_setting', null, 'term_condition', getStoreSettings('term_condition') ); ?>
</div>
