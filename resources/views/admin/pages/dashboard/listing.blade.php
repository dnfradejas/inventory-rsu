@extends('admin.layout.main')

@section('styles')
<link rel="stylesheet" href="/adminlte/bower_components/chartist/dist/chartist.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{$orders_count}}</h3>
          <p>Orders</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
      </div>
    </div>

    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3>P{{$total_sales}}</h3>

          <p>Total Sales</p>
        </div>
        <div class="icon">
          <i class="ion ion-cash"></i>
        </div>
        <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
      </div>
    </div>
    <!-- ./col -->   
      
</div>
<div class="row">
    @include('admin.pages.dashboard.section.company-sales')
    <section class="col-lg-6 connectedSortable">
      @include('admin.pages.dashboard.section.top-selling')
      @include('admin.pages.dashboard.section.expiring-products')
    </section>
  </div>
@endsection

@section('scripts')
<script src="/adminlte/bower_components/chartist/dist/chartist.min.js"></script>

<script>
(function($){
  let company_sales_name_values = <?php echo json_encode($company_sales_name_values);?>;
  let company_sales_name = <?php echo json_encode($company_sales_name);?>;
  new Chartist.Bar('.ct-chart', {
    labels: company_sales_name,
    series: company_sales_name_values
  }, {
  distributeSeries: true,
  }).on('draw', function(data){
    if(data.type === 'bar') {
      console.log('here');
      data.element.attr({
        style: 'stroke-width: 40px'
      });
    }
  });
  new Chartist.Pie('.ct-chart-pie', {
    labels: ['Tech', 'Cement', 'Paint', 'Materials'],
    series: [20, 10, 30, 40]
  }, {
    donut: true,
    donutWidth: 60,
    donutSolid: true,
    startAngle: 270,
    showLabel: true
  });

  $('.btn-delete-expiring-product').on('click', function(e){
    let id = $(this).data('id');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "{{route('admin.delivery.expired.delete')}}",
          method: "DELETE",
          data: {
            id: id
          },
          success: function(response){
            const {message} = response;
            Swal.fire(
              'Deleted!',
              message,
              'success'
            ).then(() => {
              window.location.href = window.location.href;
            });
          },
          error: function(xhr){
            console.log('error', xhr);
          }
        });
        
      }
    });
    
  });
})(jQuery);

</script>

@endsection