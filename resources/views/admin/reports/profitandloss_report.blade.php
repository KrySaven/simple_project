@php
    $siteprofile = Session::get('siteprofile');
@endphp
@extends('layouts.app')
@section('title',__('app.profit_and_loss_report'))
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
            }
            .table-bordered tbody tr td {
                padding: 5px;
                border: 1px solid #000 !important;
                font-size: 10px !important;
            }
            .table-bordered thead tr th {
                padding: 5px;
                border: 1px solid #000 !important;
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
                            {!! Form::open(array('route' => 'profitandloss_report' , 'method' => 'GET')) !!}
                            @include('admin.includes.error')
                            <div class="col-sm-5">
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
                            <h3>{{ trans('app.profit_and_loss_report') }}</h3>
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
                            <div class="table table-responsive" id="responsive">
                                <table class="table table-bordered" style="white-space: nowrap;">
                                    <thead>
                                    <tr class="head_center">
                                        <th>Profit And Loss (P&L) Statement</th>
                                        @foreach ($date_month_arr as $month)
                                            <th>{{ date('M-Y',strtotime($month)) }}</th>
                                        @endforeach
                                        <th>@lang('app.amount')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php($total_net = 0)
                                        @php($total_net_arr = [])
                                        <tr>
                                            <th>@lang('app.revenue')</th>
                                            @foreach ($date_month_arr as $month)
                                                @php($total_net_arr[date('mY',strtotime($month))] = 0)
                                                <th></th>
                                            @endforeach
                                            <th></th>
                                        </tr>
                                        {{-- <tr>
                                            <td style="padding-left: 20px;">+ Loan Investment</td>
                                            @php($total_invest = 0)
                                            @foreach ($date_month_arr as $month)
                                                    @php($invest = isset($profitandloss_arr[date('mY',strtotime($month))]['loan'])?$profitandloss_arr[date('mY',strtotime($month))]['loan']['sum_amount_riel']:0)
                                                    @php($total_invest += $invest)
                                                    @php($total_net_arr[date('mY',strtotime($month))] += $invest)
                                                    <td style="text-align: right;" class="@if($invest < 0) {{ 'text_red' }} @endif">{{ number_format($invest,2) }}</td>
                                            @endforeach
                                            @php($total_net += $total_invest)
                                            <th style="text-align: right;" class="@if($total_invest < 0) {{ 'text_red' }} @endif">{{ number_format($total_invest,2) }}</th>
                                        </tr> --}}

                                        {{-- principle --}}
                                        {{-- <tr>
                                            <td style="padding-left: 20px;">+ @lang('app.principle') Payment</td>
                                            @php($total_payment = 0)
                                            @foreach ($date_month_arr as $month)
                                                @php($payment = isset($profitandloss_arr[date('mY',strtotime($month))]['principle'])?$profitandloss_arr[date('mY',strtotime($month))]['principle']['sum_amount_riel']:0)
                                                @php($total_payment += $payment)
                                                @php($total_net_arr[date('mY',strtotime($month))] += $payment)
                                                <td style="text-align: right;" class="@if($payment < 0) {{ 'text_red' }} @endif">{{ number_format($payment,2) }}</td>
                                            @endforeach
                                            @php($total_net += $total_payment)
                                            <th style="text-align: right;" class="@if($total_payment < 0) {{ 'text_red' }} @endif">{{ number_format($total_payment,2) }}</th> --}}
                                        </tr>
                                        
                                        {{-- <tr>
                                            <td style="padding-left: 20px;">+ Last Over Payment</td>
                                            @php($total_payment = 0)
                                            @foreach ($date_month_arr as $month)
                                                @php($payment = isset($profitandloss_arr[date('mY',strtotime($month))]['used_over_payment'])?$profitandloss_arr[date('mY',strtotime($month))]['used_over_payment']['sum_amount_riel']:0)
                                                @php($total_payment += $payment)
                                                @php($total_net_arr[date('mY',strtotime($month))] += $payment)
                                                <td style="text-align: right;" class="@if($payment < 0) {{ 'text_red' }} @endif">{{ number_format($payment,2) }}</td>
                                            @endforeach
                                            @php($total_net += $total_payment)
                                            <th style="text-align: right;" class="@if($total_payment < 0) {{ 'text_red' }} @endif">{{ number_format($total_payment,2) }}</th>
                                        </tr> --}}
                                        <tr>
                                            <td style="padding-left: 20px;">+ @lang('app.interest')</td>
                                            @php($total_payment_inr = 0)
                                            @foreach ($date_month_arr as $month)
                                                @php($payment_inr = isset($profitandloss_arr[date('mY',strtotime($month))]['interest'])?$profitandloss_arr[date('mY',strtotime($month))]['interest']['sum_amount_riel']:0)
                                                @php($total_payment_inr += $payment_inr)
                                                @php($total_net_arr[date('mY',strtotime($month))] += $payment_inr)
                                                <td style="text-align: right;" class="@if($payment_inr < 0) {{ 'text_red' }} @endif">{{ number_format($payment_inr,2) }}</td>
                                            @endforeach
                                            @php($total_net += $total_payment_inr)
                                            <th style="text-align: right;" class="@if($total_payment_inr < 0) {{ 'text_red' }} @endif">{{ number_format($total_payment_inr,2) }}</th>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 20px;">+ @lang('app.saving_label')</td>
                                            @php($total_payment_inr = 0)
                                            @foreach ($date_month_arr as $month)
                                                @php($payment_inr = isset($profitandloss_arr[date('mY',strtotime($month))]['saving'])?$profitandloss_arr[date('mY',strtotime($month))]['saving']['sum_amount_riel']:0)
                                                @php($total_payment_inr += $payment_inr)
                                                @php($total_net_arr[date('mY',strtotime($month))] += $payment_inr)
                                                <td style="text-align: right;" class="@if($payment_inr < 0) {{ 'text_red' }} @endif">{{ number_format($payment_inr,2) }}</td>
                                            @endforeach
                                            @php($total_net += $total_payment_inr)
                                            <th style="text-align: right;" class="@if($total_payment_inr < 0) {{ 'text_red' }} @endif">{{ number_format($total_payment_inr,2) }}</th>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 20px;">+ @lang('app.admin_fee')</td>
                                            @php($total_payment_inr = 0)
                                            @foreach ($date_month_arr as $month)
                                                @php($payment_inr = isset($profitandloss_arr[date('mY',strtotime($month))]['loan']['sum_admin_fee'])?$profitandloss_arr[date('mY',strtotime($month))]['loan']['sum_admin_fee']:0)
                                                @php($total_payment_inr += $payment_inr)
                                                @php($total_net_arr[date('mY',strtotime($month))] += $payment_inr)
                                                <td style="text-align: right;" class="@if($payment_inr < 0) {{ 'text_red' }} @endif">{{ number_format($payment_inr,2) }}</td>
                                            @endforeach
                                            @php($total_net += $total_payment_inr)
                                            <th style="text-align: right;" class="@if($total_payment_inr < 0) {{ 'text_red' }} @endif">{{ number_format($total_payment_inr,2) }}</th>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 20px;">+ @lang('app.penalty_en')</td>
                                            @php($total_payment = 0)
                                            @foreach ($date_month_arr as $month)
                                                @php($penalty = isset($profitandloss_arr[date('mY',strtotime($month))]['penalty'])?$profitandloss_arr[date('mY',strtotime($month))]['penalty']['sum_amount_riel']:0)
                                                @php($total_payment += $penalty)
                                                @php($total_net_arr[date('mY',strtotime($month))] += $penalty)
                                                <td style="text-align: right;" class="@if($penalty < 0) {{ 'text_red' }} @endif">{{ number_format($penalty,2) }}</td>
                                            @endforeach
                                            @php($total_net += $total_payment)
                                            <th style="text-align: right;" class="@if($total_payment < 0) {{ 'text_red' }} @endif">{{ number_format($total_payment,2) }}</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: right;"> Total Revenue</th>
                                            @foreach ($date_month_arr as $month)
                                                @php($total_net_by_month = isset($total_net_arr[date('mY',strtotime($month))])?$total_net_arr[date('mY',strtotime($month))]:0)
                                                <th style="text-align: right;" class="@if($total_net_by_month < 0) {{ 'text_red' }} @endif">{{ number_format($total_net_by_month,2) }}</th>
                                            @endforeach
                                            {{-- @dd($total_payment,$penalty,$total_net) --}}
                                            {{-- @foreach ($date_month_arr as $month)
                                                @php ( $sum_interest   = (float)$profitandloss_arr[date('mY',strtotime($month))]['interest']['sum_amount_riel'])
                                                @php ( $sum_insurance  = (float)$profitandloss_arr[date('mY',strtotime($month))]['loan']['sum_saving'])
                                                @php ( $sum_admin_fee  = (float)$profitandloss_arr[date('mY',strtotime($month))]['loan']['sum_admin_fee'] )
                                                @php ( $sum_peanlty    = (float)$profitandloss_arr[date('mY',strtotime($month))]['penalty']['sum_amount_riel'])
                                                @php ( $sum_revenue    = $sum_interst + $sum_insurance + $sum_admin_fee + $sum_penalty)
                                            @endforeach --}}
                                            <th></th>
                                        </tr>    
                                        {{-- <tr>
                                            <td style="padding-left: 20px;">+ Over Payment</td>
                                            @php($total_payment = 0)
                                            @foreach ($date_month_arr as $month)
                                                @php($penalty = isset($profitandloss_arr[date('mY',strtotime($month))]['over_payment'])?$profitandloss_arr[date('mY',strtotime($month))]['over_payment']['sum_amount_riel']:0)
                                                @php($total_payment += $penalty)
                                                @php($total_net_arr[date('mY',strtotime($month))] += $penalty)
                                                <td style="text-align: right;" class="@if($penalty < 0) {{ 'text_red' }} @endif">{{ number_format($penalty,2) }}</td>
                                            @endforeach
                                            @php($total_net += $total_payment)
                                            <th style="text-align: right;" class="@if($total_payment < 0) {{ 'text_red' }} @endif">{{ number_format($total_payment,2) }}</th>
                                        </tr> --}}
                                        @if ($group_expense->count() > 0)
                                            <tr>
                                                <th>@lang('app.expenses')</th>
                                                @foreach ($date_month_arr as $month)
                                                    <th></th>
                                                @endforeach
                                                <th></th>
                                            </tr>
                                            @foreach ($group_expense as $gr_ex)
                                                <tr>
                                                    <td style="padding-left: 20px;">+ {{ $gr_ex->group_name }}</td>
                                                    @php($total_expense = 0)
                                                    @foreach ($date_month_arr as $month)
                                                        @php($expense_amount = isset($enpenses_arr[date('mY',strtotime($month))]['expense'][$gr_ex->id])?$enpenses_arr[date('mY',strtotime($month))]['expense'][$gr_ex->id]['sum_amount_riel']:0)
                                                        @php($total_expense += $expense_amount)
                                                        @if(isset($total_expense_arr[date('mY',strtotime($month))]))
                                                            @php($total_expense_arr[date('mY',strtotime($month))] += $expense_amount)
                                                        @else
                                                            @php($total_expense_arr[date('mY',strtotime($month))] = $expense_amount)
                                                        @endif
                                                        @php($total_net_arr[date('mY',strtotime($month))] += $expense_amount)
                                                        <td style="text-align: right;" class="@if($expense_amount < 0) {{ 'text_red' }} @endif">{{ number_format($expense_amount,2) }}</td>
                                                    @endforeach
                                                    @php($total_net += $total_expense)
                                                    <th style="text-align: right;" class="@if($total_expense < 0) {{ 'text_red' }} @endif">{{ number_format($total_expense,2) }}</th>
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <th style="text-align: right;"> Total Expense</th>
                                            {{-- @foreach ($date_month_arr as $month)
                                                @php($expense_amount = isset($enpenses_arr[date('mY',strtotime($month))]['expense'][$gr_ex->id])?$enpenses_arr[date('mY',strtotime($month))]['expense'][$gr_ex->id]['sum_amount_riel']:0)
                                                <th style="text-align: right;">{{ $total_expense ??"" }}</th>
                                            @endforeach --}}
                                            {{-- @foreach ($date_month_arr as $month)
                                                @php($total_net_by_month = isset($enpenses_arr[date('mY',strtotime($month))])?$enpenses_arr[date('mY',strtotime($month))]:0)
                                                <th style="text-align: right;" class="@if($total_expense < 0) {{ 'text_red' }} @endif">{{ number_format($total_expense,2) }}</th>
                                            @endforeach --}}
                                            @php($sum_total_expense = 0)
                                            @php($total_expensess = 0)
                                            @foreach ($date_month_arr as $month)
                                                @foreach ($group_expense as $gr_ex)
                                                    @php($total_expensess = isset($total_expense_arr[date('mY',strtotime($month))])?$total_expense_arr[date('mY',strtotime($month))]:0)
                                                @endforeach
                                                @php($sum_total_expense += $total_expensess)
                                                <th style="text-align: right;" class="@if($total_expensess < 0) {{ 'text_red' }} @endif">
                                                    {{ number_format($total_expensess,2) }}
                                                </th>
                                            @endforeach
                                            <th style="text-align: right;" class="@if($total_expensess < 0) {{ 'text_red' }} @endif">
                                                {{ number_format($sum_total_expense,2) }}
                                            </th>
                                        </tr>

                                        {{-- Income --}}
                                        @if($siteprofile->is_income == 1)
                                            @if($groupincome->count() > 0)
                                                <tr>
                                                <th>@lang('app.income')</th>
                                                @foreach ($date_month_arr as $month)
                                                    <th></th>
                                                @endforeach
                                                <th></th>
                                            </tr>
                                            @foreach ($groupincome as $g_income)
                                                <tr>
                                                    <td style="padding-left: 20px;">+ {{ $g_income->name }}</td>
                                                    @php($total_income = 0)
                                                    @foreach ($date_month_arr as $month)
                                                            @php($income_amount = isset($incomes_arr[date('mY',strtotime($month))]['income'][$g_income->id])?$incomes_arr[date('mY',strtotime($month))]['income'][$g_income->id]['sum_amount_riel']:0)
                                                            @php($total_income += $income_amount)
                                                            @php($total_net_arr[date('mY',strtotime($month))] += $income_amount)
                                                            <td style="text-align: right;" class="@if($income_amount < 0) {{ 'text_red' }} @endif">{{ number_format($income_amount,2) }}</td>
                                                    @endforeach
                                                    @php($total_net += $total_income)
                                                    <th style="text-align: right;" class="@if($total_income < 0) {{ 'text_red' }} @endif">{{ number_format($total_income,2) }}</th>
                                                </tr>
                                            @endforeach
                                            @endif
                                        @endif
                                        <tr>
                                            <th>Sale Black List</th>
                                            @foreach ($date_month_arr as $month)
                                                <th></th>
                                            @endforeach
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <td style="padding-left: 20px;">+ Customer not pay</td>
                                            @php($total_payment_inr = 0)
                                            @foreach ($date_month_arr as $month)
                                                    @php($sale_black_list = isset($profitandloss_arr[date('mY',strtotime($month))]['sale_black_list'])?$profitandloss_arr[date('mY',strtotime($month))]['sale_black_list']['sum_amount_riel']:0)
                                                    @php($total_payment_inr += $sale_black_list)
                                                    @php($total_net_arr[date('mY',strtotime($month))] += $sale_black_list)
                                                    <td style="text-align: right;" class="@if($sale_black_list < 0) {{ 'text_red' }} @endif">{{ number_format($sale_black_list,2) }}</td>
                                            @endforeach
                                            @php($total_net += $total_payment_inr)
                                            <th style="text-align: right;" class="@if($total_payment_inr < 0) {{ 'text_red' }} @endif">{{ number_format($total_payment_inr,2) }}</th>
                                        </tr>
                                        <tr style="background-color: #dddddd !important;">
                                            <th style="text-align: right;">Net Profit:</th>
                                            @foreach ($date_month_arr as $month)
                                                @php($total_net_by_month = isset($total_net_arr[date('mY',strtotime($month))])?$total_net_arr[date('mY',strtotime($month))]:0)
                                                <th style="text-align: right;" class="@if($total_net_by_month < 0) {{ 'text_red' }} @endif">{{ number_format($total_net_by_month,2) }}</th>
                                            @endforeach
                                            <th style="text-align: right;" class="@if($total_net < 0) {{ 'text_red' }} @endif">{{ number_format($total_net,2) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- #END# Exportable Table -->
    </div>
@endsection
{{-- <script type="text/javascript">
    function print_report() {
        $('#printarea').printThis({
            importStyle: true,
            importCSS: true
        });
    }
</script> --}}