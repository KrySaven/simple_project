@extends('layouts.app')
@section('title','Sales Report')
@section('content')
@php
    use App\Helpers\InstallmentHelper;
    use App\User;
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
                        <h2>
                            Search
                        </h2>

                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            {!! Form::open(array('route' => 'sales_report' , 'method' => 'GET')) !!}
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
                <div class="card" id="printarea" style="padding: 10px;">
                    <div class="row">
                        <div class="col-sm-12" style="text-align: center;">
                            <h3>CAR LEASING REPORT</h3>
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
                                <table class="table table-bordered" style="white-space: nowrap;">
                                    <thead>
                                    <tr class="head_center">
                                        <!-- <th>No</th> -->
                                        <th style="width: 40px !important;" class="no_width">No</th>
                                        <th>Plaque No</th>
                                        <th>Date of The first Registeration</th>
                                        <th>Leasing Amount</th>
                                        <th>Leasing Amount in Khmer</th>
                                        <th>Start Date</th>
                                        <th>Dateline</th>
                                        <th>Relation</th>
                                        <th>Customer Name</th>
                                        <th>Gender</th>
                                        <th>DOB</th>
                                        <th>Identity Card No</th>
                                        <th>Identity Card Registeraion Date</th>
                                        <th>Identity Card Registeraion Place</th>
                                        <th>Address</th>
                                        <th>Guantor Name</th>
                                        <th>Gender</th>
                                        <th>DOB</th>
                                        <th>Indentity Card No</th>
                                        <th>Identity Card Registeraion Date</th>
                                        <th>Identity Card Registeraion Place</th>
                                        <th>CO Name</th>
                                        <th>PO Name</th>
                                        <th>Form Time</th>
                                        <th>To Time</th>
                                        <th>Make and Model</th>
                                        <th>Text Stamp</th>
                                        <th>VIN</th>
                                        <th>Engine No</th>
                                        <th>Chissis No</th>
                                        <th>Cylinder Disp</th>
                                        <th>Color</th>
                                        <th>Year of Car</th>
                                        <th>Market Price</th>
                                        <th>Hot Price</th>
                                        <th>Sale Price</th>
                                        <th>Phone Number</th>
                                        <th>Interest</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $sum_percentage = 0;
                                        $sale_paid = 0;
                                        $sale_notpaid = 0;
                                        $sale_interest = 0;
                                        $total_amount_usd=0;

                                        $sum_price = 0;
                                        $sum_deposit = 0;
                                        $sum_total = 0;
                                        $sum_interest = 0;
                                        $sum_p_paid = 0;
                                        $sum_p_notpaid = 0;
                                        $sum_p_amount = 0;
                                        $sum_amount = 0;
                                        $sum_amounts = 0;
                                    @endphp
                                    @foreach($rows as $key => $row)
                                        @php
                                            // $sum_percentage = $row->payment->where('status','=','paid')->sum('percentage');
                                            // $sum_amount = $row->payment->sum('total');
                                            // $sale_paid = $row->payment->where('status','=','paid')->sum('amount');
                                            // $sale_notpaid = $row->payment->where('status','=','notpaid')->sum('amount');
                                            // $sale_interest = $row->payment->sum('interest');
                                            // $total_amount_usd +=$row->amount;
                                            // $sum_price += $row->price;
                                            // $sum_deposit += $row->deposit;
                                            // $sum_total += $row->total;
                                            // $sum_interest += $sale_interest;
                                            // $sum_p_paid += $sale_paid;
                                            // $sum_p_notpaid += $sale_notpaid;
                                            // $sum_p_amount += $sale_paid + $sale_notpaid;
                                            // $sum_amounts += $sum_amount;

                                        @endphp
                                        {{-- @dd($rows) --}}
                                        <tr>
                                            <td style="text-align:center;">{{ ++ $key}}</td>
                                            <td>{{ $row->license_plate??'N/A' }}</td>
                                            <td>{{ date('d-M-Y',strtotime($row->first_card_issuance_date)) }}</td>
                                            <td>{{ $row->loan_price??'N/A' }}</td>
                                            <td>{{ InstallmentHelper::khNumberWord($row->loan_price)}}</td>
                                            <td>{{ date('d-M-Y',strtotime($row->first_payment)) }}</td>
                                            <td> 
                                                @php
                                                    $payment = $row->last_payment_date;
                                                    $dateline = !empty($payment[0]) ? $payment[0]->payment_date : 'N/A';
                                                @endphp
                                                 {{$dateline}} 
                                            </td>

                                            <td>
                                                @php
                                                    $relation = $row->dealer_relation ?? $row->dealer_relation_other;
                                                @endphp
                                                {{ $relation }}
                                            </td>
                                            <td>{{ isset($row->customer->name)?$row->customer->name : 'N/A' }}</td>
                                            <td>{{ isset($row->customer->gender)?$row->customer->gender : 'N/A' }}</td>
                                            <td>{{ isset($row->customer->date_of_birth)?$row->customer->date_of_birth : 'N/A' }}</td>
                                            <td>{{ isset($row->customer->identity_number)?$row->customer->identity_number : 'N/A' }}</td>
                                            <td>{{ isset($row->customer->identitycard_number_date)?$row->customer->identitycard_number_date : 'N/A' }}</td>
                                            <td>{{ $row->customer->issued_by }}</td>
                                            <td>House No: {{ $row->customer->house_no ?? 'N/A'}} 
                                                Street No: {{ $row->street_no ?? 'N/A'}} 
                                                Group: {{ $row->add_group ?? 'N/A'}} 
                                                Village: {{ $row->customer->village->village_namekh ?? 'N/A' }} 
                                                Commune: {{ $row->customer->commune->commune_namekh ?? 'N/A' }}
                                                District: {{ $row->customer->district->district_namekh ?? 'N/A'}}
                                                Province: {{ $row->customer->province->province_kh_name ?? 'N/A'}}
                                            </td>
                                            <td>{{ $row->guarantor->name_kh ?? 'N/A' }}</td>
                                            <td>{{ $row->guarantor->gender ?? 'N/A' }}</td>
                                            <td>{{ $row->guarantor->date_of_birth ?? 'N/A' }}</td>
                                            <td>{{ $row->guarantor->identity_number ?? 'N/A' }}</td>
                                            <td>{{ $row->guarantor->identitycard_number_date ?? 'N/A' }}</td>
                                            <td>{{ $row->guarantor->issued_by }}</td>
                                      
                                            
                                            <td>
                                                @php
                                                    $user_name = Auth::user()->name_kh;
                                                @endphp
                                                {{$user_name}}
                                            </td>
                                            <td>{{ $row->dealer->name_kh }}</td>
                                            <td>From Date</td>
                                            <td>To Date</td>
                                            <td>{{ $row->make_model??'N/A' }}</td>
                                            <td>{{ $row->text_stamp??'N/A' }}</td>
                                            <td>{{ $row->vin??'N/A' }}</td>
                                            <td>{{ $row->engine_number??'N/A' }}</td>
                                            <td>{{ $row->chassis_no??'N/A' }}</td>
                                            <td>{{ $row->cylineder_size??'N/A' }}</td>
                                            <td>{{ $row->color??'N/A' }}</td>
                                            <td>{{ $row->year??'N/A' }}</td>
                                            <td>{{ $row->market_price??'N/A' }}</td>
                                            <td>{{ $row->hot_price??'N/A' }}</td>
                                            <td>{{ $row->loan_price??'N/A' }}</td>
                                            <td>Phone Number</td>
                                            <td>{{ $row->interest??'N/A' }}</td>
                                            
                                            {{-- <td>{{ number_format($row->price,2) }}$</td>
                                            <td>{{ number_format($row->deposit,2) }}$</td>
                                            <td>{{ ($row->interest*1) }}%</td>
                                            <td>{{ number_format($row->total,2) }}$</td>
                                            <td>{{ $sum_percentage }}%</td>
                                            <td>{{ number_format($sale_interest,2) }}$</td>
                                            <td>{{ number_format($sale_paid,2) }}$</td>
                                            <td>{{ number_format($sale_notpaid,2) }}$</td>
                                            <td>{{ number_format($sum_amount,2) }}$</td> --}}
                                        </tr>
                                    @endforeach
                                    {{-- <tr style="background-color: #ddd;">
                                        <th colspan="5" style="text-align: right !important;">Total:</th>
                                        <th>{{ number_format($sum_price,2) }}$</th>
                                        <th>{{ number_format($sum_deposit,2) }}$</th>
                                        <th></th>
                                        <th>{{ number_format($sum_total,2) }}$</th>
                                        <th></th>
                                        <th>{{ number_format($sum_interest,2) }}$</th>
                                        <th>{{ number_format($sum_p_paid,2) }}$</th>
                                        <th>{{ number_format($sum_p_notpaid,2) }}$</th>
                                        <th>{{ number_format($sum_amounts,2) }}$</th>

                                    </tr> --}}
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