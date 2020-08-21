<div ng-controller="ManagePagesListController as managePagesListCtrl">

    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h4 class="lw-section-heading">
        	<span ng-if="!managePagesListCtrl.parent == true"><?= __tr( 'Manage Pages & Menu' ) ?> </span>
            <span  ng-if="managePagesListCtrl.parent == true">
            	<a ui-sref="pages({parentPageID:''})"><?= __tr( 'Manage Pages & Menu' ) ?> » </a>[[managePagesListCtrl.parentPage.title]] 
            	<a ng-if="canAccess('manage.pages.get.details')"  title="<?= __tr('Edit') ?>" class="btn btn-light btn-sm lw-btn" ui-sref="pages.edit({pageID:managePagesListCtrl.parentPageID})"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></a></span></h4>
        <!-- /main heading -->
        <!-- button -->
        <div class="lw-section-right-content">
            <a ng-if="canAccess('manage.pages.add') && canAccess('manage.pages.fetch.datatable.source')" title="<?=  __tr('Add New Page')  ?>" ui-sref="pages.add" class="lw-btn btn btn-light btn-sm"><i class="fa fa-plus"></i> <?=  __tr('Add New Page')  ?></a>
        </div>
        <!-- /button -->
    </div>
 
    <!-- datatable manage pages -->
    <div class="">
    	<table id="lwPagesTable" class="lw-pages-container table table-bordered" width="100%">
    		<thead class="page-header">
    			<tr>
    				<th ><?= __tr('Title') ?></th>
                    <th><?= __tr('List Order') ?></th>
    				<th><?= __tr('Type') ?></th>
    				<th><?= __tr('Created') ?></th>
    				<th><?= __tr('Updated') ?></th>
    				<th><?= __tr('Show in menu') ?></th>
    				<th><?= __tr('Status') ?></th>
    				<th><?= __tr('Action') ?></th>
    			</tr>
    		</thead>
    		<tbody></tbody>
    	</table>
    </div>
    <!-- /datatable manage pages -->

    <div ui-view></div>
    
</div>

<!-- pages list Order template -->
<script type="text/template" id="pagesColumnListOrderTemplate">

   <i class="fa fa-arrows-v"></i> - <%- __tData.list_order %>
   
</script>
<!-- /pages list Order template -->

<!-- pages list row add to menu column _template -->
<script type="text/template" id="pagesColumnAddToMenuTemplate">

   <span><%-__tData.add_to_menu %></span>
   
</script>
<!-- /pages list row add to menu column _template -->

<!-- pages list row formated type column _template -->
<script type="text/template" id="pagesColumnTypeTemplate">

   <span><%-__tData.formated_type %></span>
   <% if(__tData.type == 3) { %>
   <div>(<%- __tData.page_title.pageName %>)</div>
   <% } %>
   
</script>
<!-- /pages list row formated type column _template -->

<!-- pages list row formatted created at column _template -->
<script type="text/template" id="pagesColumnTimeTemplate">

   <span class="custom-page-in-menu" title="<%-__tData.formatted_created_at %>">
    <%-__tData.formatCreatedData %></span>
   
</script>
<!-- /pages list row formatted created at column _template -->

<!-- pages list row formatted updated at column _template -->
<script type="text/template" id="pagesColumnUpdatedTimeTemplate">
    <span class="custom-page-in-menu" title="<%-__tData.formatted_updated_at %>">
    <%-__tData.formatUpdatedData %></span>
   
</script>
<!-- /pages list row formatted updated at column _template -->

<!-- pages list row active column _template -->
<script type="text/template" id="pagesColumnActiveTemplate">

   <span><%-__tData.active %></span>

</script>
<!-- /pages list row active column _template -->

<!-- pages list row Title column _template -->
<script type="text/template" id="pagesColumnTitleTemplate">

	<% if(__tData.type == 3) { %> 
		<%- __tData.title %>&nbsp&nbsp
	<%} else { %>

		<a ui-sref="pages({parentPageID:'<%- __tData.id %>'})" title="<?= __tr('Sub-pages') ?>" class="custom-page-title"><%- __tData.title %></a> | 

		<% if(__tData.type == 1) { %> 

	   		<a target="_blank" href="<%- __tData.external_page %>" title="<?= __tr('External-page') ?>"><i class="fa fa-external-link"></i></a>

		<%} else { %>

	   		<a href="<%- __tData.link.url %>" target="_blank" title="<?= __tr('External-link') ?>"><i class="fa fa-external-link"></i></a>
	   		
		<% } %>

	<% } %>

</script>
<!-- /pages list row Title column _template -->

<!-- pages list row Action column _template -->
<script type="text/template" id="pagesColumnActionTemplate">
    <% if(__tData.canAccessEdit) { %> 
        <a title="<?= __tr('Edit') ?>" class="btn btn-light btn-sm lw-btn" ui-sref="pages.edit({pageID:<%- __tData.id %>})"><i class="fa fa-pencil-square-o"></i> <?= __tr('Edit') ?></a> 
    <% } %>

    <% if(__tData.canAccessDelete) { %> 
    	<% if(__tData.type !== 3) { %> 
    	
    		<% if(__tData.id !== 1) { %> 
    	   		<a title="<?= __tr('Delete') ?>" class="lw-btn btn btn-danger btn-sm delete-sw" href="" ng-click="managePagesListCtrl.delete('<%- __tData.id %>', '<%- escape(__tData.title) %>')"><i class="fa fa-trash-o"></i> <?= __tr('Delete') ?></a> 
    	   	<% } %>

        <% } %>
    <% } %>
</script>
<!-- /pages list row Action column _template -->
