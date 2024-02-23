@extends('admin.layout.main')

@section('styles')
@include('admin.layout.plugins.css.datatables')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
    <div class="card">
              <div class="card-header">
                <h3 class="card-title">Transaction Histories</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="datatable" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>Details</th>
                        <th>On</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($histories as $history)
                    <tr>
                        <td>{{$history->details}}</td>
                        <td>{{date('F j, Y h:i A', strtotime($history->created_at))}}</td>
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
<script type="text/javascript">
(function($){
    $('#datatable').DataTable({});
})(jQuery);
</script>
@endsection