@extends('admin.layout.main')

@section('styles')
@include('admin.layout.plugins.css.datatables')
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
            <h3 class="card-title">Available Stores &nbsp; <a href="{{route('admin.store.display.form')}}"><i class="fas fa-plus"></i>&nbsp;Add new</a></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <div class="section__stores">
                <div class="section_storelist">
                    @foreach($stores as $store)
                        @if($store->status === 'active')
                        <div class="section__stores--store store-active">
                            <a href="javascript:void(0);" class="-red store__delete store-delete--icon" data-id="{{$store->id}}"><i class="far fa-times-circle"></i></a>
                            <div class="section__stores--store-logo" data-slug="{{$store->slug}}">
                                <figure class="section__stores--store-shape small-circle">
                                    <img src="/storage/images/icon/store-logo.png" alt="">
                                </figure>
                            </div>
                            <div class="section__stores--store-name">
                                <h4 class="font-large">{{$store->store_name}}</h4>
                                <p class="-grey-primary">{{$store->address}}</p>
                            </div>
                        </div>
                        @else
                        <div class="section__stores--store store-inactive">
                            <a href="javascript:void(0);" class="-red store__delete store-delete--icon" data-id="{{$store->id}}"><i class="far fa-times-circle"></i></a>
                            <div class="section__stores--store-logo" data-slug="{{$store->slug}}">
                                <figure class="section__stores--store-shape small-circle">
                                    <img src="/storage/images/icon/store-logo.png" alt="">
                                </figure>
                            </div>
                            <div class="section__stores--store-name">
                                <h4 class="font-large">{{$store->store_name}}</h4>
                                <p class="-grey-primary">{{$store->address}}</p>
                            </div>
                        </div>
                        @endif
                    @endforeach

                </div>
            </div>
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
    $('.section__stores--store-logo').on('click', function(){
        let slug = $(this).data('slug');
        window.location.href = `/admin/store/${slug}/edit`;
    });
    
    $('.section_storelist').on('click', '.store__delete', function(evt){
        
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
                    url: "{{route('admin.store.delete')}}",
                    method: "DELETE",
                    data: {
                        id: id
                    },
                    success: function(response){
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

})(jQuery);
</script>
@endsection