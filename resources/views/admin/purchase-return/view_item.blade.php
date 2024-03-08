@if ($productItem)
    <tr>
        <th scope="row">1</th>
        <td>{{ $productItem->pro_no }}</td>
        <td>{{ $productItem->name }}</td>
        <td style="width: 5% !important">
            <div class="form-line">
                <input name="case[]" type="text" class="form-control">
            </div>
        </td>
        <td style="width: 5% !important">
            {!! Form::select('color_id',['sdfs','sdfsd'], null, [
                'class' => 'form-control show-tick',
                'data-live-search' => 'true',
            ]) !!}
            @if ($errors->has('color_id'))
                <span class="invalid-feedback" role="alert">
                    <label id="color_id-error" class="error" for="color_id">{{ $errors->first('color_id') }}</label>
                </span>
            @endif
        </td>


        @if ($sizes)
            @foreach ($sizes as $size)
                <td style="width: 5% !important">
                    <div class="form-line">
                        <input name="size[{{ $size->id }}]" type="text" class="form-control" placeholder="10">
                    </div>
                </td>
            @endforeach
        @endif

        <td>{{ $productItem->price }}</td>
        <td>{{ $productItem->price }}</td>
        <td class="text-center"><a href="javascript:void(0);"><i class="material-icons icon_delete">delete</i></a></td>
    </tr>
@endif
