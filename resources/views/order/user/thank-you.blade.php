<div class="col-lg-12 text-center lw-order-success-section">
        <h3>
            <?= __tr("Thanks for your order") ?>
        </h3>
         @if($payment_status == 'Completed')
		  <i class="fa fa-check-square-o fa-5x lw-success"></i>
          <h5>
              <?= __tr( 'We will notify you when your order get processed') ?>
          </h5>
        @elseif($payment_status == 'Pending')
            <i class="fa fa-exclamation-triangle fa-5x lw-warn-color"></i>
            <h5>
                <?= __tr( 'We have received your order but unfortunately Payment is not showing as completed, further investigation may required. <br> We will get back to you soon, if needed please feel free to contact us.' ) ?>
            </h5> 
        @endif

        <h4>
            <?= __tr( 'For the further referance please quote Order ID ') ?>
            <a href="<?= route('my_order.details', $invoice) ?>"><?= $invoice ?></a>
        </h4>
</div>
