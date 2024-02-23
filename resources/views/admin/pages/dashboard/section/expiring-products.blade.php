<!--Product Life Monitoring-->
<div class="card" style="height:467px;">
    <div class="card-header" style="background-color:red !important;">
    <h3 class="card-title" style="color:white !important;">
        <i class="fas fa-life-ring mr-1"></i>
        Expiring Products</h3>
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
    @if(count($expiringProducts) > 0)
    <table class="table table-head-fixed text-nowrap">
        <thead>
        <tr>
            <th>Product</th>
            <th>Production Date</th>
            <th>Expire in</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($expiringProducts as $product)
        <tr <?php if(is_yesterday($product->expiration_date)): ?> style="color: red;" <?php endif;?>>
            <td>{{$product->product_name}}</td>
            <td>{{date('F j, Y', strtotime($product->production_date))}}</td>    
            <td>{{date('F j, Y', strtotime($product->expiration_date))}}</td>
            <td>
                @if(is_yesterday($product->expiration_date))
                    <a href="{{route('admin.product.view.detail', ['id' => $product->id])}}">View</a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" data-id="{{$product->id}}" class="btn-delete-expiring-product">Delete</a>
                @else
                -
                @endif
            </td>   
        </tr>
        @endforeach
        </tbody>
    </table>
    @else
    <p>No data available</p>
    @endif
    </div>
</div>