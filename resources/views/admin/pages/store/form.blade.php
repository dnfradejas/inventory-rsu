@extends('admin.layout.main')

@section('content')
<div class="row row__center">
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
                        @if($store->id)
                        <input type="hidden" name="id" value="{{$store->id}}">
                        @endif
                        <label for="store_name">Store name <span class="-red">*<span></label>
                        <input type="text" class="form-control" id="store_name" value="{{$store->store_name}}" required="" placeholder="Enter your store here">
                        <div class="error__cont"></div>
                    </div>
                    <div class="form-group">
                        <label for="store_address">Store address <span class="-red">*<span></label>
                        <input type="text" class="form-control" id="store_address" value="{{$store->address}}" required="" placeholder="Enter your store address here">
                    </div>
                    <div class="form-group">
                        <label for="tin">TIN</label>
                        <input type="text" class="form-control" id="tin" value="{{$store->tin}}" placeholder="Enter your store TIN #">
                    </div>
                    <div class="form-group">
                        <label for="telephone">Telephone </label>
                        <input type="text" class="form-control" id="telephone" value="{{$store->telephone}}" placeholder="Enter your store telephone #">
                        
                    </div>
                    <div class="form-group">
                        <label>Status <span class="-red">*<span></label>
                        <div class="form-check">
                            <input type="radio" {{$store->status === 'active' ? 'checked' : ''}} name="status" required="" value="active" class="form-check-input">
                            <label class="form-check-label">Active</label>    
                        </div>

                        <div class="form-check">
                            <input type="radio" {{$store->status === 'inactive' ? 'checked' : ''}} name="status" required="" value="inactive" class="form-check-input">
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
@section('scripts')
<script type="text/javascript">
(function($){
    $('#form').on('submit', function(evt){
        evt.preventDefault();
        
        if($('#form').parsley().isValid()) {
            let form = new FormData();
            form.append('store_name', $('#store_name').val());
            form.append('status', $('input[name=status]:checked').val());
            form.append('address', $('#store_address').val());
            form.append('tin', $('#tin').val());
            form.append('telephone', $('#telephone').val());
            
            if ($('input[name=id]').length) {
                form.append('id', $('input[name=id]').val());
            }
            
            $.ajax({
                url: "{{route('admin.store.post.create')}}",
                method: "POST",
                data: form,
                processData: false,
                contentType: false,
                success: function(response){
                    Swal.fire({
                        // position: 'top-end',
                        icon: 'success',
                        title: response.data.results,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function(){
                        window.location.href = "{{route('admin.store.listing')}}";
                    });
                },
                error: function(jqXHR, jqStatus, jqThrown){
                    
                    let status = jqXHR.status;
                    
                    if (status === 422) {
                        const { errors } = jqXHR.responseJSON;
                        console.log(errors);
                        $('.error__cont').html(`<li class="-red">${errors.store_name[0]}</li>`);
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