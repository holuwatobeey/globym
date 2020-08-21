@section('page-image', getStoreSettings('logo_image_url'))
@section('twitter-card-image', getStoreSettings('logo_image_url'))
@section('page-url', url()->current())

<div>
	<div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
			<span>
	        	<?= __tr( 'Shop by Brands' ) ?>
	        	@section('page-title') 
		        	<?= __tr('Shop by Brands') ?>
		        @endsection
	        </span>
        </h3>
        <!-- /main heading -->
    </div>
    
   <!-- brand logo -->
  	@if(!empty($brands))
  	<div class="lw-products-container">
         <div class="lw-gutter-sizer"></div>
  		@foreach($brands as $brand)
  		 <div class="text-center lw-product-box lw-brand-box">
                    <a class="lw-product-thumbnail lw-show-process-action" href="<?=  route('product.related.by.brand', [$brand['id'], slugIt($brand['name'])])  ?>">
                        <img class="lw-brand-thumb" data-src="<?= e( $brand['logoURL'] ) ?>"/>

                        <img class="lw-brand-item-thumb-loader-image" src="<?= asset('dist/imgs/ajax-loader.gif') ?>" alt="" >
                    </a>
                </div>
    	@endforeach
    	<!--  get brands keywords for metadata  -->
            @section('keywordName')
            	<?= getKeywords($brands) ?>
            @endsection
        <!--  / get brands keywords for metadata  -->
    </div>
    @else
    	<div class="alert alert-info"><?=  __tr('No brands available here.')  ?></div>
    @endif
  <!-- /brand logo -->
	
</div>


<!-- /To show the products list   -->
@push('appScripts')
    <script  type="text/javascript">

    var $brandsMasonryInstance;

        $(document).ready(function(){

            $brandsMasonryInstance = $('.lw-products-container');

            //_.defer(function() {
                $brandsMasonryInstance.masonry({
                    itemSelector    : '.lw-product-box',
                    percentPosition: true,
                    columnWidth: '.lw-product-box',                    
                    gutter:'.lw-gutter-sizer',
                    horizontalOrder: true,
                    visibleStyle: { transform: 'translateY(0)', opacity: 1 },
                    hiddenStyle: { transform: 'translateY(100px)', opacity: 0 },
                });
            //});

                $('.lw-brand-thumb').Lazy({
                    beforeLoad: function(element) {
                        $('.lw-brand-item-thumb-loader-image').hide();
                    },
                    afterLoad: function(element) {
                        $brandsMasonryInstance.masonry('layout');
                    },
                });              
        });
    </script>
@endpush