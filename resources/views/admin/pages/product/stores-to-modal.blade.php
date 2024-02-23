<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="store">Store <span class="-red">*<span></label>
            <!-- <select  id="store" class="form-control">
                <option value="">--Select store--</option>
                @foreach($stores as $store)
                    <option value="{{$store->id}}">{{$store->store_name}}</option>
                @endforeach
            </select> -->
            @foreach($stores as $store)
            <div class="custom-control custom-checkbox">
                <?php
                $is_checked = $store->is_checked;
                ?>
                <input class="custom-control-input chk__store" <?php echo $is_checked ? 'checked' : '';?> data-address="{{$store->address}}"  data-name="{{$store->store_name}}" data-id="{{$store->id}}"  type="checkbox" id="chk__store-{{$store->id}}" value="{{$store->id}}">
                <label for="chk__store-{{$store->id}}" class="custom-control-label">{{$store->store_name}} - <small>{{$store->address}}</small></label>
                <br>
                <div class="store__inventory-cont {{!$is_checked ? '-hidden' : ''}}">
                    <label class="control-label" for="store-inventory{{$store->id}}">Store Inventory: </label>
                    <input type="number" id="store-inventory{{$store->id}}" min="0" value="{{$store->input_inventory}}" class="store__inventory" placeholder="Store inventory">
                </div>
            </div>

            @endforeach
        </div>
        <div class="form-group">
            <a href="javascript:void(0);" class="btn btn-primary" id="ax-add__store">Add</a>
        </div>
    </div>
</div>