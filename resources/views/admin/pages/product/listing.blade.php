@extends('admin.layout.main')

@section('styles')
@include('admin.layout.plugins.css.datatables')
@endsection
@section('content')
<div class="row">
    <div class="col-12">
    <div class="card">
              <div class="card-header">
                <h3 class="card-title">Available Products &nbsp; <a href="{{route('admin.product.display.form')}}"><i class="fas fa-plus"></i>&nbsp;Add new</a></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="table__sizes" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>Action</th>
                        <th>Product name</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Discount Price</th>
                        <th>Stocks Available</th>
                        <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <a href="javascript:void(0);" data-id="{{$product->id}}" class="ax__product-delete"><i class="far fa-times-circle -red"></i></a>
                        </td>
                        <td>
                            <a href="{{route('admin.product.display.edit.form', ['slug' => $product->slug])}}">{{$product->product_name}}</a>
                        </td>
                        <td>{{$product->brand}}</td>
                        <td>{{$product->category}}</td>
                        <td>{{$product->sku}}</td>
                        <td>PHP {{number_format($product->price, 2)}}</td>
                        <td>PHP {{number_format($product->discount_price, 2)}}</td>
                        <td>{{$product->stock !== null ? $product->stock : '-'}}</td>
                        
                        <td>
                            @if($product->status === 'active')
                                <div class="bg-success color-palette -text-center"><span>{{$product->status}}</span></div>
                            @else
                                <div class="bg-secondary color-palette -text-center"><span>{{$product->status}}</span></div>
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

@section('modal')
<div class="modal-html"></div>
@endsection

@section('scripts')
@include('admin.layout.plugins.js.datatables')
<script type="text/javascript">
(function($){
    $('#table__sizes').DataTable({
        createdRow: function(row, data, dataIndex){
            console.log(data);
        },
        initComplete: function(){

            // delete product
            $('.ax__product-delete').on('click', function(){
                let id = $(this).data('id');
                let form = {
                    id: id
                };

                console.log(form);
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
                            url: "{{route('admin.product.post.delete')}}",
                            method: "DELETE",
                            data: form,
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