@extends('cashier.layout.main')
@section('styles')
@include('admin.layout.plugins.css.datatables')
@endsection
@section('content')
<div class="row">
                    <div class="productlist-col col col-md-9">
                        <div class="main_content--productlist">
                            <div class="main_content--productlist-header row">
                                <div class="col col-xs-8 col-sm-9 main_content--productlist-header-store">
                                    <h3>{{$store->store_name}}</h3>
                                    <p class="font-small">{{$store->address}}</p>
                                </div>
                                <div class="col col-xs-4 col-sm-3 main_content--productlist-header-date">
                                    <h3>Today</h3>
                                    <p class="font-small">{{date("D M j, Y");}}</p>
                                </div>
                            </div>

                            <table class="table table-responsive">
                                <thead>
                                    <th>Action</th>
                                    <th>Product Name</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>SKU</th>
                                    <th>Code</th>
                                    <th>Inventory</th>
                                    <th>Price</th>
                                    <th>Discount Price</th>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0);" data-id="{{$product->id}}" @if($product->size) data-size="{{$product->size_id}}" @endif @if($product->color) data-color="{{$product->color_id}}" @endif class="btn__order" data-toggle="modal" data-target="#modal-lg">
                                                <img src="/assets/icon/plus.png"/>
                                            </a>
                                        </td>
                                        <td>{{$product->product_name}}</td>
                                        <td>{{$product->brand}}</td>
                                        <td>{{$product->category}}</td>
                                        <td>{{$product->sku}}</td>
                                        <td>{{$product->code}}</td>
                                        <td>{{$product->inventory_count}}</td>
                                        <td>&#8369; {{$product->price}}</td>
                                        <td>&#8369; {{$product->size_discount_price ?? $product->discount_price}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>    
                    </div>

                    <div class="orderlist-col col col-md-3">
                        <div class="main_content--orderlist">
                            <div class="main_content--orderlist-table-cont">
                                <h3>Order List</h3>
                                <table class="table table-responsive">
                                    <thead>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        <tr>
                                            <td>{{$order->product_name}}</td>
                                            <td>{{$order->quantity}}</td>
                                            <td>&#8369;{{$order->price}}</td>
                                            <td><a href="javascript:void(0);" data-id="{{$order->id}}" class="-red remove__order-action">x</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="main_content--orderlist-subtotal row">
                                            
                                <div class="main_content--orderlist-subtotal-label col col-xs-8 col-md-7">Subtotal</div>
                                <div class="main_content--orderlist-subtotal-value col col-xs-3 col-md-3">&#8369;{{$grandTotal}}</div>
                            </div>
            
                            <div class="main_content--orderlist-total row">
                                <label class="main_content--orderlist-total-label col col-xs-8 col-md-7">Total</label>
                                <label class="main_content--orderlist-total-value col col-xs-3 col-md-3">&#8369;{{$grandTotal}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pos-button-wrapper">
                    <div class="pos-button-row row">
                        <div class="pos-button-scan pos-button col col-xs-6 col col-sm-6">
                            <a href="javascript:void(0);">Scan Product Barcode</a>
                        </div>
                        <div class="pos-button-ordernow pos-button col col-xs-6 col col-sm-6">
                            <a href="javascript:void(0);">Order Now</a>
                        </div>
                    </div>
                </div>
@endsection
@section('scripts')
@include('admin.layout.plugins.js.datatables')
<script type="text/javascript">
(function($){
    window.cnf = {
        storeId: "{{$store->id}}",
        orderProductUrl: "{{route('cashier.order.post.add.product')}}",
        orderProductQuantityUrl: "{{route('cashier.post.quantity.modal')}}",
        orderNowUrl: "{{route('cashier.order.post.create')}}",
    };
})(jQuery);
</script>

@endsection
