@extends('layouts.app')
@section('title','Sales Report')
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
        .info-box-4{
        	margin-top: 10px !important;
        	box-shadow: inset 1px 1px 10px 1px rgba(0, 0, 0, 0.2) !important;
        }
        
        
        .text_red{color: #F44336 !important;}
        @media (min-width: 1200px){
        	.col-lg-2{
	        	width: 14.25%;
	        	padding-right: 8px!important;
    			padding-left: 8px!important;
	        }
	        .col-md-2{
	        	width: 14.25%;
	        	padding-right: 8px!important;
    			padding-left: 8px!important;
	        }
            
        }
        .card{
        	margin-bottom: 5px !important;
        }
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
                /*font-family: 'Khmer OS Battambong';*/
            }
            .info-box-4{
            	margin-top: 10px !important;
            	box-shadow: inset 1px 1px 10px 1px rgba(0, 0, 0, 0.2) !important;
            }
            .list_date{
                width: 14.25% !important;
                padding-right: 8px!important;
                padding-left: 8px!important;
                page-break-after: always !important;
            }
            .print_back{
                background-color: #eaeaea !important;
                /*page-break-after: always !important;*/
            }
            .card .body {
                font-size: 12px !important;
            }
            .hide_print{
                display: none;
            }
            a[href]:after {
                content:none;
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
                        <h2>Search</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            {!! Form::open(array('route' => 'daily_report' , 'method' => 'GET')) !!}
                            <div class="col-sm-5">
                                <b>Month</b>
                                <div class="form-group">
                                    <div class="form-line branchdea_holder">
                                        {!! Form::select('month', $month_arr, ($month)?$month:null,['class'=>'form-control show-tick','data-live-search'=>'true']) !!}
                                    </div>
                                    @if ($errors->has('month'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="month-error" class="error" for="month">{{ $errors->first('month') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <b>Year</b>
                                <div class="form-group">
                                    <div class="form-line branchdea_holder">
                                        {!! Form::select('year', $year_arr, ($year)?$year:null,['class'=>'form-control show-tick','data-live-search'=>'true']) !!}
                                    </div>
                                    @if ($errors->has('year'))
                                        <span class="invalid-feedback" role="alert">
                                            <label id="year-error" class="error" for="year">{{ $errors->first('year') }}</label>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <button type="submit" class="btn bg-green waves-effect pull-right">
                                        <i class="material-icons">search</i>
                                        <span>Search</span>
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
                            <h4>{{ trans('app.daily_customer_pay_report') }} {{ strtoupper(date('F',strtotime($month.'-'.$month.'-01'))) }} {{ strtoupper(date('Y',strtotime($year))) }}</h4>
                            <hr>
                            {{-- <h5>DAILY SALES REPORT FOR {{ strtoupper(date('F',strtotime($month))) }} {{ strtoupper(date('Y',strtotime($year))) }}</h5> --}}
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
                            <p>From Date: {{ date("d-M-Y", strtotime($from_date))}} </p>
                        </div>
                        <div class="col-sm-6" style="text-align: right;">
                            <p>To Date: {{ date("d-M-Y", strtotime($to_date))}} </p>
                        </div>

                    </div> --}}
                    <div class="row clearfix">
                        @php
                            $munber_of_month = 0;
                            $days = date('D',strtotime($from_date));

                            $munber_of_month = isset($day_arr[$days])?$day_arr[$days]:0;
                        @endphp
                        @for ($a = 1; $a <= $munber_of_month; $a++)
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 list_date">
                                            
                            </div>
                        @endfor
                                @foreach ($date_month_arr as $date)
						            		{{-- expr --}}
                                    @php
                                    $url='javascript:void(0);';
                                    $target='';
                                        $bg = "bg-green";
                                        $day =date('D',strtotime($date));
                                        $days = date('D',strtotime('2019-08-01'));
                                        if(date('D',strtotime($date)) == 'Sat'){
                                            $bg = 'bg-orange';
                                        }else if (date('D',strtotime($date)) == 'Sun'){
                                            $bg = 'bg-red';
                                        }
                                        if(isset($date_one_month_arr[$date]['payment_no_id'])){
                                            $url=url('invoice_daily_report/?date_from='.$date.'&date_to='.$date.'');
                                            $target='_blank';
                                        }
                                    @endphp
                                        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6 list_date">
                                            <div class="card">
                                                <div class="header {{ $bg }}" style="padding: 5px !important;">
                                                    <h2 style="font-size: 14px;">
                                                        {{ date('l',strtotime($date)) }} - {{ date('d',strtotime($date)) }}
                                                    </h2>
                                                    <ul class="header-dropdown m-r--5" style="top: 4px;">
                                                        <li>
                                                            <a class="hide_print" href="{{ $url }}" target="{{ $target }}" data-toggle="cardloading" data-loading-effect="rotation" data-loading-color="lightGreen">
                                                                <i class="material-icons">remove_red_eye</i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="body print_back" style="padding: 5px !important;">
                                                    <table style="width: 100%;">
                                                        <tbody>
                                                            {{-- <tr>
                                                                <td colspan="2" style="text-align: right;"><b>
                                                                    {{ isset($date_one_month_arr[$date]['payment_no_id'])?$date_one_month_arr[$date]['payment_no_id']:0 }}
                                                                </b></td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td style="width: 60%;">
                                                                    <span style="font-size:10px; ">No. Payment</span>
                                                                </td>
                                                                <td>: <span style="font-size: 10px;"><b>
                                                                    {{-- {{ isset($data_no_payment_arr[$date]['no_payment'])?$data_no_payment_arr[$date]['no_payment']:0 }} --}}
                                                                    {{ isset($date_one_month_arr[$date]['payment_no_id'])?$date_one_month_arr[$date]['payment_no_id']:0 }}
                                                                    </b></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%;">
                                                                    <span style="font-size:10px; ">Interest</span>
                                                                </td>
                                                                <td>: <span style="font-size: 10px;"><b>
                                                                    {{ isset($date_one_month_arr[$date]['sum_interest'])?$date_one_month_arr[$date]['sum_interest']:0 }}
                                                                </b></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%;">
                                                                    <span style="font-size:10px; ">Capital</span>
                                                                </td>
                                                                <td>: <span style="font-size: 10px;"><b>
                                                                    {{ isset($date_one_month_arr[$date]['sum_ammount'])?$date_one_month_arr[$date]['sum_ammount']:0 }}
                                                                </b></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%;">
                                                                    <span style="font-size:10px; ">Total</span>
                                                                </td>
                                                                <td>: <span style="font-size: 10px;"><b>
                                                                    {{ isset($date_one_month_arr[$date]['sum_total'])?$date_one_month_arr[$date]['sum_total']:0 }}
                                                                </b></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="width: 50%;">
                                                                    <span style="font-size:10px; ">B: <b>{{ isset($date_one_month_arr[$date]['bank_amount'])?$date_one_month_arr[$date]['bank_amount']:0 }}</b></span>
                                                                </td>
                                                                <td style="width: 50%;">
                                                                    <span style="font-size:10px; ">C: <b>{{ isset($date_one_month_arr[$date]['cash_amount'])?$date_one_month_arr[$date]['cash_amount']:0 }}</b></span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
						            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection