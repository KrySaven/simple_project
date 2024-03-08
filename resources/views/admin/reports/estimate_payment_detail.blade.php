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
                        <h2>
                            Search
                        </h2>

                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            {!! Form::open(array('route' => 'estimate_payment_detail' , 'method' => 'GET','id'=>'submit_form')) !!}
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
                            <h3>ESTIMATE PAYMENT DETAIL REPORT</h3>
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
                            <div class="table-responsive responsive" style="max-height: 60vh;">
                                <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                                </div>
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                    <tr class="head_center" style="background-color: #dddddd !important;">
                                        <th style="width: 50px !important;text-align: center;" class="no_width">No</th>
                                        {{-- <th style="white-space: nowrap;">Product Name</th> --}}
                                        {{-- <th style="white-space: nowrap;">Serial</th> --}}
                                        <th style="white-space: nowrap;">Customer</th>
                                        <th style="white-space: nowrap;">Total Payment</th>
                                        <th style="white-space: nowrap;">Interest</th>
                                        <th style="white-space: nowrap;">Capital</th>
                                    </tr>
                                    </thead>
                                        @php($i=1)
                                        @php($sum_total = 0)
                                        @php($sum_interest = 0)
                                        @php($sum_amount = 0)
                                        @foreach ($rows as $row)
                                            @php($sum_total += $row->total)
                                            @php($sum_interest += $row->interest)
                                            @php($sum_amount += $row->amount)
                                            <tr>
                                                <td style="text-align: center !important;">{{ $i++ }}</td>
                                                {{-- <td style="text-align: left !important;">{{ $row->product_name }}</td> --}}
                                                {{-- <td style="text-align: center !important;">{{ $row->serial }}</td> --}}
                                                <td style="text-align: left !important;">{{ $row->cus_name.' - '.$row->cus_phone }}</td>
                                                <td style="text-align: right !important;">{{ number_format($row->total,2) }}</td>
                                                <td style="text-align: right !important;">{{ number_format($row->interest,2) }}</td>
                                                <td style="text-align: right !important;">{{ number_format($row->amount,2) }}</td>
                                            </tr>
                                        @endforeach
                                    <tbody>
                                    <tr style="background-color: #dddddd !important;">
                                        <th style="text-align: right !important;" colspan="2">Total:</th>
                                        <th style="text-align: right !important;">{{ number_format($sum_total,2) }}</th>
                                        <th style="text-align: right !important;">{{ number_format($sum_interest,2) }}</th>
                                        <th style="text-align: right !important;">{{ number_format($sum_amount,2) }}</th>
                                        
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
