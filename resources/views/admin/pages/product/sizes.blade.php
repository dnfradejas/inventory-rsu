<div class="existing__product--sizes">
@if(count($sizes))
    <div class="existing__product--sizes-title">
        <h4 class="-bold">Available sizes</h4>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="modal__product--sizes-cont">
                <ul>
                    @foreach($sizes as $size)
                    <li>{{$size->size}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@else
    <div class="existing__product--sizes-title">
        <h4 class="-bold">No sizes have been added to this product.</h4>
    </div>
@endif
</div>