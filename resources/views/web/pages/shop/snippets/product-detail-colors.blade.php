@if(count($product['colors']) > 0)
<div class="row">
    <div class="item-option-select">
        <h6>Choose Color</h6>
        <div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
            @foreach($product['colors'] as $color)
                <label class="btn btn-light radio-color" data-color="{{$color[0]}}">
                    <input type="radio" name="radio_color"> {{$color[1]}}
                </label>
            @endforeach
        </div> 
    </div>
</div> <!-- row.// -->
@endif