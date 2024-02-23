@extends('admin.layout.main')
@section('styles')
<link rel="stylesheet" type="text/css" href="/adminlte/bower_components/chartist/dist/chartist.min.css"/>
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0" style="font-weight: bold;">{{$details[0]->product_name}}</h1>
          </div><!-- /.col -->
          
        </div><!-- /.row -->
          
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-6 connectedSortable">
             <!-- Custom tabs (Charts with tabs)-->
             <div class="card" >
              <div class="card-header" style="background-color:rgb(189, 0, 0);">
                <h3 class="card-title" style="color:white;">
                  <i class="fas fa-chart-area mr-1"></i>
                  {{date('Y')}} Item Sales (PHP{{number_format($lifeTimeSales, 2)}})
                </h3>
                <!-- card tools -->
                <!-- <div class="card-tools " >
                  <button type="button" class="btn btn-primary btn-sm daterange input-group-append"
                   title="Date range" style="background: red !important;">
                    <i class="far fa-calendar-alt"></i>
                  </button>
                </div> -->
                
              </div><!-- /.card-header -->
              <div class="ct-chart ct-perfect-fourth" style="height: 71.5vh; font-size: 20px;"></div>
              
            </div>
                    
          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-6 connectedSortable">
            <div class="row">
         
            <div class="col-lg-6 col-5">
              <!-- small box -->
              <div class="small-box bg-maroon">
                <div class="inner">
                  <h3>{{$currentStock}}</h3>
                  <p>Current Stock</p>
                </div>
                <div class="icon">
                  <i class="ion ion-connection-bars"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-5">
              <!-- small box -->
              <div class="small-box bg-gradient-yellow">
                <div class="inner">
                  <h3>{{$details[0]->sku}}</h3>
                  <p>SKU</p>
                </div>
                <div class="icon">
                  <i class="ion ion-information-circled"></i>
                </div>
              </div>
                            
            </div>
               
          </div>

              <!--Delivery History-->
              <div class="card" style="height:60vh;">
                <div class="card-header" style="background-color:green !important;">
                  <h3 class="card-title" style="color:white !important;">
                    <i class="fas fa-tractor mr-1"></i>
                    Delivery History</h3>
                    <!-- <div class="card-tools">
                      <div class="input-group input-group-sm" style="width: 200px;">
                        <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
      
                        <div class="input-group-append">
                          <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                          </button>
                        </div>
                      </div>
                    </div> -->
                </div>
                
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0" style="height: 200px;">
                  <table class="table table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                        <th>Date of Delivery</th>
                        <th>Production Date</th>
                        <th>Expiration Date</th>

                      </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $product)
                        <tr <?php if(is_yesterday($product->expiration_date)): ?> style="color: red;" <?php endif;?>>
                            <td>{{date('F j, Y', strtotime($product->delivery_date))}}</td>
                            <td>{{date('F j, Y', strtotime($product->production_date))}}</td>
                            <td>{{date('F j, Y', strtotime($product->expiration_date))}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
@endsection
@section('scripts')
<script src="/adminlte/bower_components/chartist/dist/chartist.min.js"></script>
<script>

  let labels = <?php echo json_encode($monthly_names);?>;
  let series = <?php echo json_encode($montly_values);?>;
  new Chartist.Line('.ct-chart', {
  labels: labels,
  series: [series]
}, {
   low: 0,
   showArea: true

});
</script>
@endsection