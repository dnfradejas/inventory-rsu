<div class="modal fade" id="modal-barcode">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header" style="background-color: #D9D9D9">
        <h4 class="modal-title"><b>Scan Product</b></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-2">
                <div class="form-group">
                    <label>Quantity</label>
                    <div class="input-group">
                    <input type="number" id="input-quantity" class="form-control" value="1"
                        data-mask="1" inputmode="numeric" width="30% !important">
                    </div>
                </div>
                </div>
                <div class="col">
                <div class="form-group">
                    <label>Barcode</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                        </div>
                        <input type="text" class="form-control" id="input-barcode">
                    </div>
                </div>
                </div>
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>