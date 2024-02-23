@php
$quantity = $forOrder ? $forOrder->quantity : 0;
$inventory_count = $product->inventory_count;
@endphp
<div class="modal__add-order-minus">
    <span>-</span>
</div>
<div class="modal__add-order-input-qty">
    <input type="text" id="modal__order-qty" value="{{$quantity}}" placeholder="Enter quanity">
</div>
<div class="modal__add-order-plus" data-stocks="{{$inventory_count}}">
    <span>+</span>
</div>

<div class="modal__add-order-pricelabel"><label for="">Price</label></div>
@if($product && $product->size_price)
@php
$total = $product->size_price * $quantity;
@endphp
<div class="modal__add-order-price" data-curprice="{{$product->size_price}}">PHP{{number_format($product->size_price, 2)}}</div>
@else
@php
$total = $product->price * $quantity;
@endphp
<div class="modal__add-order-price" data-curprice="{{$product->price}}">PHP{{number_format($product->price, 2)}}</div>
@endif

<div class="modal__add-order-stocks font-bold">Stocks Available:</div>
<div class="modal__add-order-stocks-value">{{$inventory_count}}</div>
<!-- <div class="modal__add-order-changeprice">
    <a href="javascript:void(0);" class="btn modal__add-order-changeprice-button">Change Price</a>
</div> -->

<div class="modal__add-order-total">
    <span>PHP{{$total}}</span>
</div>

<div class="modal__add-order-button">
    <span>ADD TO ORDER</span>
</div>