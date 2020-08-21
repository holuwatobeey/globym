<div ng-controller="ManageOrderLogController as orderLogCtrl">

    <!--  main heading  -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __tr( 'Order Log For' )  ?> [[ orderLogCtrl.order._id ]]</h3>
    </div>
    <!--  /main heading  -->
	
	<!--  /Order log Detail  -->   
    <div class="mb-3">
        <ul class="list-group">
		  <li class="list-group-item" ng-repeat="log in orderLogCtrl.orderLog">
				<div class="lw-order-description">
                    [[ log.created_at ]]<br>
                </div>[[ log.description ]]
		  </li>
		</ul>
    </div> 
	<!--  /Order log Detail  -->   

	<!-- <div class="lw-dotted-line"></div> -->

	<!--  action button  -->
    <div class="modal-footer">
        <button type="button" ng-click="orderLogCtrl.close()" class="lw-btn btn btn-light" title="<?= __tr('Close') ?>"><?= __tr('Close') ?></button>
    </div>
	<!--  /action button  -->
</div>