
<form id="search-typehead-form" name="form-country_v1" method="GET">
	<div class="typeahead__container">
	    <div class="typeahead__field">
	        <div class="typeahead__query">
	            <input class="lw-product-search-input" id="lw-product-search-input" name="searchProduct[query]" type="search" placeholder="<?= __tr('Search products...') ?>" autocomplete="off" ng-model="search.searchtext">
	        </div>
	        <div class="typeahead__button">
                <button type="submit">
                    <i class="typeahead__search-icon"></i>
                </button>
            </div>
	    </div>
	</div>
</form>