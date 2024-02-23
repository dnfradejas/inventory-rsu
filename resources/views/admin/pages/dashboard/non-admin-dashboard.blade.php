@extends('admin.layout.main')
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
            <section class="col-lg-8 connectedSortable">
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
                        <tr>
                            <td>{{date('F j, Y', strtotime($product->delivery_date))}}</td>
                            <td>{{date('F j, Y', strtotime($product->expiration_date))}}</td>
                            <td>{{$product->supplier_name}}</td>
                            <!-- <td>Carrier - Tablas Forwarders</td> -->
                            <td>{{$product->product_name}}</td>
                            <td>{{$product->quantity}}</td>
                            <td>
                              <span>
                                <button data-target="#modal-lg" data-toggle="modal" data-id="{{$product->id}}" type="button" class="btn btn-block btn-warning btn-xs edit-btn"
                                style="font-weight: bold;">Edit</button>
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
            @include('admin.pages.delivery.stock-update-sheet')
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
        format: 'YYYY-MM-DD'
      });
    }

    function fetchHtmlModalForm(id = '') {
      $.ajax({
        url: `/admin/delivery/form-modal/${id}`, //route('admin.delivery.get.modal.html.form')
        method: "GET",
        success: function(response){
          const {html} = response;
          console.log(response);
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
            error: function(xhr, status, thrown){}

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
    


  })(jQuery);
</script>
@endsection