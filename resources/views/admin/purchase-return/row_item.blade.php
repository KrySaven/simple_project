<tr id="row_{{ $index }}" data-index="{{ $index }}">
    <th scope="row">0</th>
    <td style="display:none">
        {{ $result->pro_no }}
        {!! Form::input('number', "product_ids[$index]", $result->id, ['class' => 'hidden']) !!}
    </td>
    <td>{{ $result->code_product }}</td>
    <td style="padding: 5px;">
        @php
            $image = '';
            if(file_exists($result->image)){
                $image = asset($result->image);
            }else{
                $image = asset('images/images.png');
            }
        @endphp
        <img class="img_one view_image" id="img_cus" src="{{ $image }}" alt="" width="50" height="50">
    </td>

    <td>{{ $result->name }}</td>
    <td>{{ $result->type }}</td>
    <td>
        <div class="form-line">
            {!! Form::select("color_ids[$index]", $colors, $item->color_id ?? null, [
            'class'=>'form-control',
            'data-live-search'=>'true',
            'placeholder'=>'Select Color',
            ]) !!}
        </div>
        <span class="error-msg hidden" id="color_ids_{{ $index }}_error"></span>
    </td>
    <td>

    @if ($result->type == 'size')
        <div class="form-line">
            {!! Form::select("size_ids[$index]", $sizes, $item->size ?? null, [
                'class'=>'form-control',
                'data-live-search'=>'true',
                'placeholder'=>'Select Size',
            ]) !!}
        </div>
         <span class="error-msg hidden" id="size_ids_{{ $index }}_error"></span>
    @endif

    </td>
    <td>
        {!! Form::input('number', "unit_prices[$index]", $result->price ?? $result->price, ['class' => 'form-control
        unit_price']) !!}
    </td>
    <td>
        {!! Form::input('number', "qtys[$index]",null, ['class' => 'form-control qtys','min'=>1]) !!}
          <span class="error-msg hidden" id="qtys_{{ $index }}_error"></span>
    </td>
    <td>
        <span class="total_prices">
            @if (old('total_prices'))
                {{ old('total_prices')[$index] }}
            @else
                {{ $item->total ?? 0 }}
            @endif
        </span>
        {!! Form::input('number', "total_prices[$index]", $item->total ?? null, ['class' => 'form-control hidden']) !!}
    </td>
    <td>
            <i class="material-icons trash_icon">delete</i>

    </td>
</tr>
