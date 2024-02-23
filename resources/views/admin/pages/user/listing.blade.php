@extends('admin.layout.main')

@section('styles')
@include('admin.layout.plugins.css.datatables')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
    <div class="card">
              <div class="card-header">
                <h3 class="card-title">Admin users &nbsp; <a href="{{route('admin.user.display.form')}}"><i class="fas fa-plus"></i>&nbsp;Add new</a></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="table__users" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>Action</th>
                        <th>Fullname</th>
                        <th>Username</th>
                        <th>User Role</th>
                        <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <a href="javascript:void(0);" data-id="{{$user->id}}" class="ax__user-delete"><i class="far fa-times-circle -red"></i></a>
                        </td>
                        <td>
                            <a href="{{route('admin.user.display.edit.form', ['id' => $user->id])}}">{{$user->fullname}}</a>
                        </td>
                        <td>{{$user->username}}</td>
                        <td>
                            <a href="#">
                                {{$user->role}}</td>
                            </a>
                        <td>{{$user->status}}</td>
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

@section('modal')
<div class="modal-html"></div>
@endsection

@section('scripts')
@include('admin.layout.plugins.js.datatables')
<script type="text/javascript">
(function($){
    $('#table__users').DataTable({
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

            // delete product
            $('.ax__user-delete').on('click', function(){
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        $.ajax({
                            url: `/admin/user/${id}`,
                            method: "DELETE",
                            success: function(response){
                                const {data} = response;
                                Swal.fire({
                                    icon: 'success',
                                    title: data.results,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = window.location.href;
                                });
                            },
                            error: function(xhr, status, thrown){
                                let errorMessage = 'Something went wrong!';
                                console.log(xhr);
                                if (xhr.status === 422) {
                                    const { errors } = xhr.responseJSON;
                                    errorMessage = errors.id[0];
                                }
                                if (xhr.status === 400) {
                                    const {results} = xhr.responseJSON.data; 
                                    errorMessage = results;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Ooops...',
                                    text: errorMessage
                                });
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