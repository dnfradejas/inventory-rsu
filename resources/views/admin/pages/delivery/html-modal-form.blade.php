<form id="form" data-parsley-validate="" autocomplete="off">
    @if($detail->id)
        <input type="hidden" id="id" value="{{$detail->id}}">
    @endif
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header" style="background-color: #D9D9D9">
            <h4 class="modal-title"><b>Add Delivery</b></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
            <div class="col">
                <div class="form-group">
                <label>Date of Delivery*</label>
                <div class="input-group date" id="deliverydatetime" data-target-input="nearest">
                    <input required="" type="text" class="form-control datetimepicker-input" data-target="#deliverydatetime"  value="{{$detail->delivery_date}}">
                    <div class="input-group-append" data-target="#deliverydatetime" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
                </div>
            </div>

            </div>
            <div class="row">
            <div class="col">
                <div class="form-group">
                <label>Supplier*</label>
                <select required="" id="supplier" name="supplier" class="form-control">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                    <?php if($detail && $detail->product_id == $supplier->id): ?>
                        <option selected value="{{$supplier->id}}">{{$supplier->name}}</option>
                    <?php else: ?>
                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                    <?php endif;?>
                    @endforeach
                </select>
                </div>
            </div>

            </div>

            <div class="card">
            <div class="card-header" style="background-color:#2E4051;">
                <h4 class="card-title" style="color:white">Product Details</h4>
            </div>
            <div class="card-body">
                <div class="row">
                <div class="col">
                    <div class="form-group">
                    <label>Select Product*</label>
                    <select required="" id="product" name="product" class="form-control">
                        <option value="">Select product</option>
                        @foreach($products as $product)
                        <?php if($detail && $detail->product_id == $product->id): ?>
                            <option selected value="{{$product->id}}">{{$product->product_name}} - {{$product->brand->brand}}</option>
                        <?php else: ?>
                            <option value="{{$product->id}}">{{$product->product_name}} - {{$product->brand->brand}}</option>
                        <?php endif;?>
                        @endforeach
                    </select>
                    </div>
                </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                        <label>Quantity*</label>
                        <input required="" data-parsley-type="digits" type="text" id="quantity" class="form-control" value="{{$detail->quantity}}" placeholder="Enter quantity">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                        <label>Barcode</label>
                            <input type="text" id="barcode" class="form-control" value="{{$detail->barcode}}" placeholder="Enter barcode">
                            <div class="barcode" style="color: red; display: none;"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                    <label>Production Date*</label>
                    <div class="input-group date" id="productiondate" data-target-input="nearest">
                        <input required="" type="text" class="form-control datetimepicker-input" data-target="#productiondate" value="{{$detail->production_date}}">
                        <div class="input-group-append" data-target="#productiondate" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                    <label>Expiration Date*</label>
                    <div class="input-group date" id="expirationdate" data-target-input="nearest">
                        <input required="" type="text" class="form-control datetimepicker-input" data-target="#expirationdate" value="{{$detail->expiration_date}}">
                        <div class="input-group-append" data-target="#expirationdate" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                    <div class="expiration_date" style="color: red; display: none;"></div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" id="btn-modal-close" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="add-product-btn" style="background-color: #2E4051; ">+ Add Product</button>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
</form>