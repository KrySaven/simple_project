@extends('layouts.app')
@section('title','CO Report')
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
            @page{
                size: A4 landscape !important;
            }
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
                        <h2>Search</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            {!! Form::open(array('route' => 'co_report' , 'method' => 'GET')) !!}
                            @include('admin.includes.error')
                            <div class="col-sm-5">
                                <b>From Date</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{form::text('date_from',(isset($date_from) ? $date_from :null),['class'=>'form-control datetime','placeholder'=>'From Date','id'=>'date_from'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <b>To Date</b>
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
            {{-- <div class="row clearfix">
                <button type="button" class="btn btn-success waves-effect pull-right print_data"
                        onclick="print_report()">
                    <i class="material-icons">print</i>
                    <span>PRINT</span>
                </button>
            </div> --}}
            <div class="row clearfix">
                <div class="card" id="printarea" style="padding: 15px;">
                    <div class="row">
                        <div class="col-sm-12" style="text-align: center;">
                            <p style="font-family: Khmer OS Moul Light;"><b>សហគ្រាស ក្រេដីតអេតហ្វាយស៏</b></p>
                            <p><b>PawnShop Credit@Y</b></p>
                            {{-- <p style="font-family: Khmer OS Moul Light;"><b>ផលិតភាពការងារតាមបុគ្គលិកផ្នែកលក់ និងចំណាយសំវិធានធន</b></p>
                            <p>សាខា ស្អាង , ចាប់ពីថ្ងៃទី 01/07/2022 ដល់ថ្ងៃទី 31/07/2022 , រូបិយប័ណ្ណ ទាំងអស់ , ប្រភេទផលិតផល ទាំងអស់ , អត្រា 4099</p> --}}
                        </div>
                        {{-- <div class="col-sm-12" style="text-align: center;">
                            <h3>{{ trans('app.co_report') }}</h3>
                        </div> --}}
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
                            <div id="responsive" class="table table-responsive">
                                <table class="table1" style="width: 100% !important">
                                    <thead>
                                    <tr class="head_center">
                                        <th>No</th>
                                        <th id="papa">Staff Name</th>
                                        <th nowrap="nowrap">ចំនួនបញ្ចេញ</th>
                                        <th>ទឹកប្រាក់បញ្ចេញ</th>
                                        <th nowrap="nowrap">ចំនួនសកម្ម</th>
                                        <th>ទឹកប្រាក់សកម្ម</th>
                                        <th>កំរៃបញ្ចេញ</th>
                                        <th nowrap="nowrap">កំរៃបុនហិរញ្ញប្បទាន</th>
                                        {{-- <th nowrap="nowrap">ចំនួនប្រមូល</th> --}}
                                        <th>ប្រាក់ដើមប្រមូល</th>
                                        <th>ការប្រាក់ប្រមូល</th>
                                        <th nowrap="nowrap">ពិន័យបង់ផ្តាច់ប្រមូល</th>
                                        <th>ចំនួនយឺត1</th>
                                        <th>ឥណទានយឺត1</th>
                                        <th>PAR1</th>
                                        <th>ចំនួនយឺត30</th>
                                        <th>ឥណទានយឺត30</th>
                                        <th>PAR30</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @php($total = 0)
                                        @php($total_count = 0)
                                        @php($sum_loan_amount=0)
                                        @php($sum_admin_fee=0)
                                        @php($sum_financing_fee=0)
                                        @php($sum_collect_prin=0)
                                        @php($sum_collect_inter=0)
                                        @php($sum_collect_advance_fine=0)
                                        @php($sum_num_late_paid=0)
                                        @php($sum_late_un_paid_amount=0)
                                        @php($sum_num_late_more_thirty=0)
                                        @php($sum_amoun_unpaid_more_thirty=0)
                                        @php($sum_active_paid_amount=0)
                                        @php($sum_collect_saving =0)
                                        @php($total_count_sale =0)
                                    @foreach($co_report as $key => $row)
                                        @php($total                     += $row->total_sale)
                                        
                                        {{-- old count sales --}}
                                        {{-- @php($total_count               += $row->count_sale)  --}}
                                        {{-- new count sales --}}
                                        @php($total_count               += $count_sales_arr['count_sale'][$key])

                                        @php($total_count_sale          += $row->count_sale)
                                        @php($par_one                   = ($late_credit_arr['late_unpaid_amount'][$key]/$row->total_sale)*100)
                                        @php($par_thirty                = ($late_credit_arr['amount_late_unpaid_more_thirty'][$key]/$row->total_sale)*100)
                                        @php($sum_loan_amount           += $loan_amount_arr['total_loan_amount'][$key])
                                        @php($sum_admin_fee             += $admin_fee_arr['admin_fee'][$key])
                                        @php($sum_financing_fee         += (($financing_fee_arr[$key]*1)/100))
                                        @php($sum_collect_prin          += $collect_prin_and_inter_arr['collect_principle'][$key])
                                        @php($sum_collect_inter         += $collect_prin_and_inter_arr['collect_interest'][$key])
                                        @php($sum_collect_saving        += $collect_prin_and_inter_arr['collect_saving'][$key])
                                        @php($sum_collect_advance_fine  += $loan_amount_arr['collect_advance_fine'][$key])
                                        @php($sum_num_late_paid         += $late_credit_arr['num_late_unpaid'][$key])
                                        @php($sum_late_un_paid_amount   += $late_credit_arr['late_unpaid_amount'][$key])
                                        @php($sum_num_late_more_thirty  += $late_credit_arr['num_late_unpaid_more_thirty'][$key])

                                        {{-- Old Active amount --}}
                                        {{-- @php($sum_active_paid_amount    += ($row->total_sale-$active_amount_arr['active_paid_amount'][$key])) --}}
                                        {{-- New Active amount --}}
                                        @php($sum_active_paid_amount    += $count_sales_arr['sum_loan_amount'][$key] - ($paid_amount_arr['paid_amount'][$key]+$paid_amount_arr['partial_amount'][$key]))
                                        
                                        @php($sum_amoun_unpaid_more_thirty+= $late_credit_arr['amount_late_unpaid_more_thirty'][$key])
                                        <tr>
                                            <td style="text-align:center;">{{ ++ $key}}</td>
                                            <td nowrap="nowrap">{{ isset($row->name)?$row->name:'N/A' }}</td>
                                            <td style="text-align: center;">{{ $row->count_sale }}</td>
                                            {{-- <td style="text-align: right;">{{ number_format($loan_amount_arr['total_loan_amount'][$key-1],2) }}៛</td> --}}
                                            <td>{{ number_format($row->total_sale,2) }}៛</td>

                                            {{-- old count sale --}}
                                            {{-- <td style="text-align:center;">{{ $row->count_sale }}</td> --}}
                                            {{-- New count sales --}}
                                            <td style="text-align: center;">{{ $count_sales_arr['count_sale'][$key-1]}}</td>

                                            {{-- Old active amount --}}
                                            {{-- <td style="text-align:right;">{{ number_format( $row->total_sale-$active_amount_arr['active_paid_amount'][$key-1],2) }}៛</td> --}}
                                            {{-- New active amount --}}
                                            <td>{{ number_format($count_sales_arr['sum_loan_amount'][$key-1] - ($paid_amount_arr['paid_amount'][$key-1]+$paid_amount_arr['partial_amount'][$key-1]),2)}}៛</td>

                                            {{-- Old admin_fee --}}
                                            {{-- <td style="text-align:right;">{{ number_format($loan_amount_arr['admin_fee'][$key-1],2) }}៛ koko</td> --}}
                                            {{-- New admin_fee --}}
                                            <td>{{ number_format($admin_fee_arr['admin_fee'][$key-1],2) }}៛</td>

                                            {{-- <td>{{number_format(($financing_fee_arr[$key-1]*1)/100,2)}} ៛</td> --}}
                                            <td>0.00 ៛</td>

                                            {{-- <td>{{$count_payments_arr[0][$key-1]}}</td> --}}
                                            {{-- <td style="text-align: center;">{{$count_payment_arr[$key-1]}}</td> --}}

                                            {{-- Old collcect Principle and Interest --}}
                                            {{-- <td style="text-align:right;">{{ number_format($loan_amount_arr['collect_principle'][$key-1],2) }}៛</td>
                                            <td style="text-align:right;">{{ number_format($loan_amount_arr['collect_interest'][$key-1]+$loan_amount_arr['collect_saving'][$key-1],2) }}៛</td> --}}
                                            {{-- New Collect Principle and Interest --}}
                                            <td>{{ number_format($collect_prin_and_inter_arr['collect_principle'][$key-1],2)}}៛</td>
                                            <td>{{ number_format($collect_prin_and_inter_arr['collect_interest'][$key-1]+$collect_prin_and_inter_arr['collect_saving'][$key-1],2)}}៛</td>
                                            
                                            <td style="text-align:right;">{{ number_format($loan_amount_arr['collect_advance_fine'][$key-1] , 2) }}៛</td>
                                            <td style="text-align:center;">{{$late_credit_arr['num_late_unpaid'][$key-1]}}</td>
                                            <td style="text-align:center;">{{number_format($late_credit_arr['late_unpaid_amount'][$key-1],2)}}៛</td>
                                            <td style="text-align:center;">{{number_format($par_one,2)}}%</td>
                                            <td style="text-align:center;">{{$late_credit_arr['num_late_unpaid_more_thirty'][$key-1]}}</td>
                                            <td style="text-align:right;">{{number_format($late_credit_arr['amount_late_unpaid_more_thirty'][$key-1],2)}}៛</td>
                                            <td style="text-align: center;">{{number_format($par_thirty,2)}}%</td>
                                        </tr>
                                    @endforeach
                                    <tr style="background-color: #ddd;">
                                        <th colspan="2" style="text-align: right;">Total</th>
                                        <th style="text-align:center;">{{ $total_count_sale }}</th>
                                        <th>{{ number_format($total,2) }}៛</th>
                                        {{-- <th>{{number_format($sum_loan_amount,2)}}៛</th> --}}
                                        <th style="text-align: center;">{{ $total_count }}</th>
                                        <th style="text-align: right;">{{ number_format($sum_active_paid_amount,2) }}៛</th>
                                        <th>{{number_format($sum_admin_fee,2)}}៛</th>

                                        {{-- <th>{{number_format($sum_financing_fee,2)}}៛</th> --}}
                                        <th>0.00 ៛</th>

                                        {{-- <th></th> --}}
                                        <th>{{number_format($sum_collect_prin,2)}}៛</th>
                                        <th>{{number_format(($sum_collect_inter+$sum_collect_saving),2)}}៛</th>
                                        <th style="text-align: right;">{{number_format($sum_collect_advance_fine,2)}}៛</th>
                                        <th style="text-align: center;">{{$sum_num_late_paid}}</th>
                                        <th>{{number_format($sum_late_un_paid_amount,2)}}៛</th>
                                        <th></th>
                                        <th style="text-align: center;">{{$sum_num_late_more_thirty}}</th>
                                        <th style="text-align: right;">{{number_format($sum_amoun_unpaid_more_thirty,2)}}៛</th>
                                        <th style="text-align: center;"></th>
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