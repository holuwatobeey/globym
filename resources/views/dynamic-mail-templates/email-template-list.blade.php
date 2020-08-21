<?php
/*
*  Component  : Configuration
*  View       : email-template-list.blade.php 
*  Engine     : ConfigurationEngine  
*  File       : dynamic-email-template.email-template-list.blade.php  
*  Controller : EmailTemplateListController as emailTemplateListCtrl 
----------------------------------------------------------------------------- */ 
?>
<div class="lw-dialog">
	<div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
			<span>
	        	<?= __tr( 'Manage Email Templates' ) ?>
	        </span>
        </h3>
	</div>  
	
	<!-- Modal Body -->
    <div class="modal-body row">

		<table class="table table-bordered" ng-if="canAccess('store.settings.get.email-template.data')">
			<thead>
				<tr class="active">
					<th><?= __tr('Template Name') ?></th>
					<th><?= __tr('Action') ?></th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="emailList in emailTemplateListCtrl.emailTemplateData">
					<td>[[ emailList.title ]]</td>
					<td>
						<a ng-if="canAccess('store.settings.get.edit.email-template.data')" href class="lw-btn btn btn-light btn-sm" ui-sref="show_template({emailTemplateId : emailList.id})" title="<?= __tr( 'Edit' ) ?>">
					 		<i class="fa fa-pencil-square-o"></i> <?= __tr( 'Edit' ) ?>
					 	</a>
					</td>
				</tr>
				<tr ng-if="userDcrsDetailCtrl.userDcrsData.length == 0" class="text-center">
                	<td colspan="9"><?= __tr('There are no records to display.') ?></td>
            	</tr>
			</tbody>
			
		</table>
		<div ui-view=""></div>
    </div>
    <!-- /Modal Body -->	
</div>