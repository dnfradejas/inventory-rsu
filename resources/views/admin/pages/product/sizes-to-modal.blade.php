<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="msize">Size <span class="-red">*<span></label>
            <select  id="msize" class="form-control">
                <option value="">--Select size--</option>
                @foreach($sizes as $size)
                    <option value="{{$size->id}}">{{$size->size}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="minventory_count">Inventory Count <span class="-red">*<span></label>
            <input type="number" id="minventory_count" class="form-control" value="0">
        </div>
        <div class="form-group">
            <label for="mprice">Price <span class="-red">*<span></label>
            <input type="number" id="mprice" class="form-control" value="0">
        </div>
        <div class="form-group">
            <label for="mdiscount_price">Discount Price</label>
            <input type="number" id="mdiscount_price" class="form-control" value="0">
        </div>
        <div class="form-group">
            <a href="javascript:void(0);" class="btn btn-primary" id="ax-add__size">Add</a>
        </div>
    </div>
</div>