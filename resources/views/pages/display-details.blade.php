<div>
	@if (!empty($pageDetails))
		
		@if($pageDetails['status'] == 2)
	 	<div class="alert alert-warning" role="alert">
	    	<?=  __tr("The <strong>:__pageName__</strong> page is inactive & will not display in public until you change status to active",[
	    		':__pageName__' => $pageDetails['title']
	    	])  ?>
	    	<a title="<?= __tr('Edit Page') ?>" 
	            href="<?=  ngLink('manage.app','page_edit', [], [':pageID' => $pageDetails['id']])  ?>" 
	            class="btn btn-default btn-xs"> 
	            <?=  __tr("Edit")  ?> 
	            <i class="fa fa-pencil-square-o"></i>
	        </a>
	    </div>
	    @endif	

		@if($pageDetails['id'] !== 1)
	    <div class="lw-section-heading-block">
	        <!-- main heading -->
	        <h3 class="lw-section-heading"><?=  $pageDetails['title']  ?></h3>
	        <!-- /main heading -->
	    </div>
		@endif

	    @section('page-title', $pageDetails['title']) 

	   @section('description', str_limit(strip_tags($pageDetails['description'])), 20) 
	   <div class="lw-image-width">
	   		<?=  $pageDetails['description']  ?>
	   </div>
	@else 
		Your home page content
	@endif	
</div>
