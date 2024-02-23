<ul>
    @foreach($sizes as $size)
        <li><a href="javascript:void(0);" data-id="{{$size->id}}" class="ax__edit--size">{{$size->size}} </a> &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" data-id="{{$size->id}}" class="ax__selected--size"><i class="far fa-times-circle -red"></i></a></li>
    @endforeach
</ul>