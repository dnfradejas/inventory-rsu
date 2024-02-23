@foreach($stores as $store)
    @if($store->status === 'active')
    <div class="section__stores--store store-active" data-slug="{{$store->slug}}">
        <div class="section__stores--store-logo">
            <figure class="section__stores--store-shape small-circle">
                <img src="/storage/images/icon/store-logo.png" alt="">
            </figure>
        </div>
        <div class="section__stores--store-name">
            <h4 class="font-large">{{$store->store_name}}</h4>
            <p class="-grey-primary">{{$store->address}}</p>
        </div>
    </div>
    @else
    <div class="section__stores--store store-inactive cursor-not-allowed" data-slug="{{$store->slug}}">
        <div class="section__stores--store-logo">
            <figure class="section__stores--store-shape small-circle">
                <img src="/storage/images/icon/store-logo.png" alt="">
            </figure>
        </div>
        <div class="section__stores--store-name">
            <h4 class="font-large">{{$store->store_name}}</h4>
            <p class="-grey-primary">{{$store->address}}</p>
        </div>
    </div>
    @endif
@endforeach