@extends('layouts.app')
@section('title','Customer Data Report')
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
            padding: 2px !important;
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
                        <h2>Search</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            {!! Form::open(array('route' => 'customer_data' , 'method' => 'GET','id'=>'submit_form')) !!}
                            {!! Form::hidden('submit_type',null,['id'=>'submit_type']) !!}
                            <div class="col-sm-2">
                                <b>From Date</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_from',(isset($date_from) ? $date_from :null),['class'=>'form-control datetime','placeholder'=>'From Date','id'=>'date_from'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <b>To Date</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_to',(isset($date_to) ? $date_to : null),['class'=>'form-control','placeholder'=>'To Date','id'=>'date_to'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <b>Customer</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::select('customer_id', $customer,$request->customer_id,['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'-- Select Customer --','id'=>'customer_id']) !!}
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
                                        <span>Search</span>
                                    </button>
                                    <button type="button" id="btn_download" class="btn bg-green waves-effect">
                                        <i class="material-icons">file_download</i>
                                        <span>Exports</span>
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
                            <h3>{{ trans('app.customer_data_report') }}</h3>
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
                                        <th rowspan="2" style="width: 40px !important;" class="no_width">No</th>
                                        <th rowspan="2">Sale Date</th>
                                        <th rowspan="2">Customer</th>
                                        <th rowspan="2">Customer Phone</th>
                                        <th rowspan="2">Dealer</th>
                                        {{-- <th rowspan="2">IEM</th>
                                        <th rowspan="2">Wing card number</th>
                                        <th rowspan="2">Original file</th>
                                        <th colspan="2">Commission</th>

                                        <th rowspan="2">Phone type</th>
                                        <th rowspan="2">Serial</th> --}}
                                        <th rowspan="2">Date of first payment</th>
                                        <th rowspan="2">Loan Amount</th>
                                        {{-- <th rowspan="2">Deposit</th> --}}
                                        <th rowspan="2">% Installment</th>
                                        <th rowspan="2">Interest Rate</th>
                                        <th rowspan="2">Installment Price </th>
                                        <th rowspan="2">Installment Period</th>
                                        <th rowspan="2">Remaining months</th>
                                        <th rowspan="2">Interest</th>
                                        <th rowspan="2">Total Capital and interest</th>
                                        <th rowspan="2">Monthly payment</th>
                                        @php($sum_total_arr = [])
                                        @php($sum_amount_arr = [])
                                        @php($sum_interest_arr = [])
                                        @foreach ($date_month_arr as $month)
                                            @php($sum_total_arr[date('mY',strtotime($month))] = 0)
                                            @php($sum_amount_arr[date('mY',strtotime($month))] = 0)
                                            @php($sum_interest_arr[date('mY',strtotime($month))] = 0)
                                            <th colspan="3" style="white-space: nowrap;">{{ date('M-Y',strtotime($month)) }}</th>
                                        @endforeach
                                    </tr>
                                    <tr class="head_center">
                                        {{-- <th>Bank</th>
                                        <th>Cash</th> --}}
                                        @foreach ($date_month_arr as $month)
                                            <th style="white-space: nowrap;">Monthly</th>
                                            <th style="white-space: nowrap;">Interest</th>
                                            <th style="white-space: nowrap;">Capital</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php($sum_percentage = 0)
                                        @php($sale_paid = 0)
                                        @php($sale_notpaid = 0)
                                        @php($sale_interest = 0)
                                        @php($total_amount_usd=0)
                                        @php($sum_price = 0)
                                        @php($sum_deposit = 0)
                                        @php($sum_total = 0)
                                        @php($sum_interest = 0)
                                        @php($sum_p_paid = 0)
                                        @php($sum_p_notpaid = 0)
                                        @php($sum_p_amount = 0)
                                        @php($sum_amount = 0)
                                        @php($sum_amounts = 0)
                                        @php($invest_sale =0)
                                        @php($sum_montly_payment =0)
                                    @foreach($rows as $key => $row)
                                            @php($sum_percentage = $row->payment->where('status','=','paid')->sum('percentage'))
                                            @php($sum_amount = $row->payment->sum('total'))
                                            @php($sale_paid = $row->payment->where('status','=','paid')->sum('amount'))
                                            @php($sale_notpaid = $row->payment->where('status','=','notpaid')->sum('amount'))
                                            @php($sale_interest = $row->payment->sum('interest'))
                                            @php($total_amount_usd +=$row->amount)
                                            @php($sum_price += $row->price)
                                            @php($sum_deposit += $row->deposit)
                                            @php($sum_total += $row->total)
                                            @php($sum_interest += $sale_interest)
                                            @php($sum_p_paid += $sale_paid)
                                            @php($sum_p_notpaid += $sale_notpaid)
                                            @php($sum_p_amount += $sale_paid + $sale_notpaid)
                                            @php($sum_amounts += $sum_amount)
                                            @php($invest_sale +=$row->total - $sale_paid)
                                            @php($installment = ($row->total /  $row->price)*100)
                                            @php($timeline_detail = $row->payment())
                                        @if($timeline_detail->count() > 0)
                                            @php($count_mont = $timeline_detail->count())
                                            @php($pay_permonth = $row->payment()->orderBY('no','DESC')->first()->total)
                                            @php($first_date_pay = $row->payment()->orderBY('no','ASC')->first()->payment_date)
                                            @php($last_date_pay = $row->last_payment_date()->first()->payment_date)
                                            @php($remaining_months  = $row->payment()->where('status','notpaid')->count())
                                            @php($payment_detail  = $row->payment()->get())
                                            @php($sum_montly_payment += $pay_permonth)
                                        @else
                                            @php($count_mont = 0)
                                            @php($pay_permonth = 0)
                                            @php($first_date_pay = '')
                                        @endif
                                        <tr>
                                            <td class="no_wrap" style="text-align:center;">{{ ++ $key}}</td>
                                            <td class="no_wrap">{{ date('d-M-Y',strtotime($row->date)) }}</td>
                                            <td  class="no_wrap">{{ isset($row->customer_name)?$row->customer_name : 'N/A' }}</td>
                                            <td  class="no_wrap">{{ isset($row->customer_phone)?$row->customer_phone : 'N/A' }}</td>
                                            <td  class="no_wrap">{{ isset($row->dealer_name)?$row->dealer_name : 'N/A' }}</td>
                                           {{--  <td>{{ $row->iem }}</td>
                                            <td></td>
                                            <td>{{ $row->original_file }}</td>
                                            @if($row->commission_type == 'bank')
                                                <td>{{ number_format($row->commission,2) }}</td>
                                                <td></td>
                                            @elseif($row->commission_type == 'cash')
                                                <td></td>
                                                <td>{{ number_format($row->commission,2) }}</td>
                                            @else
                                                <td></td>
                                                <td></td>
                                            @endif
                                            <td class="no_wrap">{{ $row->product_name }}</td>
                                            <td class="no_wrap">{{ $row->serial }}</td> --}}
                                            <td class="text-center no_wrap">{{ isset($first_date_pay)?date('d-M-Y',strtotime($first_date_pay)):'' }}</td>
                                            <td class="text-right no_wrap">{{ number_format($row->price,2) }} </td>
                                            {{-- <td class="text-right no_wrap">{{ number_format($row->deposit,2) }} </td> --}}
                                            <td class="text-right no_wrap">{{ number_format($installment,0) }} %</td>
                                            <td class="text-center no_wrap">{{ ($row->interest*1) }}%</td>
                                            <td class="text-right no_wrap">{{ number_format($row->total,2) }} </td>
                                            <th class="text-center no_wrap">{{ $count_mont }}</th>
                                            <th class="text-center no_wrap">{{ $remaining_months }}</th>
                                            <td class="text-right no_wrap">{{ number_format($sale_interest,2) }} </td>
                                            <td class="text-right no_wrap">{{  number_format($sum_amount,2) }} </td>
                                            <td class="text-right no_wrap">{{ number_format($pay_permonth,2) }} </td>
                                            @foreach ($date_month_arr as $month)
                                                @php($total_arr = isset($get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['total'])?$get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['total']:0)
                                                @php($amount_arr = isset($get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['amount'])?$get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['amount']:0)
                                                @php($interest_arr = isset($get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['interest'])?$get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['interest']:0)
                                                @php($sum_total_arr[date('mY',strtotime($month))] += $total_arr)
                                                @php($sum_amount_arr[date('mY',strtotime($month))] += $amount_arr)
                                                @php($sum_interest_arr[date('mY',strtotime($month))] += $interest_arr)
                                                <td class="text-right no_wrap">{{ isset($get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['total'])?$get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['total']:'' }}</td>
                                                <td class="text-right no_wrap" style="color: green;">{{ isset($get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['interest'])?$get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['interest']:'' }}</td>
                                                <td class="text-right no_wrap" style="color: red;">{{ isset($get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['amount'])?$get_payment_data_arr[date('m-Y',strtotime($month)).'-'.$row->id]['amount']:'' }} </td>
                                               
                                            @endforeach
                                            
                                        </tr>

                                    @endforeach
                                    <tr style="background-color: #ddd;">
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: right !important;">Total:</th>
                                        <th class="text-right no_wrap">{{ number_format($sum_price,2) }}</th>
                                        {{-- <th class="text-right no_wrap">{{ number_format($sum_deposit,2) }}</th> --}}
                                        <th></th>
                                        <th></th>
                                        <th class="text-right no_wrap">{{ number_format($sum_total,2) }}</th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right no_wrap">{{ number_format($sum_interest,2) }}</th>
                                        <th class="text-right no_wrap">{{ number_format($sum_amounts,2) }}</th>
                                        <th class="text-right no_wrap">{{ number_format($sum_montly_payment,2) }}$</th>
                                        @foreach ($date_month_arr as $month)
                                            @php($result_sum_total = isset($sum_total_arr[date('mY',strtotime($month))])?$sum_total_arr[date('mY',strtotime($month))]:0)
                                            @php($result_sum_amount = isset($sum_amount_arr[date('mY',strtotime($month))])?$sum_amount_arr[date('mY',strtotime($month))]:0)
                                            @php($result_sum_interest = isset($sum_interest_arr[date('mY',strtotime($month))])?$sum_interest_arr[date('mY',strtotime($month))]:0)
                                            <th class="text-right no_wrap">{{ $result_sum_total }}</th>
                                            <th class="text-right no_wrap" style="color: green;">{{ $result_sum_interest }} </th>
                                            <th class="text-right no_wrap" style="color: red;">{{ $result_sum_amount }}</th>
                                        @endforeach
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
