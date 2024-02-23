<div class="col">
    <div class="card">
        <div class="card-body box-profile">

            @if($product)
            <h3 class="profile-username text-center">{{$product->product_name}}</h3>
            <p class="text-muted text-center">{{$product->quantity}} stocks</p>
            <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                <b>SKU</b> <a class="float-right" style="color: black;">{{$product->sku}}</a>
                </li>
                <li class="list-group-item">
                <b>Price</b> <a class="float-right" style="color: black;">₱ {{number_format($price, 2)}}</a>
                </li>

            </ul>

            <a href="#" class="btn btn-success btn-block" id="btn-add-product" style="background-color: #2E4051;"><b>Add
                Product</b></a>
            @else
            <h3 class="profile-username text-center">Product not found.</h3>
            @endif
        </div>
        <!-- /.card-body -->
    </div>
</div>