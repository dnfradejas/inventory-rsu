@extends('admin.layout.main')

@section('styles')
@include('admin.layout.plugins.css.datatables')
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
        @include('admin.pages.order.snippet.export', ['action' => route('admin.order.post.export'), 'status' => 'Paid'])
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
                    @if($order->status === 'Paid')
                    <a class="btn bg-orange -text-white" href="/admin/order/receipt/{{$order->order_ref}}" target="_blank">Print Receipt</a>
                    @elseif($order->status === 'Processing')
                    <a href="{{route('admin.order.view.detail', ['order_ref' => $order->order_ref])}}">Edit</a>
                    @endif
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
<script type="text/javascript">
(function($){
    $('#table__orders').DataTable({
      initComplete: function(){

          $('.print').on('click', function(evt){
              evt.preventDefault();
              let orderRef = $(this).data('ref');
              // route name: admin.order.display.receipt.to.pdf
              window.location.href = `/admin/order/receipt/${orderRef}`;
          });
      }
    });
    $('#filter_date').daterangepicker();
})(jQuery);
</script>
@endsection
