<!--Top Selling Products-->
<div class="card" >
    <div class="card-header"  style="background-color:yellowgreen !important;">
    <h3 class="card-title" style="color:white !important;">
        <i class="fas fa-box mr-1" style="color:white !important;"></i>
        Top Selling Products</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body table-responsive p-0" style="height: 300px;">
    @if(count($top_selling_products) > 0)
    <table class="table table-head-fixed text-nowrap" >
        <thead>
        <tr>
            <th>Product</th>
            <th>SKU</th>
            <th>Qty</th>
            <th>Volume</th>
            <th>Total Sales</th>
            <!-- <th>Action</th> -->
        </tr>
        </thead>
        <tbody>
        @foreach($top_selling_products as $tsp)
        <tr>
            <td>{{$tsp['product_name']}}</td>
            <td>{{$tsp['sku']}}</td>
            <td>{{$tsp['quantity']}}</td>
            <td>{{$tsp['volume']}}</td>
            <td>P {{number_format((($tsp['price'] * $tsp['quantity']) * $tsp['volume']), 2)}}</td>                       
            <!-- <td><span>
                <a href="" class="btn btn-block btn-success btn-xs">View Product</a>
            </span></td> -->
        </tr>
        @endforeach
        
        </tbody>
    </table>
    @else
    <p>No data available</p>
    @endif
    </div>
    <!-- /.card-body -->
</div>