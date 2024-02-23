@extends('admin.layout.main')
@section('styles')
<style>
    .custom__modal--content-body {
        width: 25% !important;
    }
</style>
@endsection
@section('content')
<div class="order__detail">
    <div class="order__detail-header">
        <h3>Order Detail</h3>
        <div class="order__header-button-1">
            <a href="{{route('admin.order.listing')}}">Back to order list</a>
        </div>
        @if(!in_array($orderInfo->status, ['Paid', 'Cancelled']))
        <div class="order__header-button-2" data-id="{{$orderInfo->id}}" id="ax__update-status">
            Update Status
        </div>
        @endif
    </div>

    <div class="order__detail-customer">
        <div class="order__detail-info">
            <div class="order__detail-info-number">
                <strong>Order Number: </strong> <span>{{$orderInfo->order_ref}}</span>
            </div>
            <div class="order__detail-info-date">
                <strong>Order Date: </strong> <span>{{$orderInfo->created_at}}</span>
            </div>
            <div class="order__detail-info-date">
                <strong>Order Status: </strong> <span>{{$orderInfo->status}}</span>
            </div>
        </div>
        <div class="customer__info">
            <div class="bill-to">Bill To: N/A</div>
        </div>
    </div>

    <div class="order__detail-products">
        <div class="order__detail-products-items">
            <?php $grandTotal = 0;?>
            @foreach($products as $product)
            <div class="order__detail-products-items-item">
                <div>
                    <strong>{{$product->product_name}}</strong>
                    <br><small><i>SKU: {{$product->sku}}</i></small>
                </div>
                <div class="order__detail-products-items-item-qty"><i>PHP{{$product->price}} x {{$product->quantity}}</i></div>
                <div class="order__detail-products-items-item-price">PHP{{$product->string_row_total}}</div>
            </div>
            <?php $grandTotal += $product->row_total;?>
            @endforeach
        </div>
        <div class="order__detail-products-total">
            <div class="order__detail-products-total-label">Total</div>
            <div class="order__detail-products-total-value">PHP{{number_format($grandTotal, 2)}}</div>
        </div>
    </div>
</div>
@endsection
@section('modal')
<div class="modal-html"></div>
@endsection
@section('scripts')
<script type="text/javascript">
(function($){
    let id;
    $('#ax__update-status').on('click', function(){
        id = $(this).data('id');
        $.ajax({
            url: `/admin/order/${id}/statuses`,
            method: "GET",
            success: function(response){
                const {html} = response;
                $('.modal-html').html(html);
                $('.custom__modal--content').show();
            }
        });
    });

    $('.modal-html').on('click', '#btn__update-status', function(evt){
        evt.preventDefault();
        
        let status = $('#status').val();
        $('.custom__modal--content').hide();
        $.ajax({
            url: "{{route('admin.order.update.status')}}",
            method: "POST",
            data: {
                id: id,
                status: status
            },
            success: function(response){
                Swal.fire({
                    icon: 'success',
                    title: 'Order status has been updated!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = window.location.href;
                });
            }
        });
    });
})(jQuery);
</script>
@endsection