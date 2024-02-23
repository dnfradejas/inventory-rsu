<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="mcolor">Color <span class="-red">*<span></label>
            <select  id="mcolor" class="form-control">
                <option value="{{$color->color_id}}">{{$color->color}}</option>
            </select>
        </div>
        <div class="form-group">
            <a href="javascript:void(0);" class="btn btn-primary" id="ax-add__color">Add</a>
        </div>
    </div>
</div>