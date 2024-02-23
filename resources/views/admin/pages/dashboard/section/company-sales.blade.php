<!-- Left col -->
<section class="col-lg-6 connectedSortable">
    <!-- Custom tabs (Charts with tabs)-->
    <div class="card" >
    <div class="card-header bg-success">
    <h3 class="card-title" style="color:white;">
        <i class="fas fa-chart-area mr-1"></i>
        Company Sales
    </h3>
    <!-- card tools -->
    <!-- <div class="card-tools " >
        <button type="button" class="btn btn-primary btn-sm daterange input-group-append" title="Date range">
        <i class="far fa-calendar-alt"></i>
        </button>
    </div> -->
    
    </div><!-- /.card-header -->
    <div class="ct-chart ct-perfect-fourth" style="max-height: 300px; font-size: 20px;"></div>
    
</div>

<!--Live Stocks Monitor-->
<div class="card" style="height:467px;">
    <div class="card-header" style="background-color:#D81B60 !important;">
    <h3 class="card-title" style="color:white !important;">
        <i class="fas fa-record-vinyl mr-1"></i>
        Low Stocks Monitoring</h3>
        <div class="card-tools">
        <!-- <div class="input-group input-group-sm" style="width: 200px;">
            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

            <div class="input-group-append">
            <button type="submit" class="btn btn-default">
                <i class="fas fa-search"></i>
            </button>
            </div>
        </div> -->
        </div>
    </div>
    
    <!-- /.card-header -->
    <div class="card-body table-responsive p-0" style="height: 200px;">
    <table class="table table-head-fixed text-nowrap">
        <thead>
        <tr>
            <th>Product</th>
            <th>Stocks</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($lowStocks as $lowStock)
        <tr>
            <td>{{$lowStock->product_name}}</td>
            <td>{{$lowStock->quantity}}</td>           
            <td><span class="text-danger">Low Stock</span></td>    
            <td><span><a href="{{route('admin.product.view.detail', ['id' => $lowStock->product_id])}}" class="btn btn-block btn-success btn-xs">View Product</button></span></td>     
        </tr>
        @endforeach
        
        </tbody>
    </table>
    </div>
</div>


</section>