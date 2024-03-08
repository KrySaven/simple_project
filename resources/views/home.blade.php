@php
    use App\Helpers\MyHelper;
    $UserPermision = MyHelper::UserPermision();
    $checkisadmin = MyHelper::checkisadmin();
@endphp
@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <style type="text/css">
        .img_icon {
            margin-top: 12px !important;
            max-width: 50px !important;
        }
    </style>
    <style>
        .text_red {
            color: #F44336 !important;
        }

        /*.card{font-family: 'Khmer OS Siemreap Regular' !important;}*/
        @media print {
            .text_red {
                color: #F44336 !important;
            }

            .table-bordered tbody tr th {
                padding: 5px;
                border: 1px solid #000 !important;
                font-size: 10px !important;
                white-space: nowrap !important;
                text-align: center !important;
                font-weight: unset !important;
                font-family: 'Khmer OS Siemreap Regular' !important;
            }

            .table-bordered tbody tr td {
                padding: 5px;
                border: 1px solid #000 !important;
                font-size: 12px !important;
                text-align: center !important;
            }

            .table-bordered tfoot tr th {
                padding: 5px;
                border: 1px solid #000 !important;
                font-size: 10px !important;
                white-space: nowrap !important;
                text-align: center !important;
                font-weight: unset !important;
                font-family: 'Khmer OS Siemreap Regular' !important;
            }

            .table-bordered tfoot tr td {
                padding: 5px;
                border: 1px solid #000 !important;
                font-size: 12px !important;
            }

            .table-bordered thead tr th {
                padding: 5px;
                border: 1px solid #000 !important;
                white-space: nowrap !important;
                text-align: center !important;
                font-weight: unset !important;
                font-family: 'Khmer OS Siemreap Regular' !important;
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

            .card {
                font-family: 'Khmer OS Siemreap Regular' !important;
            }

            td {
                white-space: nowrap !important;
            }

            /*p{font-size: 10px !important;}*/
        }
    </style>
    <div class="container-fluid">
        <div class="block-header">
            {{-- <h2>@lang('app.dashboard')</h2> --}}
        </div>
        @if ($checkisadmin)
            {{-- <div class="row clearfix">
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect" style="background-color: #0e4e82 !important;">
                    <div class="icon">
                        <img class="material-icons img_icon" src="{{ asset('images/icon/user.png') }}" alt="">

                    </div>
                    <div class="content">
                        <div class="text">@lang('app.general_user')</div>
                        <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">{{ $users }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <div class="info-box bg-cyan hover-expand-effect" style="background-color: #0e4e82 !important;">
                    <div class="icon">
                        <img class="material-icons img_icon" src="{{ asset('images/icon/user.png') }}" alt="">

                    </div>
                    <div class="content">
                        <div class="text">@lang('app.number_of_co')</div>
                        <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">{{ $co }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                <div class="info-box bg-light-green hover-expand-effect" style="background-color: #0e4e82 !important;">
                    <div class="icon">
                        <img class="material-icons img_icon" src="{{ asset('images/icon/dealer.png') }}" alt="">

                    </div>
                    <div class="content">
                        <div class="text">@lang('app.branch')</div>
                        <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">{{ $branch??0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-teal hover-expand-effect" style="background-color: #0e4e82 !important;">
                    <div class="icon">
                        <img class="material-icons img_icon" src="{{ asset('images/icon/customer.png') }}" alt="">

                    </div>
                    <div class="content">
                        <div class="text">@lang('app.customer')</div>
                        <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">{{ $customers }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-teal hover-expand-effect" style="background-color: #0e4e82 !important;">
                    <div class="icon">
                        <img class="material-icons img_icon" src="{{ asset('images/icon/statement.png') }}" alt="">

                    </div>
                    <div class="content">
                        <div class="text">@lang('app.loan')</div>
                        <div class="number count-to" data-from="0" data-to="257" data-speed="1000" data-fresh-interval="20">{{ $count_sale }}</div>
                    </div>
                </div>
            </div>
        </div> --}}

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-pink hover-expand-effect show_number">
                        <div class="content">
                            <div class="number count-to" data-from="0" data-to="50" data-speed="1000"
                                data-fresh-interval="20">
                            </div>
                            <div class="text">Purchase Order</div>
                            <p style="font-size: 12px">Show number of Purchase orders.</p>
                        </div>

                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-cyan hover-expand-effect show_number">
                        <div class="content">
                            <div class="number count-to" data-from="0" data-to="243" data-speed="1000"
                                data-fresh-interval="20"></div>
                            <div class="text">Verified</div>
                            <p style="font-size: 12px">Show number of verified orders.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-light-green hover-expand-effect show_number">
                        <div class="content">
                            <div class="number count-to" data-from="0" data-to="243" data-speed="1000"
                                data-fresh-interval="20"></div>
                            <div class="text">Process</div>
                            <p style="font-size: 12px">Show number of process order.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box bg-orange hover-expand-effect show_number">
                        <div class="content">
                            <div class="number count-to" data-from="0" data-to="243" data-speed="1000"
                                data-fresh-interval="20"></div>
                            <div class="text">Completed</div>
                            <p style="font-size: 12px">Show number of complete.</p>
                        </div>
                    </div>
                </div>

            </div>
        @endif
        <div class="row clearfix">
            <!-- Task Info -->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="header">
                        <h2>@lang('app.customer_list_to_pay')</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix" style="margin-bottom: 10px;">
                            <style type="text/css">
                                .no_margin_buttom {
                                    margin-bottom: 5px !important;
                                }
                            </style>
                            {!! Form::open(['route' => 'home', 'method' => 'GET']) !!}
                            <div class="col-sm-10 no_margin_buttom">
                                <b>@lang('app.search_with_customer_name_phone_email_identity_passport')</b>
                                <div class="form-group">
                                    <div class="form-line branchdea_holder">
                                        {{ Form::text('search', $request->search, ['class' => 'form-control', 'placeholder' => __('app.search_with_customer_name_phone_email_identity_passport')]) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 no_margin_buttom">
                                <div class="form-group">
                                    <button type="submit" class="btn bg-green waves-effect pull-right">
                                        <i class="material-icons">search</i>
                                        <span>@lang('app.search')</span>
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dashboard-task-infos">
                                <thead>
                                    <tr>
                                        <th>@lang('app.no')</th>
                                        <th>@lang('app.follow_note')</th>
                                        <th>@lang('app.customer_name')</th>
                                        <th>@lang('app.payment_date')</th>
                                        <th>@lang('app.amount')</th>
                                        <th class="text-right">@lang('app.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                        $num = Request::get('page');
                                        if ($num > 1) {
                                            $i = ($num - 1) * 200;
                                        }
                                        $saleid = 0;
                                    @endphp
                                    @foreach ($pay_list as $key => $row)
                                        @if ($row->isUnpaid() || $row->isPartial())
                                            @php
                                                $due_amount = $row->total - ($row->t_amount + $row->t_interest + $row->t_saving);
                                            @endphp
                                            @if ($saleid == $row->sale_id)
                                                <tr>
                                                    <td></td>
                                                    @if ($row->is_follow == 1)
                                                        <td>{{ isset($row->note) ? $row->note : 'N/A' }}</td>
                                                    @else
                                                        <td>N/A</td>
                                                    @endif

                                                    <td>{{ isset($row->sale->customer->name) ? $row->sale->customer->name_kh : 'N/A' }}
                                                        ||
                                                        {{ isset($row->sale->customer->phone) ? $row->sale->customer->phone : 'N/A' }}
                                                    </td>
                                                    <td>{{ date('d-m-Y', strtotime($row->payment_date)) }}</td>
                                                    @if ($row->isUnpaid())
                                                        <td><span
                                                                class="label bg-green">{{ number_format($row->total, 2) }}</span>
                                                        </td>
                                                    @else
                                                        <td><span
                                                                class="label bg-green">{{ number_format($due_amount, 2) }}</span>
                                                        </td>
                                                    @endif

                                                    <td class="text-right">
                                                        <ol class="breadcrumb"
                                                            style="padding: 0px !important;margin-bottom: 0px !important;">
                                                            <li class="active">
                                                                @if ($row->isUnpaid() || $row->isPartial())
                                                                    @if ($row->hasPayPermission())
                                                                        <div class="btn-group btn-group-sm btn-group-action"
                                                                            role="group" aria-label="Small button group">
                                                                            <button type="button"
                                                                                class="btn btn-primary waves-effect btn-main"
                                                                                onclick="payNow(this);"
                                                                                url="#">
                                                                                {{-- <i class="material-icons">attach_money</i> --}}
                                                                                {{-- {!! $currencySymbol !!} --}}
                                                                                <span>@lang('app.pay_now')</span>
                                                                            </button>
                                                                        </div>
                                                                    @else
                                                                        <a href="javascript:void(0);"
                                                                            style="padding: 5px;border-radius: 2px; background: #FF5722;color: #382e2e;"
                                                                            disabled="disabled"><span>@lang('app.pay_now')</span></a>
                                                                    @endif
                                                                @endif
                                                                @if ($row->is_follow == 1)
                                                                    @if (isset($checkisadmin) || isset($UserPermision['sale.un_follow_up']))
                                                                        <a onclick="return confirm('Are you sure you want to Unfollow ?');"
                                                                            href="{{ route('sale.un_follow_up', ['id' => $row->id]) }}"
                                                                            style="padding: 5px;border-radius: 2px; background: #FF5722;color: #ffffff;"><i
                                                                                class="material-icons">close</i>Unfollow</a>
                                                                    @else
                                                                        <a href="javascript:void(0);"
                                                                            style="padding: 5px;border-radius: 2px; background: #FF5722;color: #382e2e;"
                                                                            disabled="disabled"><i
                                                                                class="material-icons">close</i>
                                                                            Unfollow</a>
                                                                    @endif
                                                                @else
                                                                    @if (isset($checkisadmin) || isset($UserPermision['sale.save_follow_up']))
                                                                        <a href="javascript:void(0);" data-toggle="modal"
                                                                            data-target="#follow_up"
                                                                            onclick="follow_up({{ $row->id }})"
                                                                            style="padding: 5px;border-radius: 2px; background: #009688;color: #ffffff;"><i
                                                                                class="material-icons">check_circle</i>Followup</a>
                                                                    @else
                                                                        <a href="javascript:void(0);"
                                                                            style="padding: 5px;border-radius: 2px; background: #009688;color: #382e2e;"
                                                                            disabled="disabled"><i
                                                                                class="material-icons">check_circle</i>
                                                                            Followup</a>
                                                                    @endif
                                                                @endif
                                                            </li>
                                                        </ol>

                                                    </td>
                                                </tr>
                                            @else
                                                @php
                                                    $due_amount = $row->total - ($row->t_amount + $row->t_interest + $row->t_saving);
                                                @endphp
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    @if ($row->is_follow == 1)
                                                        <td>{{ isset($row->note) ? $row->note : 'N/A' }}</td>
                                                    @else
                                                        <td>N/A</td>
                                                    @endif

                                                    <td>{{ isset($row->sale->customer->name) ? $row->sale->customer->name_kh : 'N/A' }}
                                                        ||
                                                        {{ isset($row->sale->customer->phone) ? $row->sale->customer->phone : 'N/A' }}
                                                    </td>
                                                    <td>{{ date('d-m-Y', strtotime($row->payment_date)) }} </td>
                                                    @if ($row->isUnpaid())
                                                        <td><span
                                                                class="label bg-green">{{ number_format($row->total, 2) }}</span>
                                                        </td>
                                                    @else
                                                        <td><span
                                                                class="label bg-green">{{ number_format($due_amount, 2) }}</span>
                                                        </td>
                                                    @endif
                                                    <td class="text-right" style="white-space: nowrap !important;">
                                                        <ol class="breadcrumb"
                                                            style="padding: 0px !important;margin-bottom: 0px !important;">
                                                            <li class="active">
                                                                @if ($row->isUnpaid() || $row->isPartial())
                                                                    @if ($row->hasPayPermission())
                                                                        <div class="btn-group btn-group-sm btn-group-action"
                                                                            role="group"
                                                                            aria-label="Small button group">
                                                                            <button type="button"
                                                                                class="btn btn-primary waves-effect btn-main"
                                                                                onclick="payNow(this);"
                                                                                url="#">
                                                                                {{-- <i class="material-icons">attach_money</i> --}}
                                                                                {{-- {!! $currencySymbol !!} --}}
                                                                                <span>@lang('app.pay_now')</span>
                                                                            </button>
                                                                        </div>
                                                                    @else
                                                                        <a href="javascript:void(0);"
                                                                            style="padding: 5px;border-radius: 2px; background: #FF5722;color: #382e2e;"
                                                                            disabled="disabled"><span>@lang('app.pay_now')</span></a>
                                                                    @endif
                                                                @endif
                                                                @if ($row->is_follow == 1)
                                                                    @if (isset($checkisadmin) || isset($UserPermision['sale.un_follow_up']))
                                                                        <a onclick="return confirm('Are you sure you want to Unfollow ?');"
                                                                            href="{{ route('sale.un_follow_up', ['id' => $row->id]) }}"
                                                                            style="padding: 5px;border-radius: 2px; background: #FF5722;color: #ffffff;"><i
                                                                                class="material-icons">close</i>Unfollow</a>
                                                                    @else
                                                                        <a href="javascript:void(0);"
                                                                            style="padding: 5px;border-radius: 2px; background: #FF5722;color: #382e2e;"
                                                                            disabled="disabled"><i
                                                                                class="material-icons">close</i>
                                                                            Unfollow</a>
                                                                    @endif
                                                                @else
                                                                    @if (isset($checkisadmin) || isset($UserPermision['sale.save_follow_up']))
                                                                        <a href="javascript:void(0);" data-toggle="modal"
                                                                            data-target="#follow_up"
                                                                            onclick="follow_up({{ $row->id }})"
                                                                            style="padding: 5px;border-radius: 2px; background: #009688;color: #ffffff;"><i
                                                                                class="material-icons">check_circle</i>Followup</a>
                                                                    @else
                                                                        <a href="javascript:void(0);"
                                                                            style="padding: 5px;border-radius: 2px; background: #009688;color: #382e2e;"
                                                                            disabled="disabled"><i
                                                                                class="material-icons">check_circle</i>
                                                                            Followup</a>
                                                                    @endif
                                                                @endif
                                                            </li>
                                                        </ol>

                                                    </td>
                                                </tr>
                                            @endif
                                            @php($saleid = $row->sale_id)
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-md-12 pull-right" style="margin-bottom: 0px;">
                                {{ $pay_list->appends(Request::get('page'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Task Info -->
        </div>
        @if ($checkisadmin)
            <div class="row clearfix">
                <!-- Line Chart -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>@lang('app.payment_and_interest_payment')</h2>
                        </div>
                        <div class="body">
                            <canvas id="line_chart" height="150"></canvas>
                        </div>
                    </div>
                </div>
                <!-- #END# Line Chart -->
                <!-- Bar Chart -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>@lang('app.investment')</h2>
                        </div>
                        <div class="body">
                            <canvas id="bar_chart" height="150"></canvas>
                        </div>
                    </div>
                </div>
                <!-- #END# Bar Chart -->
            </div>
        @endif
    </div>
    <div class="modal fade" id="view_payment" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="row clearfix" id="view_payment_form">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="follow_up" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="row clearfix" id="follow_up_form">

                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')

    <script src="{{ asset('plugins/chartjs/Chart.bundle.js') }}"></script>

@stop
