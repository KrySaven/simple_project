@extends('layouts.app')
@section('title',__('app.monthly_customer_payment_report'))
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
                        <h2>@lang('app.search')</h2>
                    </div>
                    <div class="body">
                        {!! Form::open(array('route' => 'customer_topay_report' , 'method' => 'GET')) !!}
                        <div class="row clearfix">
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
                            {{-- @dd($request->customer_id) --}}
                            <div class="col-sm-3">
                                <b>@lang('app.customer')</b>
                                <div class="form-group">
                                    {!! Form::select('customer_id', $customer,$request->customer_id,['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>__('app.select_customer'),'id'=>'customer_id']) !!}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <b>@lang('app.duration_type')</b>
                                {{-- <label class="form-label">@lang('app.duration_type')</label> --}}
                                <div class="form-line">
                                    {{Form::select('duration_type',$duration_types,$request->duration_type,['class'=>'form-control','id' => 'duration_type', 'placeholder' => __('app.duration_type')])}}
                                </div>
                                @if ($errors->has('duration_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="duration_type-error" class="error" for="duration_type">{{ $errors->first('duration_type') }}</label>
                                    </span>
                                @endif
                            </div>
                            {{-- <div class="col-sm-2">
                                <b>@lang('Location')</b>
                                <div class="form-line">
                                    {{Form::select('villages',$villages,$request->villages,['class'=>'form-control','id' => 'village', 'placeholder' => __('app.location')])}}
                                </div>
                                @if ($errors->has('duration_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="duration_type-error" class="error" for="duration_type">{{ $errors->first('duration_type') }}</label>
                                    </span>
                                @endif
                            </div> --}}
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <button type="submit" class="btn bg-green waves-effect pull-right">
                                        <i class="material-icons">search</i>
                                        <span>@lang('app.search')</span>
                                    </button>
                                </div>
                            </div>
                            {{-- {!! Form::close() !!} --}}
                        </div>
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <b>@lang('app.province')</b>
                                <div class="form-line">
                                    {!! Form::select('province_id', $province, $request->province_id??null,['class'=>'form-control show-tick province_id','data-live-search'=>'true','placeholder'=>__('app.province_or_city'),'id'=>'province_id']) !!}
                                </div>
                                @if ($errors->has('province_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="province_id-error" class="error" for="province_id">{{ $errors->first('province_id') }}</label>
                                    </span>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                <b>@lang('app.district')</b>
                                <div class="form-line form_district_id">
                                    {!! Form::select('district_id', [] , $request->district_id, ['class'=>'form-control show-tick district_id','data-live-search'=>'true', 'placeholder' => __('app.district_or_khan') ,'id'=>'district_id']) !!}
                                </div>
                                @if ($errors->has('district_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="district_id-error" class="error" for="district_id">{{ $errors->first('district_id') }}</label>
                                    </span>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                <b>@lang('app.commune')</b>
                                <div class="form-line form_commune_id">
                                    {!! Form::select('commune_id',[], $request->commune_id??null, ['class'=>'form-control show-tick commune_id','data-live-search'=>'true','placeholder'=>__('app.commune_or_sangkat'),'id'=>'commune_id']) !!}
                                </div>
                                @if ($errors->has('commune_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="commune_id-error" class="error" for="commune_id">{{ $errors->first('commune_id') }}</label>
                                    </span>
                                @endif
                            </div>
                            <div class="col-sm-3">
                                <b>@lang('app.village')</b>
                                <div class="form-line form_village_id">
                                    {!! Form::select('village_id',[], $request->village_id??null , ['class'=>'form-control show-tick village_id','data-live-search'=>'true','placeholder'=>__('app.village_or_borey'),'id'=>'village_id']) !!}
                                </div>
                                @if ($errors->has('village_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <label id="village_id-error" class="error" for="village_id">{{ $errors->first('village_id') }}</label>
                                    </span>
                                @endif
                            </div>
                        </div>
                        {!! Form::close() !!}
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
                            {{-- <h3>{{ $request->duration_type??'' }} @lang('Customer Payment Report')</h3> --}}
                            <h3>{{ config('app.duration')[ $request->duration_type] ??""}} @lang('app.monthly_customer_payment_report')</h3>
                        </div>
                    </div>
                    <div class="row" style="text-align: left;">
                        <div class="col-sm-6">
                            <p>@lang('app.from_date'): {{ date("d-M-Y", strtotime($date))}} </p>
                        </div>
                        <div class="col-sm-6" style="text-align: right;">
                            <p>@lang('app.to_date'): {{ date("d-M-Y", strtotime($date_to))}} </p>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="table table-responsive" id="responsive">
                                <table class="table table-bordered" style="white-space: nowrap;">
                                    <thead>
                                        <tr class="head_center">
                                            <th style="width: 40px !important;" class="no_width">@lang('app.no')</th>
                                            <th>@lang('app.loan_date')</th>
                                            <th>@lang('app.customer')</th>
                                            <th>@lang('app.phone')</th>
                                            <th>@lang('app.payment_date')</th>
                                            {{-- <th>Actual Date</th> --}}
                                            <th>@lang('app.principle')</th>
                                            <th>@lang('app.interest')</th>
                                            <th>@lang('app.saving_label')</th>
                                            <th>@lang('app.total')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_amount_usd   = 0;
                                            $sum_total          = 0;
                                            $sum_interest       = 0;
                                            $saleid             = 0;
                                            $sum_insurance      = 0;
                                            $currencySymbol     = '៛';
                                        @endphp
                                        
                                        @forelse($rows as $key => $row)
                                        {{-- @dd($row->sale->date) --}}
                                            @php
                                                // $sum_percentage = $row->payment->where('status','=','paid')->sum('percentage');
                                                // $sale_paid = $row->payment->where('status','=','paid')->sum('total');
                                                // $sale_notpaid = $row->payment->where('status','=','notpaid')->sum('total');
                                                // $sale_interest = $row->payment->sum('interest');
                                                $currencySymbol     = LoanHelper::currencySymbol($row->currency_type);
                                                $total_amount_usd   += $row->amount;
                                                $sum_total          += $row->total;
                                                $sum_interest       += $row->interest;
                                                $sum_insurance      += $row->saving??0;
                                            @endphp
                                            @if($saleid == $row->sale_id)
                                                <tr>
                                                    <td style="text-align:center;"> </td>
                                                    <td>{{ date('d-M-Y',strtotime($row->sale->date??"")) }}</td>
                                                    <td>{{ isset($row->sale->customer->name)?$row->sale->customer->name : 'N/A' }}</td>
                                                    <td>{{ isset($row->sale->customer->phone)?$row->sale->customer->phone : 'N/A' }}</td>
                                                    <td>{{ date('d-M-Y',strtotime($row->payment_date)) }}</td>
                                                    {{-- <td>{{ date('d-M-Y',strtotime($row->actual_date)) }}</td> --}}
                                                    <td>{{ number_format($row->amount,2) }}  {{ $currencySymbol }}</td>
                                                    <td>{{ number_format($row->interest,2) }}  {{ $currencySymbol }}</td>
                                                    <td>{{ number_format($row->saving,2) }} {{ $currencySymbol }}</td>
                                                    <td>{{ number_format($row->total,2) }}  {{ $currencySymbol }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td style="text-align:center;">{{ ++ $key}}</td>
                                                    <td>{{ date('d-M-Y',strtotime($row->sale->date??"")) }}</td>
                                                    <td>{{ isset($row->sale->customer->name)?$row->sale->customer->name : 'N/A' }}</td>
                                                    <td>{{ isset($row->sale->customer->phone)?$row->sale->customer->phone : 'N/A' }}</td>
                                                    <td>{{ date('d-M-Y',strtotime($row->payment_date)) }}</td>
                                                    {{-- <td>{{ date('d-M-Y',strtotime($row->actual_date)) }}</td> --}}
                                                    <td>{{ number_format($row->amount,2) }}  {{ $currencySymbol }}</td>
                                                    <td>{{ number_format($row->interest,2) }}  {{ $currencySymbol }}</td>
                                                    <td>{{ number_format($row->saving,2) }} {{ $currencySymbol }}</td>
                                                    <td>{{ number_format($row->total,2) }}  {{ $currencySymbol }}</td>
                                                </tr>
                                            @endif
                                            @php($saleid = $row->sale_id)
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">@lang('app.data_not_found')</td>
                                            </tr>
                                        @endforelse
                                        <tr style="background-color: #dddddd;">
                                            <th colspan="5" style="text-align: right !important;">@lang('app.total')</th>
                                            <th>{{ number_format($total_amount_usd,2) }}  {{ $currencySymbol }}</th>
                                            <th>{{ number_format($sum_interest,2) }}  {{ $currencySymbol }}</th>
                                            <th>{{ number_format($sum_insurance,2) }} {{ $currencySymbol }}</th>
                                            <th>{{ number_format($sum_total,2) }}  {{ $currencySymbol }}</th>
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
    {{-- @dd($request->district_id) --}}
@endsection
@section('javascript')
<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
<script type="text/javascript">
        // var district_id = "{{ $request->district_id }}";
    $(document).on("change",'.province_id',function(){
        var province_id = $(this).val();
        get_districts('district_id',province_id,'{{ $request->district_id??0 }}');
        
    });

    $('.province_id').trigger('change');

    $(document).on("change",'.district_id',function(){
        var district_id = $(this).val();
        get_communes('commune_id',district_id,'{{$request->commune_id??0}}');
    });

    $('.district_id').trigger('change');

    $(document).on("change",'.commune_id',function(){
        var commune_id = $(this).val();
        get_villages('village_id',commune_id,'{{$request->village_id??0}}');
    });
    $('.commune_id').trigger('change');
</script>
@stop