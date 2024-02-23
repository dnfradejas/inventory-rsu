@extends('admin.layout.main')

@section('content')
<div class="row">
    <div class="col-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">{{$cardTitle}}</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="form" data-parsley-validate="" autocomplete="off">
                <div class="card-body">
                    <div class="form-group">
                        @if($user->id)
                        <input type="hidden" name="id" value="{{$user->id}}">
                        @endif
                        <!-- <div class="error__cont"></div> -->
                    </div>
                    <div class="form-group">
                        <label for="role">Role <span class="-red">*<span></label>
                        <select name="role" id="role" required="" class="form-control">
                            <option value="">--Select role--</option>
                            @foreach($roles as $role)
                                <option {{$user->role_id === $role->id ? 'selected' : ''}} value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fullname">Full name <span class="-red">*<span></label>
                        <input type="text" name="fullname" id="fullname" class="form-control" required="" value="{{$user->fullname}}">
                    </div>
                    <div class="form-group">
                        <label for="username">Username <span class="-red">*<span></label>
                        <input type="text" name="username" id="username" class="form-control" required="" value="{{$user->username}}">
                    </div>
                    <div class="form-group">
                        @if($user->id)
                        <label for="password">Password </label>
                        <input type="password" name="password" id="password" class="form-control">
                        @else
                        <label for="password">Password <span class="-red">*<span></label>
                        <input type="password" name="password" id="password" class="form-control" required="">
                        @endif
                        <div class="error__cont"></div>
                    </div>

                    <div class="form-group">
                        <label>Status <span class="-red">*<span></label>
                        <div class="form-check">
                            <input type="radio" {{$user->status === 'active' ? 'checked' : ''}} name="status" required="" value="active" class="form-check-input">
                            <label class="form-check-label">Active</label>    
                        </div>

                        <div class="form-check">
                            <input type="radio" {{$user->status === 'inactive' ? 'checked' : ''}} name="status" required="" value="inactive" class="form-check-input">
                            <label class="form-check-label">Inactive</label>    
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                <button type="submit" id="btnSubmit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('modal')
<div class="modal-html"></div>
@endsection
@section('scripts')
<script type="text/javascript">

(function($){
    
    $('#form').on('submit', function(evt){
        evt.preventDefault();
        if($('#form').parsley().isValid()) {
            let form = new FormData();
            let idInput = $('input[name=id]');
            form.append('role', $('#role').val());
            form.append('fullname', $('#fullname').val());
            form.append('username', $('#username').val());
            form.append('status', $('input[name=status]:checked').val());
            
            if (idInput.length <= 0) {
                form.append('password', $('#password').val());
            }

            if (idInput.length > 0) {
                form.append('id', idInput.val());
                if ($('#password').val()) {
                    form.append('password', $('#password').val());
                }
            }

            $.ajax({
                url: "{{route('admin.user.post.create')}}",
                method: "POST",
                data: form,
                processData: false,
                contentType: false,
                success: function(response){

                    const {results} = response.data;
                    const {url} = results;
                    if (url) {
                        window.location.href = url;
                    } else {
                        Swal.fire({
                            // position: 'top-end',
                            icon: 'success',
                            title: results,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function(){
                            window.location.href = "{{route('admin.user.listing')}}";
                        });

                    }

                },
                error: function(jqXHR, jqStatus, jqThrown){
                    
                    let status = jqXHR.status;
                    
                    if (status === 422) {
                        const { errors } = jqXHR.responseJSON;
                        $('.error__cont').html(`<li class="-red">${errors.password[0]}</li>`);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        });
                    }
                }
            });
        }
    });


})(jQuery);
</script>
@endsection