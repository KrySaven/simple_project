@extends('layouts.app')
@section('title',__('app.loan_report'))
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
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>@lang('app.search')</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            {!! Form::open(array('route' => 'sales_report' , 'method' => 'GET')) !!}
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
                                <b>@lang('app.co')</b>
                                <div class="form-group">
                                    {!! Form::select('co_id', $co_users,$request->co_id,['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'-- Select Co --','id'=>'co_id']) !!}
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
                <div class="card" id="printarea" style="padding: 10px;">
                    <div class="row">
                        <div class="col-sm-12" style="text-align: center;">
                            <h3>@lang('app.loan_report')</h3>
                        </div>
                    </div>
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
                            <div class="table table-responsive">
                                <table class="table table-bordered" style="white-space: nowrap;">
                                    <thead>
                                    <tr class="head_center">
                                        <!-- <th>No</th> -->
                                        <th style="width: 40px !important;" class="no_width">No</th>
                                        <th>@lang('app.loan_no')</th>
                                        <th>@lang('app.customer')</th>
                                        <th>@lang('app.co')</th>
                                        <th>@lang('app.loan_date')</th>
                                        <th>@lang('app.principle')</th>
                                        <th>@lang('app.interest_rate')</th>
                                        <th class="hidden">@lang('app.interest')</th>
                                        <th>@lang('app.saving_label')</th>
                                        <th>@lang('app.fee_charge')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $sum_percentage = 0;
                                        $sale_paid = 0;
                                        $sale_notpaid = 0;
                                        $sale_interest = 0;
                                        $total_amount_usd=0;
                                        $sum_price = 0;
                                        $sum_deposit = 0;
                                        $sum_total = 0;
                                        $sum_interest = 0;
                                        $sum_p_paid = 0;
                                        $sum_p_notpaid = 0;
                                        $sum_p_amount = 0;
                                        $sum_amount = 0;
                                        $sum_amounts = 0;
                                        $sum_insurance = 0;
                                        $currencySymbol = '$';
                                    @endphp
                                    @forelse($rows as $key => $row)
                                        @php
                                            $sum_percentage = $row->payment->where('status','=','paid')->sum('percentage');
                                            $sum_amount = $row->payment->sum('total');
                                            $sale_paid = $row->payment->where('status','=','paid')->sum('amount');
                                            $sale_notpaid = $row->payment->where('status','=','unpaid')->sum('amount');
                                            $sale_interest = $row->payment->sum('interest');
                                            $total_amount_usd +=$row->amount;
                                            $sum_price += $row->price;
                                            $sum_deposit += $row->deposit;
                                            $sum_total += $row->total;
                                            $sum_interest += $sale_interest;
                                            $sum_p_paid += $sale_paid;
                                            $sum_p_notpaid += $sale_notpaid;
                                            $sum_p_amount += $sale_paid + $sale_notpaid;
                                            $sum_amounts += $sum_amount;
                                            $sum_insurance += $row->saving;
                                            if($row->currency_type == 'riel'){
                                                $currencySymbol = '៛';
                                            }
                                        @endphp
                                        <tr>
                                            <td style="text-align:center;">{{ ++ $key}}</td>
                                            <td>{{ $row->inv_no??"" }}</td>
                                            <td>{{ isset($row->customer->name)?$row->customer->name : 'N/A' }}</td>
                                            <td>{{ $row->CoUser->name??"" }}</td>
                                            <td>{{ date('d-M-Y',strtotime($row->date)) }}</td>
                                            <td>{{ number_format($row->total,2) }} {{ $currencySymbol }}</td>
                                            <td>{{ number_format($row->interest,2) }} %</td>
                                            <td class="hidden">{{ number_format($sale_interest,2) }} {{ $currencySymbol }}</td>
                                            <td>{{ number_format($row->saving,2) }} {{ $currencySymbol }}</td>
                                            <td>{{ number_format($row->admin_fee,2) }} %</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">@lang('app.data_not_found')</td>
                                        </tr>
                                    @endforelse
                                        <tr style="background-color: #ddd;">
                                            <th colspan="5" style="text-align: right !important;">@lang('app.total_en'):</th>
                                            <th>{!! number_format($sum_total,2) !!} {{ $currencySymbol }}</th>
                                            <th></th>
                                            <th>{!! number_format($sum_insurance,2) !!} {{ $currencySymbol }}</th>
                                            {{-- <th>{!! number_format($sum_p_paid,2) !!} {{ $currencySymbol }}</th> --}}
                                            <th></th>
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