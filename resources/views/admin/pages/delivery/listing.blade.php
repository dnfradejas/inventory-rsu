@extends('admin.layout.main')
@section('styles')
<style>
  #deliverydatetime, #productiondate, #expirationdate {
    position: relative;
  }
  #deliverydatetime > ul, #productiondate > ul, #expirationdate > ul {
    position: absolute;
    top: 34px;
    
  }

.parsley-errors-list {
  margin-left: -40px;
}
.parsley-errors-list > li {
  list-style-type: none;
  
}
</style>
@endsection
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0" style="font-weight: bold;">Product Deliveries</h1>
              <p class="m-0">Update product supplies</p>
            </div><!-- /.col -->

          </div><!-- /.row -->

        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <section class="content">

        <div class="container-fluid">
          <!-- /.row -->
          <!-- Main row -->
          <div class="row">
            <!-- Left col -->
            <section class="col-lg-12 connectedSortable">
              <!--Delivery Details-->
              <div class="card" style="height: 70vh;">
                <div class="card-header" style="background-color:#2E4051 !important;">
                  <h3 class="card-title" style="color:white !important;">
                    <i class="fas fa-car mr-1"></i>
                    Delivery Details
                  </h3>
                  <div class="card-tools">
                    <button type="button" id="add-delivery-btn" class="btn btn-block btn-success btn-xs" data-toggle="modal"
                      data-target="#modal-lg">+ Add Delivery</button>
                  </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0" style="height: 200px;">
                  <table class="table table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                        <th>Delivery Date</th>
                        <th>Expiration Date</th>
                        <th>Supplier</th>
                        <!-- <th>Mode of Delivery</th> -->
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $product)
                        <tr <?php if(is_yesterday($product->expiration_date)): ?> style="color: red;" <?php endif;?>>
                            <td>{{date('F j, Y', strtotime($product->delivery_date))}}</td>
                            <td>{{date('F j, Y', strtotime($product->expiration_date))}}</td>
                            <td>{{$product->supplier_name}}</td>
                            <td>{{$product->product_name}}</td>
                            <td>{{$product->quantity}}</td>
                            <td>
                              @if(is_yesterday($product->expiration_date))
                                <a data-id="{{$product->id}}" href="javascript:void(0);" style="background: #dc3545;" class="btn btn-block btn-danger btn-xs btn-delete-expiring-product">Delete</a>
                              @else
                              <span>
                                <button data-target="#modal-lg" data-toggle="modal" data-id="{{$product->id}}" type="button" class="btn btn-block btn-warning btn-xs edit-btn"
                                style="font-weight: bold;">Edit</button>
                              @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

            </section>

            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            {{-- @include('admin.pages.delivery.stock-update-sheet') --}}
            <!-- right col -->
          </div>
          <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
      </section>

      <!-- modal  -->
      @include('admin.pages.delivery.modal')
      <!-- /.modal -->
@endsection
@section('scripts')
<script>
  (function($){

    function initDates() {

      // Delivery date picker
      $('body').find('#deliverydatetime').datetimepicker({
        icons:{ 
          time: 'far fa-clock'
        },
        format: "YYYY-MM-DD HH:mm"
      });

      // Product date picker
      $('body').find('#productiondate').datetimepicker({
          format: 'YYYY-MM-DD'
      });

      // Expiration date picker
      $('body').find('#expirationdate').datetimepicker({
        format: 'YYYY-MM-DD',
        minDate: new Date(),
      });
    }

    $('.delivery--modal').on('keypress', '#barcode', function(evt){
      if (evt.keyCode == 13) {
          event.preventDefault();
      }
    });

    function fetchHtmlModalForm(id = '') {
      $.ajax({
        url: `/admin/delivery/form-modal/${id}`, //route('admin.delivery.get.modal.html.form')
        method: "GET",
        success: function(response){
          const {html} = response;
          $('.modal--delivery-detail').html(html);
          initDates();
        }
      });
    }

    function formSubmit(evt) {
        evt.preventDefault();

        if ($('#form').parsley().isValid()) {
          let form = {
            delivery_date: $('#deliverydatetime').find('input').val(),
            supplier: parseInt($('#supplier').val()),
            product: parseInt($('#product').val()),
            quantity: parseInt($('#quantity').val()),
            barcode: $('#barcode').val(),
            production_date: $('#productiondate').find('input').val(),
            expiration_date: $('#expirationdate').find('input').val(),
          };

          if ($('#id').length > 0) {
            form.id = parseInt($('#id').val());
          }

          $.ajax({
            url: "{{route('admin.delivery.post.create')}}",
            method: "POST",
            data: form,
            success: function(response){
                Swal.fire({
                  icon: 'success',
                  title: 'Save!',
                  // showConfirmButton: false,
                }).then(function(){
                  window.location.href = window.location.href;
                });
            },
            error: function(xhr, status, thrown){
              const response = xhr.responseJSON;
              const {errors} = response;
              console.log(errors);
              if (xhr.status == 422) {
                if (errors.hasOwnProperty('expiration_date')) {
                  $('.expiration_date').text('Expiration date must not be less than today').show();
                } else {
                  $('.barcode').text('Barcode has already been taken.').show();
                }
              }
            }
          });
        }
    }

    function editDetails(evt) {
      evt.preventDefault();
      let id = $(this).data('id');

      fetchHtmlModalForm(id);


    }

    $('#add-delivery-btn').on('click', function(evt){
      evt.preventDefault();
      fetchHtmlModalForm();
    });

    $('#form').on('submit', formSubmit);
    $('body').on('click', '.edit-btn', editDetails);


    $('.delivery--modal').on('click', '.close', function(){
        window.location.href = window.location.href;
    });

    $('.delivery--modal').on('click', '#btn-modal-close', function(){
        window.location.href = window.location.href;
    });

    

    $('.btn-delete-expiring-product').on('click', function(e){
      let id = $(this).data('id');
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: "{{route('admin.delivery.expired.delete')}}",
            method: "DELETE",
            data: {
              id: id
            },
            success: function(response){
              const {message} = response;
              Swal.fire(
                'Deleted!',
                message,
                'success'
              ).then(() => {
                window.location.href = window.location.href;
              });
            },
            error: function(xhr){
              console.log('error', xhr);
            }
          });
          
        }
      });
    });
  })(jQuery);
</script>
@endsection