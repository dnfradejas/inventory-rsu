@extends('web.layout.main')

@section('content')

<section class="section-content padding-y bg">
<div class="container">

<!-- ============================ COMPONENT 1 ================================= -->
<div class="card">
	<div class="row no-gutters">
		<aside class="col-md-6">
<article class="gallery-wrap"> 
	<div class="img-big-wrap">
	   <a href="#"><img src="{{url($product['image'])}}"></a>
	</div> <!-- img-big-wrap.// -->
	
</article> <!-- gallery-wrap .end// -->
		</aside>
		<main class="col-md-6 border-left">
<article class="content-body">

<h2 class="title">{{$product['product_name']}}</h2>

<div class="mb-3"> 
	<var class="price h4">{{$product['discount_price'] > 0 ? number_format($product['discount_price'], 2) : number_format($product['price'], 2)}}</var> 
</div> 

<p>Virgil Abloh’s Off-White is a streetwear-inspired collection that continues to break away from the conventions of mainstream fashion. Made in Italy, these black and brown Odsy-1000 low-top sneakers.</p>


<hr>
    @include('web.pages.shop.snippets.product-detail-colors', ['product' => $product])
    @include('web.pages.shop.snippets.product-detail-sizes', ['product' => $product])
	
	<hr>
	<a href="javascript:void(0);" class="btn btn-primary button-add-cart" data-id="{{$product['id']}}"> <span class="text">Add to cart</span> <i class="fas fa-shopping-cart"></i>  </a>
</article> <!-- product-info-aside .// -->
		</main> <!-- col.// -->
	</div> <!-- row.// -->
</div> <!-- card.// -->
<!-- ============================ COMPONENT 1 END .// ================================= -->

<br>

<!-- <div class="row">
			<div class="col-md-9">

	<header class="section-heading">
		<h3>Customer Reviews </h3>  
		
	</header>

	<article class="box mb-3">
		<div class="icontext w-100">
			<img src="./images/avatars/avatar1.jpg" class="img-xs icon rounded-circle">
			<div class="text">
				<span class="date text-muted float-md-right">24.04.2020 </span>  
				<h6 class="mb-1">Mike John </h6>
				
			</div>
		</div>
		<div class="mt-3">
			<p>
				Dummy comment Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip
			</p>	
		</div>
	</article>

	

	</div> 
</div> row.// -->


</div> <!-- container .//  -->
</section>
<!-- ========================= SECTION CONTENT END// ========================= -->
@endsection

@section('scripts')
<script type="text/javascript">
(function($){

	let productSize = null;
	let productColor = null;
	$('.radio-size').on('click', function(evt){
		productSize = $(this).data('size');
	});

	$('.radio-color').on('click', function(evt){
		productColor = $(this).data('color');
	});


	$('.button-add-cart').on('click', function(evt){
		let productId = $(this).data('id');
		$.ajax({
			url: "{{route('web.cart.post.add')}}",
			method: "POST",
			data: {
				product: productId,
				size: productSize,
				color: productColor,
			},
			success: function(response){
				console.log(response);
			},
			error: function(jqXhr, status, thrown){
				console.log(jqXhr);
			}
		});
	});

})(jQuery);
</script>
@endsection