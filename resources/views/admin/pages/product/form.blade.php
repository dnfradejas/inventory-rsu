@extends('admin.layout.main')

@section('content')
<div class="row">
    <div class="col-6">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">{{$cardTitle}}</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="form" data-parsley-validate="" autocomplete="off">
                <div class="card-body">
                    <div class="form-group">
                        @if($product->id)
                        <input type="hidden" name="id" value="{{$product->id}}">
                        @endif
                        <!-- <div class="error__cont"></div> -->
                    </div>
                    <div class="form-group">
                        <label for="brand">Brand <span class="-red">*<span></label>
                        <select name="brand" id="brand" required="" class="form-control">
                            <option value="">--Select brand--</option>
                            @foreach($brands as $brand)
                                <option {{$product->brand_id === $brand->id ? 'selected' : ''}} value="{{$brand->id}}">{{$brand->brand}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category">Category <span class="-red">*<span></label>
                        <select name="category" id="category" required="" class="form-control">
                            <option value="">--Select category--</option>
                            @foreach($categories as $category)
                                <option {{$product->category_id === $category->id ? 'selected' : ''}} value="{{$category->id}}">{{$category->category}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="unit_of_measure">Unit of Measure <span class="-red">*<span></label>
                        <select name="unit_of_measure" id="unit_of_measure" required="" class="form-control">
                            <option value="">--Select UOM--</option>
                            @foreach($uoms as $uom)
                                <option {{$product->unit_of_measure_id === $uom->id ? 'selected' : ''}} value="{{$uom->id}}">{{$uom->unit}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="product_name">Product name <span class="-red">*<span></label>
                        <input type="text" name="product_name" id="product_name" class="form-control" required="" value="{{$product->product_name}}">
                    </div>
                    <div class="form-group">
                        <label for="sku">Sku <span class="-red">*<span></label>
                        <input type="text" name="sku" id="sku" class="form-control" required="" value="{{$product->sku}}">
                    </div>
                    <div class="form-group">
                        <label for="price">Price <span class="-red">*<span></label>
                        <input type="number" name="price" id="price" class="form-control" min="0" oninput="this.value = Math.abs(this.value)" required="" value="{{$product->price}}">
                    </div>
                    <div class="form-group">
                        <label for="discount__price">Discount Price</label>
                        <input type="number" name="discount_price" min="0" oninput="this.value = Math.abs(this.value)" id="discount__price" class="form-control" value="{{$product->discount_price}}">
                    </div>

                    <div class="form-group">
                        <label>Status <span class="-red">*<span></label>
                        <div class="form-check">
                            <input type="radio" {{$product->status === 'active' ? 'checked' : ''}} name="status" required="" value="active" class="form-check-input status">
                            <label class="form-check-label">Active</label>    
                        </div>

                        <div class="form-check">
                            <input type="radio" {{$product->status === 'inactive' ? 'checked' : ''}} name="status" required="" value="inactive" class="form-check-input status">
                            <label class="form-check-label">Inactive</label>    
                        </div>
                    </div>

                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Attach Image</h3>
                        </div>
                        @if($product->image)
                            <div class="product__form--image-cont">
                                <div class="product__form--image">
                                    <img src="{{url($product->image)}}" alt="">
                                </div>
                                <div class="product__form--update--img-btn">
                                    <a href="javascript:void(0);" class="btn btn-secondary" id="ax__product--change-img">Change Image</a>
                                </div>
                            </div>
                        @else
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="file" data-parsley-max-file-size="2048" data-parsley-fileextension="jpg,jpeg,png,gif" name="file" id="file">
                                </div>
                            </div>
                        @endif
                        <div class="product__image-file"></div>
                        
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
    // Parsley Custom file validator
    window.Parsley
          .addValidator('fileextension', function (value, requirement) {
            let fileExtension = value.split('.').pop();
            let allowedExtension = requirement.split(',');
            return allowedExtension.includes(fileExtension);
        }, 32)
        .addMessage('en', 'fileextension', 'The extension doesn\'t match the required');


(function($){
    let productSizes = [];
    let productColors = [];
    let existingProductSizes = [];
    let existingProductColors = [];
    let productStores = [];

    let productId = "{{$product->id}}";

    // initSizeModalEvent();
    // initColorModalEvent();
    // initStoreModalEvent();

    // initPrintSizesColorsAndStoresToDom();

    function initPrintSizesColorsAndStoresToDom() {
        if (productId) {

            // Print to dom existing stores of product
            
        }
    }
    

    function initStoreModalEvent() {

        // Click store checkbox in modal
        $('.content').on('click', '.chk__store', function(){
            let storeCheck = $(this);
            let storeInvInput = $(this).closest('.custom-checkbox').find('.store__inventory');
            let storeInvContainer = $(this).closest('.custom-checkbox').find('.store__inventory-cont');
            if (storeCheck.is(':checked')) {
                storeInvContainer.show();
            } else {
                storeInvInput.val(0);
                storeInvContainer.hide();
            }
        });

        // add size from modal
        $('.content').on('click', '#ax-add__store', function(evt){
            productStores = [];
            $('.chk__store').each(function(idx, elem){
                if ($(this).is(':checked')) {
                    let store = $(this);
                    let storeInvInput = parseInt($(this).closest('.custom-checkbox').find('.store__inventory').val());
                    if (storeInvInput > 0) {
                        productStores.push({
                            id: store.data('id'),
                            inventory_count: storeInvInput,
                            label: store.data('name'),
                            address: store.data('address'),
                        });
                    }
                }
            });

            if (productStores.length > 0) {
                printStoresToDom(productStores);
                $('.custom__modal--content').hide();
            }
        });
    
        function printStoresToDom(productStores){
            let ul = '<ul>';
            for(let i = 0; i < productStores.length; i++){
                ul += `<li>${productStores[i].label} <a href="javascript:void(0);" data-id=${productStores[i].id} class="ax__selected--store"><i class="far fa-times-circle -red"></i> - <small>${productStores[i].address}</small></a></li>`;
            }
            ul += '</ul>';
            $('.product-stores-cont').html(ul);
        }
    
    
        $('.product-stores-cont').on('click', '.ax__selected--store', function(evt){
            evt.preventDefault();
            let id = $(this).data('id');
            productStores = productStores.filter(item => item.id != id);
            console.log(productStores);
            $(this).closest('li').remove();
        });
    }

    function initSizeModalEvent() {
        
        
        // add size from modal
        $('.content').on('click', '#ax-add__size', function(evt){

            let sizeId = parseInt($('#msize').val());
            let sizeLabel = $('#msize option:selected').text();
            let ivtCount = parseInt($('#minventory_count').val());
            let price = $('#mprice').val();
            let discountPrice = $('#mdiscount_price').val();

            if (ivtCount > 0 && sizeId > 0) {
                let sizeDataObj = {
                    id: sizeId,
                    label: sizeLabel,
                    inventory_count: ivtCount,
                    price: price,
                    discount_price: discountPrice,
                };
    
                // find and update existing size
                if (productSizes.length > 0) {
                    productSizes = productSizes.filter(({id}) => id !== sizeId);
                    productSizes.push(sizeDataObj);
                } else {
                    productSizes.push(sizeDataObj);
                }
    
                console.log(productSizes);
                
                printSizesToDom(productSizes);
        
                $('.custom__modal--content').hide();

            }

        });
    
    
        function printSizesToDom(productSizes){
            let ul = '<ul>';
            for(let i = 0; i < productSizes.length; i++){
                ul += `<li>${productSizes[i].label} <a href="javascript:void(0);" data-id=${productSizes[i].id} class="ax__selected--size"><i class="far fa-times-circle -red"></i></a></li>`;
            }
            ul += '</ul>';
            $('.product-sizes-cont').html(ul);
        }
    
    
        $('.product-sizes-cont').on('click', '.ax__selected--size', function(evt){
            evt.preventDefault();
            let id = $(this).data('id');
            productSizes = productSizes.filter(item => item.id != id);
            $(this).closest('li').remove();
        });
    }


    function initColorModalEvent() {
        
        
        // add size from modal
        $('.content').on('click', '#ax-add__color', function(evt){
            productColors.push({
                id: $('#mcolor').val(),
                label: $('#mcolor option:selected').text(),
            });
            
            printColorsToDom(productColors);
            $('.custom__modal--content').hide();
        });
    
    
        function printSizesToDom(productSizes){
            let ul = '<ul>';
            for(let i = 0; i < productSizes.length; i++){
                ul += `<li>${productSizes[i].label} <a href="javascript:void(0);" data-id=${productSizes[i].id} class="ax__selected--size"><i class="far fa-times-circle -red"></i></a></li>`;
            }
            ul += '</ul>';
            $('.product-sizes-cont').html(ul);
        }

        function printColorsToDom(productColors){
            let ul = '<ul>';
            for(let i = 0; i < productColors.length; i++){
                ul += `<li>${productColors[i].label} <a href="javascript:void(0);" data-id=${productColors[i].id} class="ax__selected--color"><i class="far fa-times-circle -red"></i></a></li>`;
            }
            ul += '</ul>';
            $('.product-colors-cont').html(ul);
        }
    
    
        $('.product-colors-cont').on('click', '.ax__selected--color', function(evt){
            evt.preventDefault();
            let id = $(this).data('id');
            productColors = productColors.filter(item => item.id != id);
            $(this).closest('li').remove();
        });
    }

    // Form edit:: Change image
    $('#ax__product--change-img').on('click', function(evt){
        evt.preventDefault();
        $('.product__form--image-cont').hide();
        let imageHtml = '<div class="card-body">';
        imageHtml += '<div class="form-group">';
        imageHtml += '<input type="file" required="" data-parsley-max-file-size="2048" data-parsley-fileextension="jpg,jpeg,png,gif" name="file" id="file"/>';
        imageHtml += '</div>';
        imageHtml += '</div>';
        $('.product__image-file').html(imageHtml);
    });

    // Form edit:: change size
    $('.product-sizes-cont').on('click', '.ax__edit--size', function(evt){
        evt.preventDefault();
        let sizeId = $(this).data('id');
        let productId = $('input[name=id]').val();

        $.ajax({
            // Route name: admin.product.get.existing.size
            url: `/admin/product/${productId}/size/${sizeId}`,
            method: "POST",
            success: function(response){
                const {html} = response;
                $('.modal-html').html(html);
                $('.custom__modal--content').show();
            }
        });
    });
    $('#product_name').on('keyup', function(){
        let p = $(this).val();
        let slug = sluggify(p);
        $('#sku').val(slug);
    });
    $().on('');
    $('#form').on('submit', function(evt){
        evt.preventDefault();
        if($('#form').parsley().isValid()) {
            let form = new FormData();
            form.append('brand', $('#brand').val());
            form.append('category', $('#category').val());
            form.append('unit_of_measure', $('#unit_of_measure').val());
            form.append('product_name', $('#product_name').val());
            form.append('sku', $('#sku').val());
            form.append('inventory_count', $('#inventory_count').val());
            form.append('price', $('#price').val());
            form.append('discount_price', $('#discount__price').val());
            form.append('status', $('input[name=status]:checked').val());
            form.append('sizes', JSON.stringify(productSizes));
            form.append('colors', JSON.stringify(productColors));

            if ($('input[name=id]').length) {
                form.append('id', $('input[name=id]').val());

                if ($('input[type=file]').length > 0) {
                    // Attach file
                    form.append('image', $('input[type=file]')[0].files[0]);
                }
            } else {
                // Attach file
                if (document.getElementById('file').files.length > 0) {
                    form.append('image', $('input[type=file]')[0].files[0]);
                }
            }

            $.ajax({
                url: "{{route('admin.product.post.create')}}",
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
                        window.location.href = "{{route('admin.product.listing')}}";
                    });
                },
                error: function(jqXHR, jqStatus, jqThrown){
                    
                    let status = jqXHR.status;
                    console.log('ERROR ', jqXHR);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    });
                }
            });
        }
    });


})(jQuery);
</script>
@endsection