@php
    $currencySymbol = LoanHelper::currencySymbol(!empty($loan)?$loan->currency_type:'')
@endphp
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
        .no_margin_bottom{
            margin-bottom: 0px !important;
        }
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
                            {!! Form::open(array('route' => ['customer_loan_statement_report'] , 'method' => 'GET','id' => 'form_submit')) !!}
                            @include('admin.includes.error')
                            {{-- <div class="col-sm-2">
                                <b>@lang('app.from_date')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_from',(isset($date_from) ? $date_from :null),['class'=>'form-control datetime','placeholder'=>__('app.from_date'),'id'=>'date_from'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <b>@lang('app.to_date')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_to',(isset($date_to) ? $date_to : null),['class'=>'form-control','placeholder'=>__('app.to_date'),'id'=>'date_to'])}}
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-sm-10 no_margin_bottom">
                                <b>@lang('app.search_by_loan_no')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{ Form::text('search',$request->search,['class' => 'form-control','placeholder' => __('app.search_by_loan_no')]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 no_margin_bottom">
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
                    <div class="row" style="text-align: left;">
                        <div class="col-sm-6">
                            <p>@lang('app.branch'): {{ $loan->branch->name_en??""}}</p>
                        </div>
                        <div class="col-sm-6" style="text-align: left;">
                            <p>@lang('app.customer_name_en'): {{ $loan->customer->name??"" }}</p>
                        </div>
                    </div>
                    <div class="row" style="text-align: left;">
                        <div class="col-sm-6">
                            <p>@lang('app.co_name'): {{ $loan->coUser->name??"" }}</p>
                        </div>
                        <div class="col-sm-6" style="text-align: left;">
                            <p>@lang('app.loan_date'): {{ !empty($loan) ? date('d-m-Y', strtotime($loan->date??date('d-m-Y'))) : "" }}</p>
                        </div>
                    </div>
                    <div class="row" style="text-align: left;">
                        <div class="col-sm-6">
                            <p>@lang('app.monthly_date'): {{ !empty($loan) ? date('d-m-Y',strtotime($loan->first_payment??date('d-m-Y'))) : "" }}</p>
                        </div>
                        <div class="col-sm-6" style="text-align: left;">
                            <p>@lang('app.interest_rate'): {{ !empty($loan) ? $loan->interest.' %' : '' }}</p>
                        </div>
                    </div>
                    <div class="row" style="text-align: left;">
                        <div class="col-sm-6">
                            <p>@lang('app.loan_term'): {!! $loan->LoanTerm??"" !!}</p>
                        </div>
                        <div class="col-sm-6" style="text-align: left;">
                            <p>@lang('app.loan_amount'): {{ !empty($loan)?number_format($loan->total??0,2):'' }} {{ !empty($loan)?$currencySymbol:'' }}</p>
                        </div>
                    </div>
                    <div class="row" style="text-align: left;">
                        <div class="col-sm-12">
                            <p>@lang('app.acc_status'): <b>{{ !empty($loan) ? (ucfirst($loan->approve_status == "approved" ? "active" : $loan->approve_status)) : "" }}</b></p>
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
                                            {{-- <th>@lang('app.payment_date')</th> --}}
                                            <th>@lang('app.actual_date_en')</th>
                                            <th>@lang('ប្រវត្តិសងប្រាក់')</th>
                                            <th>@lang('app.principle')</th>
                                            <th>@lang('app.interest')</th>
                                            <th>@lang('app.saving_label')</th>
                                            <th>@lang('app.total')</th>
                                            <th>@lang('app.actual_principle')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $actual_principle = !empty($loan) ? $loan->total * -1 :0;
                                        @endphp
                                        @forelse($payment_transaction as $item)
                                                @php
                                                    $actual_principle = $actual_principle + $item->principle;
                                                    $date1 = date_create_from_format('Y-m-d', date('Y-m-d', strtotime($item->date)));
                                                    $date2 = date_create_from_format('Y-m-d', date('Y-m-d', strtotime($item->payment_date)));
                                                    $diff  = date_diff($date1, $date2);
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->loan_no??"" }}</td>
                                                    {{-- <td></td> --}}
                                                    <td>{{ date('d-m-Y',strtotime($item->date??date('d-m-Y'))) }}</td>
                                                    @if ($date1 >= $date2)
                                                        <td>-{{ $diff->days  }}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    <td>{{ number_format($item->principle??0,2) }} {{ !empty($loan)?$currencySymbol:'' }}</td>
                                                    <td>{{ number_format($item->interest??0,2) }} {{ !empty($loan)?$currencySymbol:'' }}</td>
                                                    <td>{{ number_format($item->insurance??0,2) }} {{ !empty($loan)?$currencySymbol:'' }}</td>
                                                    <td>{{ number_format($item->pay_amount??0,2) }} {{ !empty($loan)?$currencySymbol:'' }}</td>
                                                    <td>{{ number_format($actual_principle,2) }} {{ !empty($loan)?$currencySymbol:'' }}</td>
                                                </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">@lang('app.data_not_found')</td>
                                            </tr>
                                        @endforelse
                                        <tr style="background: #ddd !important;">
                                            <th colspan="4" class="text-right">@lang('app.amount_due_priciple')</th>
                                            <th>{{ number_format($actual_principle,2) }} {{ !empty($loan)?$currencySymbol:'' }}</th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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