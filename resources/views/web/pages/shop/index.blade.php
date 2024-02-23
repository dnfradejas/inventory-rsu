@extends('web.layout.main')

@section('content')
<section class="section-intro padding-y-sm">
<div class="container">

<div class="intro-banner-wrap">
	<img src="images/banners/1.jpg" class="img-fluid rounded">
</div>

</div> <!-- container //  -->
</section>
<!-- ========================= SECTION MAIN END// ========================= -->

<!-- ========================= SECTION  ========================= -->
<section class="section-name padding-y-sm">
<div class="container">

<header class="section-heading">
	<!-- <a href="./store.html" class="btn btn-outline-primary float-right">See all</a> -->
	<h3 class="section-title">Popular products</h3>
</header><!-- sect-heading -->

	
<div class="row">
    @foreach($stores->products as $product)
	<div class="col-md-3">
		<div class="card card-product-grid">
			<a href="{{route('web.product.detail', ['slug' => $product->slug])}}" class="img-wrap"> <img src="{{url($product->image)}}"> </a>
			<figcaption class="info-wrap">
				<a href="{{$product->slug}}" class="title">{{$product->product_name}}</a>
				<div class="price mt-1">PHP{{number_format($product->price, 2)}}</div> <!-- price-wrap.// -->
			</figcaption>
		</div>
	</div>
    @endforeach
</div> <!-- row.// -->

</div><!-- container // -->
</section>
@endsection