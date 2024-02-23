<form action="{{$action}}" method="POST">
    @csrf
    <div class="order__export">
        <label>Filter date:</label>
        <div class="order__export-date">
            <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                <i class="far fa-calendar-alt"></i>
                </span>
            </div>
            <input type="text" class="form-control float-right"name="date" id="filter_date">
            <input type="hidden" name="status" value="{{$status}}">
            </div>
            <!-- /.input group -->
        </div>
        <div class="order__export-button order-export">
            <button type="submit"><i class="fas fa-file-export"></i> Export</button>
        </div>
    </div>
</form>