<div class="lw-dialog" ng-controller="ManageRawDataController as rawDataCtrl">
	
	<!-- main heading -->
	<div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Raw Data' )  ?></h3>
    </div>
    <!-- main heading -->
	
	<div class="panel panel-default table-responsive" ng-if="rawDataCtrl.rawData != null">
		
		<!-- raw data table -->
		<table class="table table-bordered" border="1">
			<tbody>
				<tr ng-repeat="(key, value) in rawDataCtrl.rawData">
					<th>
                        <span ng-bind="key"></span>               
                    </th>
					<td>
                        <span ng-if="rawDataCtrl.isObject(value) == false" ng-bind="value"></span>
                        <table ng-if="rawDataCtrl.isObject(value)" class="table table-bordered">
                            <tbody>
                                <tr ng-repeat="(key, nextValue) in value">
                                    <th>
                                        <span ng-bind="key"></span>
                                    </th>
                                    <td>
                                        <span ng-bind="nextValue"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>            
                    </td>
				</tr>
			</tbody>
		</table>
	</div>

	<!--  show message when no raw data found  -->
    <div class="alert alert-info" ng-if="rawDataCtrl.rawData == null">
        <?= __tr('There are no data found') ?>
    </div>
   	<!--  /show message when no raw data found  -->
	
    <!-- Action -->
    <div class="modal-footer">
        <!-- close dialog -->
	    <button type="button" ng-click="rawDataCtrl.closeDialog()" class="lw-btn btn btn-light" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
	    <!-- close dialog -->
    </div>
</div>