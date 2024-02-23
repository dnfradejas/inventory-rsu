<ul>
    @foreach($stores as $store)
        <li><a href="javascript:void(0);" data-id="{{$store->id}}" class="ax__edit--store">{{$store->store_name}} - <small>{{$store->address}}</small></a> &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" data-id="{{$store->id}}" class="ax__selected--store"><i class="far fa-times-circle -red"></i></a></li>
    @endforeach
</ul>