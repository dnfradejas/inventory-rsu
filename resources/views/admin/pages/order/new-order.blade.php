@extends('admin.layout.main')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0" style="font-weight: bold;">Select Products below</h1>
              <!-- <p class="m-0">Liwayway, Odiongan, Romblon</p> -->
            </div><!-- /.col -->

          </div><!-- /.row -->

        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->


      <!-- Main content -->
      <section class="content pb-3">
        <div class="container-fluid">
          <!-- Main row -->
          <div class="row">
            <section class="col-lg-8 ">
              <div class="card card-primary" style="height: 75vh; max-height: 75vh; width: auto;">
                <div class="card-header" style="background-color:#2E4051 !important;">
                  <h3 class="card-title" style="color:white !important;">
                    <h3 class="card-title" style="color:white !important;">
                      Product List</h3>
                    <div class="card-tools">
                      <!-- <div class="input-group input-group-sm" style="width: 300px; ">
                        <input type="text" name="table_search" class="form-control float-right"
                          placeholder="Search Product">

                        <div class="input-group-append">
                          <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                          </button>
                        </div>
                      </div> -->
                    </div>
                </div>
                <div class="card-body" style="overflow-y: scroll;">
                  <div class="row">
                    @foreach($details as $product)
                    <div class="col-sm-3 mailbox-attachments" style="margin-bottom: 10px;">
                      <a href="#">
                        <!-- <span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo1.png"
                            alt="Attachment"></span> -->
                        <div class="mailbox-attachment-info">
                          <a href="#" class="mailbox-attachment-name">{{$product->product_name}} <span class="mailbox-attachment-size clearfix mt-1">(exp: {{$product->expiration_date}})</span></a>
                          <br><span style="font-weight: bold; color: #F57224;">₱ {{$product->discount_price > 0 ? number_format($product->discount_price, 2) : number_format($product->price, 2)}}</span>
                          <span class="mailbox-attachment-size clearfix mt-1">
                            <span>{{$product->quantity}} stocks</span>
                            <a data-id="{{$product->id}}" href="javascript:void(0);" class="btn btn-default btn-sm float-right productlist-add-btn"><i class="fas fa-cart-plus"></i></a>
                          </span>
                        </div>
                      </a>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>


              <div class="row">
                <!--Buttons-->

                <div class="col-2 col-md-6 text-center">
                  <button type="button" class="btn btn-block btn-success btn-lg" data-toggle="modal"
                    data-target="#modal-check-price"><b>CHECK PRICE</b></button>
                </div>

                <div class="col-2 col-md-6 text-center">
                  <div class="col text-center">
                    <button type="button" class="btn btn-block btn-warning btn-lg" data-toggle="modal"
                      data-target="#modal-barcode" style="color:white;"><b>BARCODE SCANNER</b></button>
                  </div>
                </div>
              </div>

            </section>
            <section class="col-lg-4 section--order-list">
                @include('admin.pages.order.section.orderlist')
            </section>
          </div>
          <!-- /.row (main row) -->

        </div><!-- /.container-fluid -->
        
        @include('admin.pages.order.modal.checkprice')
        @include('admin.pages.order.modal.barcode')
        </div>
      </section>
@endsection
@section('scripts')
<script type="text/javascript">
(function($){


  function addProductToOrder(obj, quantity, quantity_append = false) {

    let formData = obj;
    formData.quantity = quantity;
    formData.quantity_append = quantity_append;

    $.ajax({
      url: "{{route('admin.order.post.add')}}",
      method: "POST",
      data: formData,
      success: function(response){
        const {html} = response;
        $('.section--order-list').html(html);
      },
      error: function(xhr, status, thrown){
        let response = xhr.responseJSON;
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: response.message,
        });
      }
    });
  }

  $('.section--order-list').on('click', '#ordernow-btn', function(){

    if ($('.container-fluid').find('.orderlist-items').length > 0) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{route('admin.post.order.now')}}",
            method: "POST",
            success: function(response){
              Swal.fire(
                'Order Created!',
                'Order has been successfully created!',
                'success'
              ).then(function(){
                window.location.href = window.location.href;
              });
  
              // const {html} = response;
              // $('.section--order-list').html(html);
            },
            error: function(xhr, status, thrown){
              let response = xhr.responseJSON;
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: response.message,
              });
            }
          });
        }
      });

    }
  });

  $('.productlist-add-btn').on('click', function(evt){
    evt.preventDefault();
    let id = parseInt($(this).data('id'));
    let quantity = 1;
    console.log({id:id, delivery_detail_id: id});
    addProductToOrder({id:id, delivery_detail_id: id}, quantity, true);
  });


  $('#input-barcode').on('keyup', debounce(function(evt){
    evt.preventDefault();
    if (evt.keyCode == 13) {
        event.preventDefault();
    }
    if (!$(this).val()) {
      console.log('no barcode');
    } else {
      let barcode = $(this).val();
      let quantity = $('#input-quantity').val();
      addProductToOrder({barcode:barcode}, quantity, true);
    }
  }, 500));

  $('#input-quantity').on('keyup', function(evt){
    if (evt.keyCode == 13) {
        event.preventDefault();
    }
  });


  $('#input-barcode-for-price-checking').on('keyup', debounce(function(evt){
    evt.preventDefault();

    if (evt.keyCode == 13) {
        event.preventDefault();
    }

    let barcode = $(this).val();

    $.ajax({
      url: "{{route('admin.product.post.check.price')}}",
      method: "POST",
      data: {
        q: barcode,
      },
      success: function(response){
        const {html} = response;
        console.log(response);
        $('.checked--product-price').html(html);
      },
      error: function(xhr, status, thrown){
        console.log('xhr', xhr);
      }
    });
  }, 500));
  

  $('#modal-check-price').on('click', '#btn-add-product', function(evt){
    let barcode = $('#input-barcode-for-price-checking').val();
    let quantity = 1;
    addProductToOrder({barcode:barcode}, quantity, true);
  });

  $('.section--order-list').on('keyup', '.orderlist-input-quantity', debounce(function(evt){
    evt.preventDefault();

    if (!$(this).val()) {
      console.log('input is empty');
    } else {
      let quantity = parseInt($(this).val());
      let id = parseInt($(this).data('id'));
      
      if (quantity == 0) {
        quantity = 1;
        $(this).val(quantity);
      }

      addProductToOrder({order_product_id: id}, quantity, false);
    }

  }, 500));

  $('.section--order-list').on('click', '.orderlist-remove-btn', function(){
    let id = parseInt($(this).data('id'));
    $.ajax({
      url: "{{route('admin.order.post.delete.item')}}",
      method: "POST",
      data: {
        id: id
      },
      success: function(response){
        const {html} = response;
        $('.section--order-list').html(html);
      },
      error: function(xhr, status, thrown){}
    });
  });
    
})(jQuery);
</script>
@endsection