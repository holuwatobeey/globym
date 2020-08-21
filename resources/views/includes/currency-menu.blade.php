@if(getStoreSettings('display_multi_currency'))
<a href title="<?= __tr('Currency') ?>" role="button" aria-expanded="false">
    <span><?= __tr('__currency__', [ '__currency__' => getSelectedCurrency() ]) ?></span> 
</a>

<ul class="dropdown-menu">
    <?php $availableCurrencies = getStoreSettings('multi_currencies'); if(!in_array(getStoreSettings('currency_value'), $availableCurrencies)) { array_push($availableCurrencies, getStoreSettings('currency_value')); } ?>

    @foreach($availableCurrencies as $availableCurrency)
        <?php if($availableCurrency == getSelectedCurrency()) continue; ?>
        <li>
        	<?php 
	        	if(getStoreSettings('currency_value') == $availableCurrency) {
	        		$currencyTitle = 'Transaction Currency';
	        	} else {
	        		$currencyTitle = 'Display  Currency';
	        	}
        	?>
         
            <a href="<?= route('currency.change', [$availableCurrency]) .'?redirectTo='.base64_encode(clearAllFilter(Request::fullUrl()));  ?>" 
            	class="lw-show-process-action lw-locale-change-action" 
            	title="<?= $currencyTitle ?>">
            	@if(getStoreSettings('currency_value') == $availableCurrency)
            		<strong><?= $availableCurrency ?></strong>
            	@else
            		<?= $availableCurrency ?>
            	@endif
            </a>
        </li>
    @endforeach
</ul>
@endif