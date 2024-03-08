@extends('layouts.app')
@section('title','Invoice Daily Report')
@section('content')
@php
    use App\Helpers\MyHelper;
@endphp
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
        #printarea {
            font-family: 'Khmer OS Battambong','Khmer OS';
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
                font-family: 'Khmer OS Battambong','Khmer OS';
            }
            .print_recipt_multy{
                width: 100%;
                float: left; 
            }
            .print_recipt{
                width: 100%;
                float: left;
                page-break-after: always !important;
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
                            {!! Form::open(array('route' => 'invoice_daily_report' , 'method' => 'GET')) !!}
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
                <div id="printarea">
                    @foreach ($rows as $print_payment)
                    <div class="col-sm-6 print_recipt_multy">
                        <div class="card print_recipt">
                            <div class="row" style="text-align: left;padding: 10px 10px !important;">
                               {{--  <div class="col-sm-7" style="text-align: right;float: left; width: 58.33333% !important;">
                                </div> --}}
                                <div class="col-sm-5" style="text-align: right;float: right; width: 41.66666% !important;">
                                    <p>ល.រ ៖ {{ MyHelper::getClientNumber($print_payment->id) }}</p>
                                    <p>{{ date('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="row">
                                @php($company = isset($siteprofile->company)?$siteprofile->company:'855 Solution')
                                <div class="rcol-sm-12" style="text-align: center;margin-bottom: 20px;">
                                    <h1 style="font-family: 'Times New Roman';">{{ $company }}</h1>
                                    {{-- <h4 style="font-family: 'Khmer OS Muol Light' !important;font-weight: unset !important;">ហាងលក់ទូរស័ព្ទដៃ ហាយស្ទ័រ</h4> --}}
                                </div>
                                <div class="col-sm-12" style="text-align: center;margin-bottom: 20px;">
                                    <h5 style="font-family: 'Khmer OS Muol Light' !important;font-weight: unset !important;">បង្កាន់ដៃទទួលប្រាក់</h5>
                                </div>
                            </div>
                            <div class="clearfix" style="text-align: left;padding: 10px 10px !important;">
                                <div class="row"​>
                                    <div class="col-sm-12" style="float: left;width: 100%;">
                                        <style type="text/css">
                                            tr td{
                                                padding: 8px !important;
                                            }
                                        </style>
                                        <table style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 25%;">ឈ្មោះអតិថិជន</td>
                                                    <td>: {{ isset($print_payment->sale->customer->name)? $print_payment->sale->customer->name: 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 25%;">ថ្ងៃចេញតារាង</td>
                                                    <td>: {{ isset($print_payment->sale->date)? date('d/m/Y',strtotime($print_payment->sale->date)): 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 25%;">ល.រ ជួរតារាង</td>
                                                    <td>: ជួរទី {{ $print_payment->no }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 25%;">ថ្ងៃដែលត្រូវបង់</td>
                                                    <td>: {{ date('d/m/Y',strtotime($print_payment->payment_date)) }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 25%;">ថ្ងៃបង់ជាក់ស្ដែង</td>
                                                    <td>: {{ date('d/m/Y',strtotime($print_payment->actual_date)) }}</td>
                                                </tr>
                                                @php($transactions = $print_payment->transactions??"")
                                                @php($total = 0)
                                                @if($transactions->count()>0)
                                                    @php($total = $transactions->sum('amount_usd'))
                                                @endif
                                                <tr>
                                                    <td style="width: 25%;">ចំនួនសរុប</td>
                                                    <td>: {{ number_format($total,2) }} {{ LoanHelper::currencySymbol($print_payment->loan->currency_type) }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 25%;">ផ្សេងៗ</td>
                                                    <td>: {{ $print_payment->description }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <br>
                                <div class="row" style="text-align: left;padding: 10px 50px !important;margin-top: 40px!important;">
                                    <div class="col-sm-12" style="text-align: center;float: left; width: 100% !important;">
                                        <p><b>សូមអគុណ !</b></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection