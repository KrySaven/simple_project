@extends('layouts.app')
@section('title',__('app.customer_loan_statement_report'))
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
                        <h2>@lang('app.search')</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            {!! Form::open(array('route' => 'channel_report' , 'method' => 'GET')) !!}
                            @include('admin.includes.error')
                            <div class="col-sm-2">
                                <b>@lang('app.from_date')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_from',(isset($date_from) ? $date_from :null),['class'=>'form-control datetime','placeholder'=>'From Date','id'=>'date_from'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <b>@lang('app.to_date')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_to',(isset($date_to) ? $date_to : null),['class'=>'form-control','placeholder'=>'To Date','id'=>'date_to'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <b>@lang('app.customer')</b>
                                <div class="form-group">
                                    {!! Form::select('customer_id', $customer,$request->customer_id,['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'-- Select Customer --','id'=>'customer_id']) !!}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <b>@lang('app.search_by_loan_no')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{ Form::text('search',$request->search,['class' => 'form-control','placeholder' => __('app.search_by_loan_no')]) }}
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
                            </div>
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
                            <h3>{{ trans('app.customer_loan_statement_report') }}</h3>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-sm-12 ">
                            <p>Date: {{date('d-M-Y H:i:s')}} </p>
                            <p>Print By: {{Auth::user()->name}}</p>
                        </div>
                    </div> --}}
                    <div class="row" style="text-align: left;">
                        <div class="col-sm-6">
                            <p>@lang('app.from_date'): {{ date("d-M-Y", strtotime($date_from))}} </p>
                        </div>
                        <div class="col-sm-6" style="text-align: right;">
                            <p>@lang('app.to_date'): {{ date("d-M-Y", strtotime($date_to))}} </p>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div id="responsive" class="table table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="head_center">
                                            <th>@lang('app.no_en')</th>
                                            <th>@lang('app.loan_no')</th>
                                            <th>@lang('app.customer_name_en')</th>
                                            <th>@lang('app.phone')</th>
                                            <th>@lang('app.date_en')</th>
                                            <th>@lang('app.status')</th>
                                            @php($sum_by_bank = [])
                                            @foreach ($bank as $banks)
                                                @php($sum_by_bank[$banks->id] = 0)
                                                <th>{{ $banks->name }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php($credit=0)
                                    @php($debit=0)
                                    @php($balance=0)
                                    @php($statement=0)
                                    @php($last_balance=0)
                                    @forelse($bankchannel as $key => $row)
                                        <tr>
                                            <td style="text-align:center;">{{ ++ $key}}</td>
                                            <td>{{ $row->inv_no??"" }}</td>
                                            <td>{{ isset($row->cus_name)?$row->cus_name:'N/A' }} | {{ isset($row->cus_namekh)?$row->cus_namekh:$row->cus_name }}</td>
                                            <td>{{ isset($row->cus_phone)?$row->cus_phone:'N/A' }}</td>
                                            <td  style="text-align:center;">{{ date('d-m-Y',strtotime($row->date)) }}</td>
                                            <td  style="text-align:center;">{{ ucfirst($row->status) }}</td>
                                            @foreach ($bank as $banks_row)                                                
                                                @if($row->bank_id == $banks_row->id)
                                                    @php($sum_by_bank[$banks_row->id] += $row->amount_usd)
                                                    <td class="@if($row->amount_usd < 0) {{ 'text_red' }} @endif" style="text-align: right;">{{ $row->amount_usd }}</td>
                                                @else
                                                    <td style="text-align: right;"></td>
                                                @endif                                                
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">@lang('app.data_not_found')</td>
                                        </tr>
                                    @endforelse
                                    <tr style="background-color: #ddd;">
                                        <th colspan="6" style="text-align: right;">@lang('app.total_en'):</th>
                                        @foreach ($bank as $banks)
                                            <th class="@if($sum_by_bank[$banks->id] < 0) {{ 'text_red' }} @endif " style="text-align: right;">{{ isset($sum_by_bank[$banks->id])?number_format($sum_by_bank[$banks->id],2):0 }}</th>
                                        @endforeach
                                        {{-- <th class="@if($last_balance < 0) {{ 'text_red' }} @endif " style="min-width: 120px; text-align: right;">{{number_format($last_balance,2)}} $</th> --}}
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