@extends('admin.layout.main')

@section('styles')
@include('admin.layout.plugins.css.datatables')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
    <div class="card">
              <div class="card-header">
                <h3 class="card-title">Available Product Sizes &nbsp; <a href="{{route('admin.size.display.form')}}"><i class="fas fa-plus"></i>&nbsp;Add new</a></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="table__sizes" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>Size</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($sizes as $size)
                    <tr>
                        <td>
                            <a href="{{route('admin.size.display.edit.form', ['id' => $size->id])}}">{{$size->size}}</a>
                            &nbsp;&nbsp;
                            <a href="javascript:void(0);" class="-red size__delete" data-id="{{$size->id}}"><i class="far fa-times-circle"></i></a>
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
<script type="text/javascript">
(function($){
    $('#table__sizes').DataTable({
        initComplete: function(){
            $('.size__delete').on('click', function(evt){
                evt.preventDefault();
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!' 
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        $.ajax({
                            url: "{{route('admin.size.delete')}}",
                            method: "DELETE",
                            data: {
                                id: id
                            },
                            success: function(){
                                window.location.href = window.location.href;
                            },
                            error: function(){
                                window.location.href = window.location.href;
                            }
                        });
                    }
                });
            });
        }
    });
})(jQuery);
</script>
@endsection