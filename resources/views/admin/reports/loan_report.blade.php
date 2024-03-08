@extends('layouts.app')
@section('title',__('app.loan_report'))
@section('content')
<style>
    @media print {
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
        .label-success{
            border: unset !important;
        }
        .col-sm-6 {
            width: 50%;
            float: left;
        }
        .no_width {
            width: 50px !important
        }
        #printarea {
            font-family: 'Khmer OS Battambong';
        }
    }
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs 12">
            <div class="card">
                <div class="header">
                    <h2>@lang('app.search')</h2>
                </div>
                <div class="body">
                    <div class="row clearfix" style="margin-bottom: 10px;">
                        {!! Form::open(array('route' => 'loan_report' , 'method' => 'GET','id' => 'form_submit')) !!}
                            <div class="col-md-3 no-magin-bottom">
                                <b>@lang('app.search')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{Form::text('search',$request->search??null,['class'=>'form-control','placeholder'=>__('app.search_loan_desc')])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 no-magin-bottom">
                                <b>@lang('app.from_date')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{Form::text('from_date',isset($from_date)? $from_date:null,['class'=>'form-control datepicker','placeholder'=>__('app.from_date'),])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 no-magin-bottom">
                                <b>@lang('app.to_date')</b>
                                <div class="form-group">
                                    <div class="form-line">
                                        {{Form::text('to_date',isset($to_date)?$to_date:null,['class'=>'form-control datepicker','placeholder'=>__('app.to_date')])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 no-magin-bottom">
                                <div class="form-group form-float">
                                    <b>@lang('app.status')</b>
                                    {!! Form::select('status', config('app.loan_status'), $request->status, ['id'=>'status', 'class'=>'form-control show-tick selectpicker','data-live-search'=>'true', 'placeholder' => __('app.select_status')]) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group pull-right">
                                    <button type="submit" class="btn bg-green waves-effect pull-right">
                                        <i class="material-icons">search</i>
                                        <span>@lang('app.search')</span>
                                    </button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row clearfix">
            <button type="button" class="btn btn-success waves-effect pull-right print_data" onclick="print_report()">
                <i class="material-icons">print</i>
                <span>@lang('app.print_label')</span>
            </button>
        </div>
        <div class="row clearfix">
            <div class="card" id="printarea" style="padding: 10px;">
                <div class="row">
                    <div class="col-sm-12" style="text-align: center;">
                        <h3>@lang('app.loan_report')</h3>
                    </div>
                </div>
                <div class="row" style="text-align: left;">
                    <div class="col-sm-6">
                        <p>@lang('app.from_date'): {{ date("d-M-Y", strtotime($from_date)) }}</p>
                    </div>
                    <div class="col-sm-6" style="text-align: right;">
                        <p>@lang('app.to_date'): {{ date("d-M-Y", strtotime($to_date))}} </p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                            <tr>
                                <th>@lang('app.no_en')</th>
                                <th>@lang('app.loan_no')</th>
                                <th>@lang('app.loan_amount')</th>
                                <th>@lang('app.interest')</th>
                                <th>@lang('app.loan_term')</th>
                                <th>@lang('app.duration_type')</th>
                                <th>@lang('app.loan_type')</th>
                                <th>@lang('app.customer')</th>
                                <th>@lang('app.co')</th>
                                <th>@lang('app.status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($loan as $loans)
                                <tr class="align-middle">
                                    <td>{!! $loop->iteration !!}</td>
                                    <td>{!! $loans->inv_no !!}</td>
                                    <td>{!! $loans->loan_amount !!}</td>
                                    <td>{!! $loans->interest_rate !!}</td>
                                    <td>{!! $loans->loan_term !!}</td>
                                    <td>{!! $loans->durationType->type_en??"" !!}</td>
                                    <td>{!! $loans->payment_type !!}</td>
                                    <td>{!! $loans->customer->name??"N/A" !!}</td>
                                    <td>{!! $loans->coUser->name??"N/A" !!}</td>
                                    <td>
                                        @php
                                            $class = '';
                                            if($loans->approve_status == 'pending'){
                                                $class='label-danger';
                                            }else{
                                                $class = 'label-success';
                                            }
                                        @endphp
                                        <span class="label {{ $class }}">{{ ucfirst($loans->approve_status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <h5 class="no-record-text">@lang('app.no_record_found')</h5>
                                    </td>
                                </tr>
                            @endforelse 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        $('.datepicker').bootstrapMaterialDatePicker({ 
            weekStart : 0, 
            clearButton: true,
            time: false
        });
        document.getElementById("form_submit").addEventListener("keydown", function(event) {
            if(event.keyCode === 13) {
                document.getElementById("form_submit").submit();
            }
        });
    </script>
@endpush