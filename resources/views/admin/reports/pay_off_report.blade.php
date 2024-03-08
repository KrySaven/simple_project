@extends('layouts.app')
@section('title',__('app.loan_pay_off_report'))
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
        .text_red {
            color: #F44336 !important;
        }
        .no_wrap{
            white-space: nowrap !important;
        }
        .table-bordered thead tr th {
            padding: 5px !important;
        }
        .table-bordered tbody tr td, .table-bordered tbody tr th {
            padding: 5px !important;
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
            .no_wrap{
                white-space: nowrap !important;
            }
            .table-bordered thead tr th {
                padding: 5px !important;
            }
            .table-bordered tbody tr td, .table-bordered tbody tr th {
                padding: 5px !important;
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
                            {!! Form::open(array('route' => 'pay_off_loan' , 'method' => 'GET','id'=>'submit_form')) !!}
                            {!! Form::hidden('submit_type',null,['id'=>'submit_type']) !!}
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
                                        {!! Form::text('loan_no',$request->loan_no??null,['class'=>'form-control','placeholder'=> __('app.search_by_loan_no'),'id'=>'loan_no']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="text-align: right;">
                                    <button type="submit" class="btn bg-green waves-effect">
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
                            <h3>{{ trans('app.loan_pay_off_report') }}</h3>
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
                            <div class="table-responsive" style="max-height: 60vh;">
                                <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                                </div>
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr class="head_center">
                                            <th style="width: 40px !important;" class="no_width">@lang('app.no')</th>
                                            <th>@lang('app.loan_no')</th>
                                            <th>@lang('app.customer')</th>
                                            <th>@lang('app.pay_off_by')</th>
                                            <th>@lang('app.date')</th>
                                            <th>@lang('app.principle')</th>
                                            <th>@lang('app.interest_schedule')</th>
                                            <th>@lang('app.balance')</th>
                                            <th>@lang('app.saving')</th>
                                            <th>@lang('app.penalty')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sum_principle = 0;
                                            $sum_t_interest = 0;
                                            $sum_balance = 0;
                                            $sum_insurance = 0;
                                            $sum_penalty = 0;
                                            $currency_type = "៛";
                                        @endphp
                                        @forelse($pay_off as $key => $pay_offs)
                                            @php
                                                $currency_type  = $pay_offs->loan->currency_type??"";
                                                $sum_principle  += $pay_offs->principle;
                                                $sum_t_interest += $pay_offs->interest;
                                                $sum_balance    += $pay_offs->balance;
                                                $sum_insurance  += $pay_offs->insurance;
                                                $sum_penalty    += $pay_offs->penalty;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pay_offs->Loan->inv_no??"" }}</td>
                                                <td>{{ $pay_offs->Loan->customer->name_kh??"" }}</td>
                                                <td>{{ MyHelper::ucfirst_unicode($pay_offs->pay_off_by??"") }}</td>
                                                <td>{{ date('d-M-Y',strtotime($pay_offs->date??"")) }}</td>
                                                <td class="text-right">{!! LoanHelper::khr_format_static($currency_type,$pay_offs->principle??"") !!}</td>
                                                <td class="text-right">{!! LoanHelper::khr_format_static($currency_type,$pay_offs->interest??"") !!}</td>
                                                <td class="text-right">{!! LoanHelper::khr_format_static($currency_type,$pay_offs->balance??"") !!}</td>
                                                <td class="text-right">{!! LoanHelper::khr_format_static($currency_type,$pay_offs->insurance??"") !!}</td>
                                                <td class="text-right">{!! LoanHelper::khr_format_static($currency_type,$pay_offs->penalty??"") !!}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="9">@lang('app.data_not_found')</td>
                                            </tr>
                                        @endforelse    
                                        <tr style="background-color: #ddd;">
                                            <th colspan="5" style="text-align: right !important;">@lang('app.total'):</th>
                                            <th style="text-align: right !important;">{!! LoanHelper::khr_format_static($currency_type,$sum_principle) !!}</th>
                                            <th style="text-align: right !important;">{!! LoanHelper::khr_format_static($currency_type,$sum_t_interest ) !!}</th>
                                            <th style="text-align: right !important;">{!! LoanHelper::khr_format_static($currency_type,$sum_balance ) !!}</th>
                                            <th style="text-align: right !important;">{!! LoanHelper::khr_format_static($currency_type,$sum_insurance ) !!}</th>
                                            <th style="text-align: right !important;">{!! LoanHelper::khr_format_static($currency_type,$sum_penalty ) !!}</th>
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
@stop
@section('javascript')
    <script>
        document.getElementById("submit_form").addEventListener("keydown", function(event) {
            if(event.keyCode === 13) {
                document.getElementById("submit_form").submit();
            }
        });
    </script>
@stop
