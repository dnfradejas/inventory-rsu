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