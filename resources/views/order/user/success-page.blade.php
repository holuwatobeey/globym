<div class="col-lg-12 text-center lw-order-success-section">
		<i class="fa fa-check-square-o fa-5x lw-success"></i>
		<h1> @section('page-title', __tr('Order Success')) <?=  __tr('Success!')  ?></h1>
        <?php $trInfo = [
            "__fullName__" => $success['full_name'],
            "__orderID__" => $success['order_uid']
        ]; ?>
		<h4><?=  __tr('Hi __fullName__', $trInfo)  ?></h4>
		<h4>
			<?= __tr('Your order has been placed successfully!!') ?>
		</h4>
        <h5><?= __tr("Order ID: __orderID__", $trInfo) ?></h5>
		<h4> <a href="<?= route('my_order.details', $success['order_uid'])  ?>"><?=  __tr('Click here to see order details') ?></a>
		</h4>
		@if (Session::has('successMessage')) 
    		<?= Session::forget('successMessage') ?>
    	@endif
</div>