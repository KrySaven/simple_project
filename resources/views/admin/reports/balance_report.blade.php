@extends('layouts.app')
@section('title','Balance Report')
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
                            <h3>BALANCE REPORT</h3>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col-sm-12 ">
                            <p>Date: {{date('d-M-Y H:i:s')}} </p>
                            <p>Print By: {{Auth::user()->name}}</p>
                        </div>
                    </div> --}}
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="table table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr class="head_center">
                                        <!-- <th>No</th> -->
                                        <th style="width: 50px !important;" class="no_width">No</th>
                                        <th>Customer Name</th>
                                        <th>Last Balance</th>
                                        <th>Loan</th>
                                        <th>Payback</th>
                                        <th>Balance</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $loan=0;
                                        $repay=0;
                                        $balance=0;
                                    @endphp
                                    @foreach($rows as $key => $row)
                                        @php
                                            $loan +=$row->loan;
                                            $repay +=$row->repay;
                                            $balance +=$row->balance;
                                           
                                        @endphp
                                        <tr>
                                            <td style="text-align:center;">{{ ++ $key}}</td>
                                            <td>{{ $row->name}}</td>
                                            <td class="@if($row->last_balance < 0) {{ 'text_red' }} @endif" style="text-align: right;">{{ number_format($row->last_balance,2) }}</td>
                                            <td class="@if($row->loan < 0) {{ 'text_red' }} @endif" style="text-align: right;">{{ number_format($row->loan,2) }} $</td>
                                            <td class="@if($row->repay < 0) {{ 'text_red' }} @endif" style="text-align: right;">{{ number_format($row->repay,2)}} $</td>
                                            <td class="@if($row->balance < 0) {{ 'text_red' }} @endif" style="text-align: right;">{{ number_format($row->balance,2)}} $</td>
                                        </tr>
                                    @endforeach
                                    <tr style="background-color: #ddd;">
                                        <th colspan="3" style="text-align: right;">Total:</th>
                                        <th class="@if($loan < 0) {{ 'text_red' }} @endif" style="min-width: 120px; text-align: right;">{{number_format($loan,2)}} $</th>
                                        <th class="@if($repay < 0) {{ 'text_red' }} @endif" style="min-width: 120px; text-align: right;">{{number_format($repay,2)}} $</th>
                                        <th class="@if($balance < 0) {{ 'text_red' }} @endif" style="min-width: 120px; text-align: right;">{{number_format($balance,2)}} $</th>
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