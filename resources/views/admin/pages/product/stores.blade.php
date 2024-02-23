<div class="existing__product--sizes">
@if(count($stores))
    <div class="existing__product--sizes-title">
        <h4 class="-bold">Available stores</h4>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="modal__product--sizes-cont">
                <ul>
                    @foreach($stores as $store)
                    <li>{{$store->store_name}} - <small>{{$store->address}}</small></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@else
    <div class="existing__product--sizes-title">
        <h4 class="-bold">No stores have been added to this product.</h4>
    </div>
@endif
</div>