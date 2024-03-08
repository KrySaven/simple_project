@extends('layouts.app')
@section('title', __('app.supplier'))
@php
    use App\Color;
    use App\Size;
@endphp
@section('content')
    {{-- <style>
    div.dataTables_wrapper div.dataTables_filter {
        margin-top: 10px !important;
    }

    .head_center th {
        text-align: center;
        background-color: #ddd;
        vertical-align: middle !important;
    }

    .verticle_allign_midle {
        vertical-align: middle;
    }

    .head_centers th {
        text-align: center;
        background-color: #ddd;

    }

    .table_datas {
        width: 100%
    }

    .table_datas thead tr:first-child>th {
        /*text-align: center;*/
    }

    .table_datas thead tr:first-child>td {
        /*text-align: center;*/
    }

    .border_left_buttom th {
        border-top: 0px !important;
        /*border-right: 0px !important;*/
        border-left: 0px !important;
    }

    .tr_border_buttom tr td,
    th {
        /*border-bottom: 0px !important;*/
        /*border-right: 0px !important;*/
        border-left: 0px !important;
    }

    .rights {
        border-right: 0px !important;
    }

    .owed_color {
        color: red !important;
    }

    .text_red {
        color: #F44336 !important;
    }

    @media print {
        .text_red {
            color: #F44336 !important;
        }

        .table-bordered tbody tr th {
            padding: 5px 2px;
            border: 1px solid #000 !important;
            font-size: 10px !important;
            text-align: center !important;
        }

        .table-bordered tbody tr td {
            padding: 5px 2px;
            border: 1px solid #000 !important;
            font-size: 10px !important;
            text-align: center !important;
        }

        .table-bordered thead tr th {
            padding: 5px 2px;
            border: 1px solid #000 !important;
            text-align: center !important;
        }

        .col-sm-6 {
            width: 50%;
            float: left;
        }

        .no_width {
            width: 50px !important
        }

        .owed_color {
            color: red !important;
        }

        #printarea {
            font-family: 'Khmer OS Battambong';
        }

        /*p{font-size: 10px !important;}*/
    }
</style> --}}
    <style>
        td,
        th {
            white-space: nowrap;
        }
    </style>
    <div class="container-fluid">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="card" id="printarea">
                    <div class="header">
                        <h2>@lang('app.priority_product')</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">

                            <div class="col-sm-12">

                                <p><strong>@lang('app.purchase_return_code')</strong>: {{ $purchase->purchase_code }}</p>
                                <p><strong>@lang('app.purchase_return_date')</strong>: {{ $purchase->date }}</p>

                                <div class="table table-responsive">
                                    <table
                                        class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                        <thead>
                                            <tr>
                                                <th class="center_text">@lang('app.no')</th>
                                                <th class="center_text">@lang('app.code')</th>
                                                <th class="center_text">@lang('app.image')</th>
                                                <th class="center_text">@lang('app.name')</th>
                                                <th class="center_text">@lang('app.unit_type')</th>
                                                <th class="center_text">@lang('app.unit')</th>
                                                <th class="center_text">@lang('app.color')</th>
                                                <th class="center_text">@lang('app.size')</th>
                                                <th class="center_text">@lang('app.unit_price')</th>
                                                <th class="center_text">@lang('app.qty')</th>
                                                <th class="center_text">@lang('app.total')</th>
                                                <th class="center_text">@lang('app.status')</th>
                                                {{-- <th class="center_text">@lang('app.note')</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (sizeof($purchaseDetail) > 0)
                                                @php($i = 1)
                                                @foreach ($purchaseDetail as $item)
                                                    {{-- @dd($item->pivot) --}}
                                                    <tr>
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $item->code_product }}</td>
                                                        <td style="padding: 5px;">
                                                            <img class="img_one view_image" id="img_cus"
                                                                src="{{ file_exists($item->image) ? asset($item->image) : asset('images/noimage.png') }}"
                                                                alt="" width="50" height="50">
                                                        </td>
                                                        <td>{{ $item->name }}</td>
                                                        <td>{{ $item->unit->type }}</td>
                                                        <td>{{ $item->unit->name }}</td>
                                                        @if ($item->pivot->color_id)
                                                            @php($color = Color::where('id', $item->pivot->color_id)->first())
                                                            <td>{{ $color->name }}</td>
                                                        @endif
                                                        @if ($item->pivot->size_id)
                                                            @php($size = Size::where('id', $item->pivot->size_id)->first())
                                                            <td>{{ $size->name }}</td>
                                                        @else
                                                            <td>N/A</td>
                                                        @endif
                                                        <td>{{ $item->pivot->price }}</td>
                                                        <td>{{ $item->pivot->qty }}</td>
                                                        <td>{{ $item->pivot->total }}</td>
                                                        <td>
                                                            <div class="form-line">
                                                                <input type="hidden" value="{{ $item->pivot->id }}"
                                                                    class="detail_item">
                                                                {!! Form::select('status_id', $status, $item->pivot->status_id ?? null, [
                                                                    'class' => 'form-control priority_status',
                                                                    'data-live-search' => 'true',
                                                                    'placeholder' => 'Select Status',
                                                                ]) !!}
                                                            </div>
                                                            <span class="error-msg hidden" id="status_id_error"></span>
                                                        </td>
                                                        {{-- <td>
                                                            <a href="{{ route('purchase.purchase_detail_note', ['p_detail_id' => $item->pivot->id]) }}"
                                                                class="delete_btn btn_at_home" type="button"><i
                                                                    class="material-icons icon_delete">note</i>Note</a>
                                                        </td> --}}
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="4" style="text-align: right !important;">
                                                        @lang('app.total'):</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th>Total Qty: {{ $purchase->total_qty }}</th>
                                                    <th>GrandTotal {{ $purchase->grand_total }}</th>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscripts')
    <script>
        $(".priority_status").on('change', function() {
            var ps_id = $(this).val();
            var detailItemId = $(this).closest('.form-line').find('.detail_item').val();
            // console.log(detail_item)
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{!! route('purchase.priority_detail_status') !!}",
                type: 'GET',
                dataType: "json",
                global: false,
                data: {
                    _token: CSRF_TOKEN,
                    item_detail_id: detailItemId,
                    purchase_status_id: ps_id,
                },
                success: function(response) {
                    if (response.code === 200) {
                        // console.log();
                        Swal.fire({
                            icon: 'success',
                            title: response.messages.title,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        });
    </script>
@endpush
