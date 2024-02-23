@if(count($product['sizes']) > 0)
<div class="row">
    <div class="item-option-select">
        <h6>Select Size</h6>
        <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
            @foreach($product['sizes'] as $size)
                <label class="btn btn-light radio-size" data-size="{{$size[0]}}">
                    <input type="radio" name="radio_size"> {{$size[1]}}
                </label>
            @endforeach
        </div> 
    </div>
</div> <!-- row.// -->
@endif