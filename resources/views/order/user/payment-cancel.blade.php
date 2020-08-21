<div class="col-lg-12 text-center lw-order-success-section">
@if($orderUid)
        <h3>
            <?= __tr("Failed order Submission") ?>
        </h3>        
            <i class="fa fa-exclamation-triangle fa-5x lw-warn-color"></i>
        <h5>
            <?= __tr( 'Order has been cancelled due to Payment Failed ' ) ?> <a href="<?= route('my_order.details', $orderUid) ?>"><?= $orderUid ?></a>
        </h5>
        @if(isset($message) and $message)
            <h6><?= $message  ?></h6>
        @endif
        
@else


</h3>        
    <i class="fa fa-exclamation-triangle fa-5x lw-warn-color"></i>
<h5>
<div class="alert">
    <strong><?= __tr( 'Invalid Request!! ' ) ?></strong>
</div>
@endif
</div>