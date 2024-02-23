
<div class="orderlist-items card" style="height: 75vh; max-height: 75vh; width: auto;">
    <div class="card-header" style="background-color:#2E4051 !important;">
        <h3 class="card-title" style="color:white !important;">
        Order List
        </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive p-0">
        <table class="table table-head-fixed text-nowrap">
        <thead>
            <tr>
            <th>Qty</th>
            <th>Product Name</th>
            <th>Unit Price</th>
            <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td><input data-id="{{$order->id}}" type="text" class="form-control orderlist-input-quantity" inputmode="numeric" value="{{$order->quantity}}"
                    style="width: 50px !important"></td>
                <td><b>{{$order->product_name}}</b><br>₱ {{number_format($order->total, 2)}}</br></td>
                <td>₱ {{number_format($order->final_price, 2)}}</td>
                <td>
                    <a data-id="{{$order->id}}" class="btn btn-danger btn-sm orderlist-remove-btn" href="javascript:void(0);">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<div class="card">
    <button type="button" class="btn btn-block btn-danger btn-lg" id="ordernow-btn"><b>ORDER NOW</b></button>
</div>