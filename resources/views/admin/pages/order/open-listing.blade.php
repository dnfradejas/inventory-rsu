@extends('admin.layout.main')

@section('styles')
@include('admin.layout.plugins.css.datatables')
<!-- daterange picker -->
<link rel="stylesheet" href="/adminlte/plugins/daterangepicker/daterangepicker.css">
@endsection
@section('content')
<div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Order history</h3>
        </div>
          <!-- /.card-header -->
          <div class="card-body">
            @include('admin.pages.order.snippet.export', ['action' => route('admin.order.post.export'), 'status' => 'Processing'])
            <table id="table__orders" class="table table-bordered table-striped">
              <thead>
                <tr>
                    <th>Reference #</th>
                    <th>Sold To</th>
                    <th>Grand Total</th>
                    <th>Status</th>
                    <th>Ordered</th>
                    <th>Store</th>
                    <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>
                        <a href="{{route('admin.order.view.detail', ['order_ref' => $order->order_ref])}}">{{$order->order_ref}}</a>
                    </td>
                    <td>{{$order->sold_to}}</td>
                    <td>PHP{{$order->grand_total}}</td>
                    <td>
                        {{$order->status}}
                    </td>
                    <td>
                        {{date("M j, Y, g:i a", strtotime($order->created_at))}}
                    </td>
                    <td>
                      {{$order->store_name}}
                    </td>
                    <td>
                      <div class="btn bg-orange -text-white ax__cancel-order" data-id="{{$order->id}}"> Cancel</div>
                    </td>
                </tr>
                @endforeach
              </tbody>
            </table>
        </div>
        <!-- /.card-body -->
      </div>
    </div>
</div>
@endsection

@section('scripts')
@include('admin.layout.plugins.js.datatables')

@if(session()->has('message'))
<script>
  Swal.fire({
    icon: 'info',
    title: "{{session()->get('message')}}"
  }).then(() => {
    window.location.href = window.location.href;
  });
</script>
@endif
<!-- date-range-picker -->
<script src="/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript">
(function($){
    $('#table__orders').DataTable({
        initComplete: function(){
            $('.ax__cancel-order').on('click', function(){
                let orderId = $(this).data('id');
                
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes'
                }).then((result) => {
                  if (result.isConfirmed) {
                    
                    $.ajax({
                      url: "{{route('admin.order.post.cancel')}}",
                      method: "POST",
                      data: {
                        id: orderId
                      },
                      success: function(response){
                        Swal.fire(
                          'Success',
                          'Order has been cancelled.',
                          'success'
                        ).then(() => {
                          window.location.href = window.location.href;
                        });
                      },
                      error: function(xhr, status, thrown){
                        Swal.fire(
                          'Failed',
                          'Order cannot be cancelled.',
                          'error'
                        );
                      }
                    });

                  }
                });
            });
        }
    });

    $('#filter_date').daterangepicker();
})(jQuery);
</script>
@endsection
