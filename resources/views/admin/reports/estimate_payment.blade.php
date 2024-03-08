@extends('layouts.app')
@section('title',__('app.estimate_payment_report'))
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
                        <h2>@lang('app.estimate_payment_report')</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            {!! Form::open(array('route' => 'estimate_payment' , 'method' => 'GET','id'=>'submit_form')) !!}
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
                                    <div class="form-line">
                                        {!! Form::select('customer_id', $customer,$request->customer_id,['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>__('app.select_customer'),'id'=>'customer_id']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <b>Timeline</b>
                                <div class="form-group">
                                    <div class="form-line branchdea_holder">
                                        {!! Form::select('timeline_id', $timeline,$request->timeline_id,['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'-- Select Timeline --']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="text-align: right;">
                                    <button type="submit" class="btn bg-green waves-effect">
                                        <i class="material-icons">search</i>
                                        <span>@lang('app.search')</span>
                                    </button>
                                   {{--  <button type="button" id="btn_download" class="btn bg-green waves-effect">
                                        <i class="material-icons">file_download</i>
                                        <span>Exports</span>
                                    </button> --}}
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
                <button type="button" class="btn btn-success waves-effect pull-right print_data"
                        onclick="print_report()">
                    <i class="material-icons">print</i>
                    <span>PRINT</span>
                </button>
            </div>
            <div class="row clearfix">
                <div class="card" id="printarea" style="padding: 10px;">
                    <div class="row">
                        <div class="col-sm-12" style="text-align: center;">
                            <h3>{{ trans('app.estimate_payment_report') }}</h3>
                        </div>
                    </div>
                    <div class="row" style="text-align: left;">
                        <div class="col-sm-6">
                            <p>From Date: {{ date("d-M-Y", strtotime($date_from))}} </p>
                        </div>
                        <div class="col-sm-6" style="text-align: right;">
                            <p>To Date: {{ date("d-M-Y", strtotime($date_to))}} </p>
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
                                        <!-- <th>No</th> -->
                                        <th rowspan="2" style="width: 40px !important;" class="no_width">@lang('app.no')</th>
                                        <th rowspan="2">@lang('app.month_year')</th>
                                        <th style="white-space: nowrap;">@lang('app.total_payment')</th>
                                        <th style="white-space: nowrap;">@lang('app.interest')</th>
                                        <th style="white-space: nowrap;">@lang('app.capital')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php($sum_total = 0)
                                        @php($sum_interest = 0)
                                        @php($sum_amount = 0)
                                        @php($key=1)
                                        @foreach ($date_month_arr as $month)
                                            <tr>
                                                <td class="no_wrap" style="text-align:center;">{{ ++ $key}}</td>
                                                <td class="text-center">{{ date('F-Y',strtotime($month)) }}</td>
                                                    @php($total = isset($get_payment_data_arr[date('m-Y',strtotime($month))]['total'])?$get_payment_data_arr[date('m-Y',strtotime($month))]['total']:0)
                                                    @php($interest = isset($get_payment_data_arr[date('m-Y',strtotime($month))]['interest'])?$get_payment_data_arr[date('m-Y',strtotime($month))]['interest']:0)
                                                    @php($amount = isset($get_payment_data_arr[date('m-Y',strtotime($month))]['amount'])?$get_payment_data_arr[date('m-Y',strtotime($month))]['amount']:0)
                                                    @php($sum_total += $total)
                                                    @php($sum_interest += $interest)
                                                    @php($sum_amount += $amount)
                                                    <td class="text-right no_wrap">{{ isset($get_payment_data_arr[date('m-Y',strtotime($month))]['total'])?$get_payment_data_arr[date('m-Y',strtotime($month))]['total']:'' }}</td>
                                                    <td class="text-right no_wrap">{{ isset($get_payment_data_arr[date('m-Y',strtotime($month))]['interest'])?$get_payment_data_arr[date('m-Y',strtotime($month))]['interest']:'' }}</td>
                                                    <td class="text-right no_wrap">{{ isset($get_payment_data_arr[date('m-Y',strtotime($month))]['amount'])?$get_payment_data_arr[date('m-Y',strtotime($month))]['amount']:'' }} </td>
                                                   
                                                
                                                
                                            </tr>
                                       @endforeach
                                    <tr style="background-color: #ddd;">
                                        <th></th>
                                        <th class="text-right">@lang('app.total')</th>
                                        <th class="text-right">{{ number_format($sum_total,2) }}</th>
                                        <th class="text-right">{{ number_format($sum_interest,2) }}</th>
                                        <th class="text-right">{{ number_format($sum_amount,2) }}</th>
                                        
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
<script type="text/javascript">
    $('#btn_download').click(function(){
        $('#submit_type').val('download');
        $("#submit_form").submit();
        $('#submit_type').val('');
        setTimeout(function () { $('.page-loader-wrapper').fadeOut(); }, 500);
    });
</script>
@stop
