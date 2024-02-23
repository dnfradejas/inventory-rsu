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
                    @if($brand->id)
                    <input type="hidden" name="id" value="{{$brand->id}}">
                    @endif
                    <label for="brand">Brand <span class="-red">*<span></label>
                    <input type="text" class="form-control" id="brand" value="{{$brand->brand}}" required="" placeholder="Enter product brand here">
                    <div class="error__cont"></div>
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
            form.append('brand', $('#brand').val());
            if ($('input[name=id]').length) {
                form.append('id', $('input[name=id]').val());
            }
            $.ajax({
                url: "{{route('admin.brand.post.create')}}",
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
                        window.location.href = "{{route('admin.brand.listing')}}";
                    });
                },
                error: function(jqXHR, jqStatus, jqThrown){
                    
                    let status = jqXHR.status;
                    
                    if (status === 422) {
                        const { errors } = jqXHR.responseJSON;
                        $('.error__cont').html(`<li class="-red">${errors.brand[0]}</li>`);
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