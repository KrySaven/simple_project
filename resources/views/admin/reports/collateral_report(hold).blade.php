@extends('layouts.app')
@section('title',__('app.collateral_report'))
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
                font-size: 9px !important;
                text-align: center !important;
            }

            .table-bordered tbody tr td {
                padding: 5px 2px;
                border: 1px solid #000 !important;
                font-size: 9px !important;
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
                        <h2>@lang('app.search')</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            {!! Form::open(array('route' => 'collateral_report' , 'method' => 'GET','id'=>'submit_form')) !!}
                            {!! Form::hidden('submit_type',null,['id'=>'submit_type']) !!}
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
                            <div class="col-sm-3">
                                <b>@lang('app.customer')</b>
                                <div class="form-group">
                                    {!! Form::select('customer_id', $customer,$request->customer_id,['class'=>'form-control show-tick','data-live-search'=>'true','placeholder'=>__('app.select_customer'),'id'=>'customer_id']) !!}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <b>@lang('app.status')</b>
                                <div class="form-group">
                                    {!! Form::select('status', $status,$request->status,['class'=>'form-control show-tick','data-live-search'=>'true','id'=>'status']) !!}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group" style="text-align: right;">
                                    <button type="submit" class="btn bg-green waves-effect">
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
        <!-- #END# Basic Examples -->
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
                            <h3>{{ trans('app.collateral_report') }}</h3>
                        </div>
                    </div>
                    <div class="row" style="text-align: left;">
                        <div class="col-sm-6">
                            <p>@lang('app.from_date'): {{ date("d-M-Y", strtotime($date_from))}} </p>
                        </div>
                        <div class="col-sm-6" style="text-align: right;">
                            <p>@lang('app.to_date'): {{ date("d-M-Y", strtotime($date_to))}} </p>
                        </div>
                    </div>
                    @if($number_of_collateral)
                        <div class="row">
                            <div class="col-sm-12 text-center m-b-10">
                                <b>@lang('app.number_of_collateral') : {{ $number_of_collateral??"" }}</b>
                            </div>
                        </div>
                    @endif
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="table-responsive" style="max-height: 60vh;">
                                <div id="DataTables_Table_1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                                </div>
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr class="head_center">
                                            <th style="width: 40px !important;" class="no_width">@lang('app.no')</th>
                                            <th>@lang('app.customer')</th>
                                            <th>@lang('app.collateral_type')</th>
                                            <th>@lang('app.collateral_name')</th>
                                            <th>@lang('app.color')</th>
                                            <th>@lang('app.year_of_mfg')</th>
                                            <th>@lang('app.engine_no')</th>
                                            <th>@lang('app.number_plate')</th>
                                            <th>@lang('app.frame_no')</th>
                                            <th>@lang('app.first_date_registeration')</th>
                                            <th>@lang('app.status')</th>
                                            @if($request->status == 'return')
                                                <th>@lang('app.return_date')</th>
                                                <th>@lang('app.return_by')</th>
                                                <th>@lang('app.description')</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($collateral as $collaterals)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $collaterals->customer->name_kh??"" }}</td>
                                                <td>{{ MyHelper::ucfirst_unicode($collaterals->collateral_name??"N/A") }}</td>
                                                <td>{{ config('app.collateral_type_kh')[$collaterals->collateral_type] }}</td>
                                                <td>{{ $collaterals->color??"N/A" }}</td>
                                                <td>{!! $collaterals->year_of_mfg??"N/A" !!}</td>
                                                <td>{!! $collaterals->engine_no??"N/A" !!}</td>
                                                <td>{!! $collaterals->licence_type??"N/A" !!}</td>
                                                <td>{!! $collaterals->frame_no??"N/A" !!}</td>
                                                <td>{!! date('d-M-Y',strtotime($collaterals->first_date_registeration??"N/A")) !!}</td>
                                                <td>{{ MyHelper::ucfirst_unicode($collaterals->status??"") }}</td>
                                                @if($collaterals->status != 'using')
                                                    <td>{!! date('d-M-Y',strtotime($collaterals->return_date??"N/A")) !!}</td>
                                                    <td>{!! $collaterals->User->name??"N/A" !!}</td>
                                                    <td>{!! $collaterals->description??"N/A" !!}</td>
                                                @endif
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center" colspan="14">@lang('app.data_not_found')</td>
                                            </tr>
                                        @endforelse                                        
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
