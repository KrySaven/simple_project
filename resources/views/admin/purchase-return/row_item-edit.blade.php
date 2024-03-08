<tr id="row_{{ $index }}" data-index="{{ $index }}">
    <th scope="row">0</th>
    <td style="display:none">
        {{ $pReturn->purchase_no }}
        {!! Form::input('number', "product_ids[$index]", $item->id, ['class' => 'hidden']) !!}
    </td>
    <td>{{ $item->code_product }}</td>
    <td style="padding: 5px;">
        @php
            $image = '';
            if (file_exists($item->image)) {
                $image = asset($item->image);
            } else {
                $image = asset('images/images.png');
            }
        @endphp
        <img class="img_one view_image" id="img_cus" src="{{ $image }}" alt="" width="50"
            height="50">
    </td>

    <td>{{ $item->name }}</td>
    <td>{{ $item->type }}</td>
    <td>
        @php
            $colors = $item->colors->pluck('name', 'id');
        @endphp
        <div class="form-line">
            {!! Form::select("color_ids[$index]", $colors, $item->pivot->color_id ?? null, [
                'class' => 'form-control',
                'data-live-search' => 'true',
                'placeholder' => 'Select Color',
            ]) !!}
        </div>
        <span class="error-msg hidden" id="color_ids_{{ $index }}_error"></span>
    </td>
    <td>
    {{-- @dd($item->pivot->size_id ) --}}
    @php
         use App\Size;
         $sizes  = Size::where('unit_id', $item->unit_id)->pluck('name', 'id') ?? null;

    @endphp
    @if ($item->pivot->size_id )
        <div class="form-line">
            {!! Form::select("size_ids[$index]", $sizes, $item->pivot->size_id ?? null, [
                'class' => 'form-control',
                'data-live-search' => 'true',
                'placeholder' => 'Select Size',
            ]) !!}
        </div>
        <span class="error-msg hidden" id="size_ids_{{ $index }}_error"></span>
    @endif

    </td>
    <td>
        {!! Form::input('number', "unit_prices[$index]", $item->pivot->price, [
            'class' => 'form-control
                unit_price',
        ]) !!}
    </td>
    <td>
        {!! Form::input('number', "qtys[$index]", $item->pivot->qty, ['class' => 'form-control qtys','min'=>1]) !!}
        <span class="error-msg hidden" id="qtys_{{ $index }}_error"></span>
    </td>
    <td>
        <span class="total_prices">
            @if (old('total_prices'))
                {{ old('total_prices')[$index] }}
            @else
                {{ $item->pivot->total ?? 0 }}
            @endif
        </span>
        {!! Form::input('number', "total_prices[$index]", $item->pivot->total ?? null, [
            'class' => 'form-control hidden',
        ]) !!}
    </td>
    <td>
        <i class="material-icons trash_icon">delete</i>

    </td>
</tr>
