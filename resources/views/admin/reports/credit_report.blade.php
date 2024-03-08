@extends('layouts.app')
@section('title','Credits Report')
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
                        <h2>
                            Search
                        </h2>

                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            {!! Form::open(array('route' => 'credit_report' , 'method' => 'GET')) !!}
                            @include('admin.includes.error')
                            <div class="col-sm-3">
                                <b>From Date</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_from',(isset($date_from) ? $date_from :null),['class'=>'form-control datetime','placeholder'=>'From Date','id'=>'date_from'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <b>To Date</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_to',(isset($date_to) ? $date_to : null),['class'=>'form-control','placeholder'=>'To Date','id'=>'date_to'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <b>Customer</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {!! Form::select('customer_id', $customer,$request->customer_id,['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>'Select Customer','id'=>'customer_id']) !!}
                                    </div>
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
                <div class="card" id="printarea" style="padding: 15px;">
                    <div class="row">
                        <div class="col-sm-12" style="text-align: center;">
                            <h3>CREDITS REPORT</h3>
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
                            <div class="table table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr class="head_center">
                                        <!-- <th>No</th> -->
                                        <th style="width: 50px !important;" class="no_width">No</th>
                                        <th>Date</th>
                                        <th>Customer Name</th>
                                        <th>Add By</th>
                                        <th>Description</th>
                                        <th>Amount</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $total_amount_usd=0;
                                    @endphp
                                    @foreach($rows as $key => $row)
                                        @php
                                            $total_amount_usd +=$row->amount;
                                        @endphp
                                        <tr>
                                            <td style="text-align:center;">{{ ++ $key}}</td>
                                            <td>{{ date('d-M-Y',strtotime($row->date)) }}</td>
                                            <td>{{ isset($row->customer->name)?$row->customer->name:'N/A' }}</td>
                                            <td>{{ $row->add_by }}</td>
                                            <td>{{ $row->description }}</td>
                                            <td class="@if($row->amount < 0) {{ 'text_red' }} @endif" style="text-align: right;">{{ number_format($row->amount,2)}} $</td>
                                        </tr>
                                    @endforeach
                                    <tr style="background-color: #ddd;">
                                        <th colspan="5" style="text-align: right;">Total:</th>
                                        <th class="@if($total_amount_usd < 0) {{ 'text_red' }} @endif" style="min-width: 120px; text-align: right;">{{number_format($total_amount_usd,2)}} $</th>
                                        {{--<th>{{number_format($total_amount_riel,0)}} ៛</th>--}}

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