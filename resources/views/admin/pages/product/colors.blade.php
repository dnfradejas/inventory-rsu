<div class="existing__product--colors">
@if(count($colors) > 0)
    <div class="existing__product--colors-title">
        <h4 class="-bold">Available colors</h4>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="modal__product--colors-cont">
                <ul>
                    @foreach($colors as $color)
                        <li>{{$color->color}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

@else
    <div class="existing__product--colors-title">
        <h4 class="-bold">No colors added to this product!</h4>
    </div>
@endif
</div>