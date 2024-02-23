<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="msize">Size <span class="-red">*<span></label>
            <select  id="msize" class="form-control">
                <option value="{{$size->size_id}}">{{$size->size}}</option>
                
            </select>
        </div>
        <div class="form-group">
            <label for="minventory_count">Inventory Count <span class="-red">*<span></label>
            <input type="number" id="minventory_count" class="form-control" value="{{$size->inventory_count}}">
        </div>
        <div class="form-group">
            <label for="mprice">Price <span class="-red">*<span></label>
            <input type="number" id="mprice" class="form-control" value="{{$size->price}}">
        </div>
        <div class="form-group">
            <label for="mdiscount_price">Discount Price</label>
            <input type="number" id="mdiscount_price" class="form-control" value="{{$size->discount_price}}">
        </div>
        <div class="form-group">
            <a href="javascript:void(0);" class="btn btn-primary" id="ax-add__size">Add</a>
        </div>
    </div>
</div>