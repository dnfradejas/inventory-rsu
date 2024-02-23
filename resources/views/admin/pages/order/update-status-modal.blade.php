<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="">Select Status</label>
            <select name="" class="form-control" id="status">
                <option value="">--Select--</option>
                @foreach($statuses as $status)
                <option value="{{$status}}">{{$status}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <button type="button" id="btn__update-status" class="btn">Submit</button>
        </div>
    </div>
</div>