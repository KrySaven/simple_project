@extends('layouts.app')
@section('title',__('app.payment_report'))
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
                            {!! Form::open(array('route' => 'sale_dialy_payment_report' , 'method' => 'GET','id' => 'form_submit')) !!}
                            {{-- <div class="col-sm-2">
                                <b>@lang('app.from_date')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_from',(isset($date_from) ? $date_from :null),['class'=>'form-control datetime','placeholder'=>'From Date','id'=>'date_from'])}}
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-sm-2">
                                <b>@lang('app.date')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_to',(isset($date_to) ? $date_to : null),['class'=>'form-control','placeholder'=>'To Date','id'=>'date_to'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <b>@lang('app.customer')</b>
                                <div class="form-group">
                                    {!! Form::select('customer_id', $customer,$request->customer_id,['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=> __('app.select_customer'),'id'=>'customer_id']) !!}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <b>@lang('app.co')</b>
                                <div class="form-group">
                                    {!! Form::select('co_id', $co_users,$request->co_id,['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=> __('app.co'),'id'=>'co_id']) !!}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <b>@lang('app.search_by_loan_no')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::text('loan_no',$request->loan_no??null,['class'=>'form-control','placeholder'=> __('app.search_by_loan_no'),'id'=>'loan_no']) !!}
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
                            <h3>{{ trans('app.dialy_payment_report') }}</h3>
                        </div>
                    </div>
                    <div class="row" style="text-align: left;">
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
                                            <th style="width: 40px !important;" class="no_width">@lang('app.no_en')</th>
                                            <th>@lang('app.loan_no')</th>
                                            <th>@lang('app.loan_date')</th>
                                            <th>@lang('app.customer')</th>
                                            {{-- <th>@lang('app.payment_date')</th> --}}
                                            <th>@lang('app.date')</th>
                                            <th>@lang('app.paid_by')</th>
                                            <th>@lang('app.principle')</th>
                                            <th>@lang('app.interest')</th>
                                            <th>@lang('app.saving_label')</th>
                                            <th>@lang('Balance')</th>
                                            <th>@lang('app.advance_fine')</th>
                                            <th>@lang('app.total')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Total
                                            $total_amount_usd = 0;
                                            $sum_total = 0;
                                            $sum_interest = 0;
                                            $sum_t_insurance = 0;
                                            $sum_balance = 0;
                                            $sum_advance_fine =0;
                                            $currencySymbol = '៛';
                                        @endphp
                                        @forelse($rows as $key => $row)
                                            @php
                                                $currencySymbol = LoanHelper::currencySymbol($row->currency_type); 
                                                // Total 
                                                $total_amount_usd   += $row->principle;
                                                $sum_interest       += $row->interest;
                                                $sum_t_insurance    += $row->insurance;
                                                $sum_balance        += $row->balance;
                                                $sum_advance_fine   += $row->advance_fine;
                                                if ($row->status=='payoff') {
                                                    $sum_total = $sum_interest + $total_amount_usd +$sum_balance + $sum_t_insurance + $sum_advance_fine;
                                                }else {
                                                    $sum_total += $row->pay_amount;
                                                }
                                            @endphp
                                            <tr>
                                                <td style="text-align:center;">{{ ++ $key }}</td>
                                                <td>{{ $row->loan_no }}</td>
                                                <td>{{ date('d-M-Y',strtotime($row->loan_date??null)) }}</td>
                                                <td>{{ $row->cus_name??'N/A' }}</td>
                                                {{-- <td>{{ date('d-M-Y',strtotime($row->date)) }}</td> --}}
                                                <td>{{ date('d-M-Y',strtotime($row->date)) }}</td>
                                                <td>{{ $row->paid_by??"N/A" }}</td>
                                                <td>{{ number_format($row->principle,2) }} {{ $currencySymbol }}</td>
                                                <td>{{ number_format($row->interest,2) }} {{ $currencySymbol }}</td>
                                                <td>{{ number_format($row->insurance,2) }} {{ $currencySymbol }}</td>
                                                <td class="balance">{{ number_format($row->balance,2) }} {{ $currencySymbol }}</td>
                                                <td style="text-align: left !important">{{ number_format($row->advance_fine,2)}} {{ $currencySymbol }}</td>
                                                <td>{{ number_format($row->pay_amount,2) }} {{ $currencySymbol }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">@lang('app.data_not_found')</td>
                                            </tr>
                                        @endforelse
                                        <tr style="background-color: #ddd;">
                                            <th colspan="6" style="text-align: right !important;">@lang('app.total'):</th>
                                            <th>{{ number_format($total_amount_usd,2) }}  {{ $currencySymbol }}</th>
                                            <th>{{ number_format($sum_interest,2) }}  {{ $currencySymbol }}</th>
                                            <th>{{ number_format($sum_t_insurance,2) }} {{ $currencySymbol }}</th>
                                            <th>{{ number_format($sum_balance,2) }} {{ $currencySymbol }}</th>
                                            <th>{{number_format($sum_advance_fine,2)}} {{ $currencySymbol }}</th>
                                            <th>{{ number_format($sum_total,2) }}  {{ $currencySymbol }}</th>  
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
@push('scripts')
    <script>
        document.getElementById("form_submit").addEventListener("keydown", function(event) {
            if(event.keyCode === 13) {
                document.getElementById("form_submit").submit();
            }
        });
    </script>
@endpush