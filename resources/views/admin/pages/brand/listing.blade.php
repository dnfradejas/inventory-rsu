@extends('admin.layout.main')

@section('styles')
@include('admin.layout.plugins.css.datatables')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
    <div class="card">
              <div class="card-header">
                <h3 class="card-title">Available Product Brands &nbsp; <a href="{{route('admin.brand.display.form')}}"><i class="fas fa-plus"></i>&nbsp;Add new</a></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="table__brands" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>Brand</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($brands as $brand)
                    <tr>
                        <td>
                            <a href="{{route('admin.brand.display.edit.form', ['id' => $brand->id])}}">{{$brand->brand}}</a>
                            &nbsp;&nbsp;
                            <a href="javascript:void(0);" class="-red brand__delete" data-id="{{$brand->id}}"><i class="far fa-times-circle"></i></a>
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
    $('#table__brands').DataTable({
      initComplete: function(){
            $('.brand__delete').on('click', function(evt){
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
                            url: "{{route('admin.brand.delete')}}",
                            method: "DELETE",
                            data: {
                                id: id
                            },
                            
                            success: function(){
                                window.location.href = window.location.href;
                            },
                            error: function(xhr, status, thrown){
                                const {data} = xhr.responseJSON;
                                const {results} = data;
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: results
                                });
                                // window.location.href = window.location.href;
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