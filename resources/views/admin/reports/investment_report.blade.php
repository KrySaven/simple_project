@extends('layouts.app')
@section('title',__('app.investment_report'))
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
        .table_datas thead tr:first-child > th {
            /*text-align: center;*/
        }
        .table_datas thead tr:first-child > td {
            /*text-align: center;*/
        }
        .border_left_buttom th {
            border-top: 0px !important;
            /*border-right: 0px !important;*/
            border-left: 0px !important;
        }
        .tr_border_buttom tr td, th {
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
        .text_red{color: #F44336 !important;}
        @media print {
            .text_red{color: #F44336 !important;}
            .table-bordered tbody tr th {
                padding: 5px;
                border: 1px solid #000 !important;
                font-size: 10px !important;
                white-space: nowrap !important;
            }
            .table-bordered tbody tr td {
                padding: 5px;
                border: 1px solid #000 !important;
                font-size: 10px !important;
            }
            .table-bordered thead tr th {
                padding: 5px;
                border: 1px solid #000 !important;
                white-space: nowrap !important;
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
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>@lang('app.investment_report')</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            {!! Form::open(array('route' => 'investment_report' , 'method' => 'GET')) !!}
                            @include('admin.includes.error')
                            {{-- <div class="col-sm-5">
                                <b>@lang('app.from_date')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_from',(isset($date_from) ? $date_from :null),['class'=>'form-control datetime','placeholder'=>'From Date','id'=>'date_from'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <b>@lang('app.to_date')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_to',(isset($date_to) ? $date_to : null),['class'=>'form-control','placeholder'=>'To Date','id'=>'date_to'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <button type="submit" class="btn bg-green waves-effect pull-right">
                                        <i class="material-icons">search</i>
                                        <span>@lang('app.search')</span>
                                    </button>
                                </div>
                            </div> --}}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Basic Examples -->
        <div class="container-fluid">
            <div class="row clearfix">
                <button type="button" class="btn btn-success waves-effect pull-right print_data" onclick="print_report()">
                    <i class="material-icons">print</i>
                    <span>@lang('app.print_label')</span>
                </button>
            </div>
            <div class="row clearfix">
                <div class="card" id="printarea" style="padding: 15px;">
                    <div class="row">
                        <div class="col-sm-12" style="text-align: center;">
                            <h3>@lang('app.investment_report')</h3>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-sm-12 ">
                            <p>Date: {{date('d-M-Y H:i:s')}} </p>
                            <p>Print By: {{Auth::user()->name}}</p>
                        </div>
                    </div> --}}

                    {{-- <div class="row" style="text-align: left;">
                        <div class="col-sm-6">
                            <p>@lang('app.from_date'): {{ date("d-M-Y", strtotime($date_from))}} </p>
                        </div>
                        <div class="col-sm-6" style="text-align: right;">
                            <p>@lang('app.to_date'): {{ date("d-M-Y", strtotime($date_to))}} </p>
                        </div>
                    </div> --}}
                    
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div id="responsive" class="table table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="head_center">
                                            <th>@lang('app.no_en')</th>
                                            <th style="text-align: left;">@lang('app.status')</th>
                                            <th>@lang('app.amount')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($total = 0)
                                        @php($i = 1)
                                        @foreach($status_arr as $key => $row)
                                            {{-- @php($total += $row->total_sale) --}}
                                            {{-- @php($total_count += $row->count_sale)  --}}
                                            <tr>
                                                <td style="text-align:center;">{{ $i++ }}</td>
                                                <td style="text-align: left;">@lang('app.total') {{ $row }}</td>
                                                @php($amount = isset($daily_sale_arr[$key])?$daily_sale_arr[$key]:0)
                                                @php($total += $amount)

                                                {{-- @php($total = $daily_sale_arr['investment'] + $daily_sale_arr['principle']) --}}

                                                {{-- @dd($amount) --}}
                                                {{-- <td style="text-align:center;">{{ $row->count_sale }}</td> --}}
                                                <td class="@if($amount < 0) {{ 'text_red' }} @endif" style="text-align:right;">{{ number_format($amount,2) }}</td>
                                            </tr>  
                                            {{-- @dd($daily_sale_arr,$daily_sale_arr['principle'])                                       --}}
                                        @endforeach
                                        <tr style="background-color: #ddd;">
                                            <th colspan="2" style="text-align: right;">@lang('app.total'):</th>
                                            <th class="@if($total < 0) {{ 'text_red' }} @endif" style="text-align: right;">{{ number_format($total,2) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection