@extends('admin_supplier.layout_supplier.supplier-home')
@section('title',__('app.supplier'))
@php
use App\Color;
use App\Size;
use App\Status;
@endphp
@section('content')
<style>
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
</style>
<div class="container-fluid">
    <div class="container-fluid">
        <div class="row clearfix m-b-5">
            <button type="button" class="btn btn-success waves-effect pull-right print_data" onclick="print_report()">
                <i class="material-icons">print</i>
                <span>@lang('app.print_label')</span>
            </button>
        </div>
        <div class="row clearfix">
            <div class="card" id="printarea" style="padding: 10px;">
                <div class="row">
                    <div class="col-sm-12" style="text-align: center;">
                        <h3>@lang('app.product_detail')</h3>
                    </div>
                </div>
                <div class="row" style="text-align: left;">
                    <div class="col-sm-6">
                        <p>@lang('app.purchase_return_code'): {{ $purchase->purchase_code }}</p>
                    </div>
                    <div class="col-sm-6" style="text-align: end;">
                        <p>@lang('app.purchase_return_date'): {{ $purchase->date }}</p>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="table table-responsive">
                            <table class="table table-bordered" style="white-space: nowrap;">
                                <thead>
                                    <tr class="head_center" style="background-color: #ddd;">
                                        <th>@lang('app.no')</th>
                                        <th>@lang('app.code')</th>
                                        <th>@lang('app.image')</th>
                                        <th>@lang('app.name')</th>
                                        <th>@lang('app.unit_type')</th>
                                        <th>@lang('app.unit')</th>
                                        <th>@lang('app.color')</th>
                                        <th>@lang('app.size')</th>
                                        <th>@lang('app.unit_price')</th>
                                        <th>@lang('app.qty')</th>
                                        <th>@lang('app.status')</th>
                                        <th>@lang('app.total')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (sizeof($purchaseDetail) > 0)
                                        @php($i = 1)
                                        @foreach ($purchaseDetail as $item)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $item->code_product }}</td>
                                            <td style="padding: 5px;">
                                                <img class="img_one view_image" id="img_cus" src="{{ file_exists($item->image) ? asset($item->image) : asset('images/noimage.png') }}" alt="" width="50" height="50">
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->unit->type }}</td>
                                            <td>{{ $item->unit->name }}</td>
                                            @if ($item->pivot->color_id)
                                            @php($color = Color::where('id',$item->pivot->color_id)->first())
                                                <td>{{ $color->name }}</td>
                                            @endif
                                            @if ($item->pivot->size_id)
                                            @php($size = Size::where('id',$item->pivot->size_id)->first())
                                            <td>{{ $size->name }}</td>
                                            @else
                                            <td>N/A</td>
                                            @endif
                                            <td>{{ $item->pivot->price }}</td>
                                            <td>{{ $item->pivot->qty }}</td>
                                            <td>
                                                @php($item->pivot->status_id != null ? $status = Status::where('id',$item->pivot->status_id)->first()->name : "")
                                                 <span class="status_priorty"> {{ $item->pivot->status_id != null ? $status  : 'No Status'}}</span>
                                            </td>
                                            <td>{{ $item->pivot->total }}</td>
                                        </tr>
                                        @endforeach
                                        <tr style="background-color: #ddd;">
                                            <th colspan="4" style="text-align: right !important;">@lang('app.total'):</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>@lang('app.total_qty') : {{ $purchase->total_qty }}</th>
                                            <th>@lang('app.grand_total') :  {{ $purchase->grand_total }}</th>
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
