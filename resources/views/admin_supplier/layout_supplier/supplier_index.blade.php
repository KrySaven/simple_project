@extends('admin_supplier.layout_supplier.supplier-home')
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
        }
    </style>
    <div class="container-fluid">
        <div class="block-header">
            {{-- <h2>@lang('app.dashboard')</h2> --}}
        </div>
        {{-- @if ($checkisadmin) --}}
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-pink hover-expand-effect show_number">
                    <div class="content">
                        <div class="number count-to" data-from="0" data-to="50" data-speed="1000" data-fresh-interval="20">
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

        {{-- @endif --}}
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
                        </div>

                    </div>
                </div>
            </div>
            <!-- #END# Task Info -->
        </div>
        {{-- @if ($checkisadmin) --}}
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
        {{-- @endif --}}
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
    <script type="text/javascript">
        $(function() {
            new Chart(document.getElementById("line_chart").getContext("2d"), getChartJs('line'));
            new Chart(document.getElementById("bar_chart").getContext("2d"), getChartJs('bar'));
        });

        function payNow(el) {
            var url = $(el).attr('url');
            $.ajax({
                type: "GET",
                url: url,
                data: {},
                dataType: "HTML",
                success: function(response) {
                    if ($(".paynow-modal").length == 0) {
                        $("html").append(response);
                    }
                    $(".paynow-modal").modal({
                        backdrop: "static",
                        keyboard: !1
                    });
                    $('.datepicker').bootstrapMaterialDatePicker({
                        format: 'DD-MM-YYYY',
                        weekStart: 0,
                        clearButton: true,
                        time: false
                    }).on('change', function(e, date) {
                        penalty();
                    });
                    $(document).find('select').selectpicker('refresh');
                }
            });
        }
    </script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="{{ asset('/') }}plugins/jquery-countto/jquery.countTo.js"></script>

    <!-- Morris Plugin Js -->
    <script src="{{ asset('/') }}plugins/raphael/raphael.min.js"></script>
    <script src="{{ asset('/') }}plugins/morrisjs/morris.js"></script>

    <!-- ChartJs -->
    <script src="{{ asset('/') }}plugins/chartjs/Chart.bundle.js"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="{{ asset('/') }}plugins/flot-charts/jquery.flot.js"></script>
    <script src="{{ asset('/') }}plugins/flot-charts/jquery.flot.resize.js"></script>
    <script src="{{ asset('/') }}plugins/flot-charts/jquery.flot.pie.js"></script>
    <script src="{{ asset('/') }}plugins/flot-charts/jquery.flot.categories.js"></script>
    <script src="{{ asset('/') }}plugins/flot-charts/jquery.flot.time.js"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="{{ asset('/') }}plugins/jquery-sparkline/jquery.sparkline.js"></script>

    <!-- Custom Js -->
    <script src="{{ asset('/') }}js/pages/index.js"></script>
@stop
