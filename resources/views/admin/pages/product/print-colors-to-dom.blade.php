<ul>
    @foreach($colors as $color)
        <li>{{$color->color}} &nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" data-id="{{$color->id}}" class="ax__selected--color"><i class="far fa-times-circle -red"></i></a></li>
    @endforeach
</ul>