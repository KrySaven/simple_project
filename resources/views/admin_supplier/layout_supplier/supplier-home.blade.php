@php
    use App\Helpers\MyHelper;
    $user = Auth::guard('supplieradmin')->user();
    $siteprofile = Session::get('siteprofile');
    // dd($siteprofile);
    $icon = '';
    $logo = '';
    $title = 'Admin 855 Solution';
    if ($user) {
        if (file_exists($user->profile)) {
            $profile = asset($user->profile);
        } else {
            $profile = asset('images/user.png');
        }
        $title = $user->name;
    }
@endphp
<!DOCTYPE html>
<html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ $title }}</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ $icon }}" type="image/x-png">
    <!-- Google Fonts -->
    <link href="{{ asset('css/googleapis.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/googleapis_s.css') }}" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Bayon' rel='stylesheet' type='text/css'>

    <!-- Bootstrap Core Css -->
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="{{ asset('plugins/node-waves/waves.css') }}" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="{{ asset('/plugins/animate-css/animate.css') }}" rel="stylesheet" />
    <!-- Colorpicker Css -->
    <link href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css') }}" rel="stylesheet" />
    <!-- Bootstrap Material Datetime Picker Css -->
    <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}"
        rel="stylesheet" />
    <!-- Wait Me Css -->
    <link href="{{ asset('plugins/waitme/waitMe.css') }}" rel="stylesheet" />
    <!-- Dropzone Css -->
    <link href="{{ asset('plugins/dropzone/dropzone.css') }}" rel="stylesheet">
    <!-- Multi Select Css -->
    <link href="{{ asset('plugins/multi-select/css/multi-select.css') }}" rel="stylesheet">

    <!-- Bootstrap Spinner Css -->
    <link href="{{ asset('plugins/jquery-spinner/css/bootstrap-spinner.css') }}" rel="stylesheet">

    <!-- Bootstrap Tagsinput Css -->
    <link href="{{ asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet">

    <!-- Bootstrap Select Css -->
    <link href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />

    <!-- noUISlider Css -->
    <link href="{{ asset('plugins/nouislider/nouislider.min.css') }}" rel="stylesheet" />
    <!-- Morris Chart Css-->
    <link href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/morrisjs/morris.css') }}" rel="stylesheet" />
    <!-- Custom Css -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <!-- JQuery DataTable Css -->
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>

    <!-- Colorpicker Css -->
    <link href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css') }}" rel="stylesheet" />
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('css/themes/all-themes.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/toastr/css/toastr.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('public/css/custom_style.css') }}">
</head>



<body class="theme-red" style="font-family: Roboto, 'Khmer OS Battambong','Khmer OS Battambang Regular';">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>@lang('app.please_wait')</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar" style="background-color: #ffffff;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" style="color: #217201;" class="navbar-toggle collapsed"
                    data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars" style="color: #217201;"></a>
                <a class="navbar-brand" href="{{ url('/home') }}" style="padding: 0px 15px !important;">
                    {{-- {{ $title }} SYSTEM --}}
                    <img src="{{ asset('assets/payback_report.png') }}" alt="" style="max-height: 55px;">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    {{-- Language --}}
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            @php
                                $img_en = asset('/images/en.jpg');
                                $img_kh = asset('/images/currency/kh.jpg');
                            @endphp
                            @switch(app()->getLocale())
                                @case('en')
                                    <img src="{{ $img_en }}" width="25">
                                    <label class="pointer" for="en"
                                        style="color:black;">{{ __('app.english') }}</label>
                                @break

                                @case('kh')
                                    <img src="{{ $img_kh }}" width="25">
                                    <label class="pointer" for="kh" style="color:black;">{{ __('app.khmer') }}</label>
                                @break
                            @endswitch
                            <span class="caret"></span>
                            {{-- @endif --}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <ul class="menu">
                                <li style="list-style-type: none;">
                                    <a class="dropdown-item" href="{{ url('locale/en') }}">
                                        <img src="{{ $img_en }}" width="25">
                                        <label class="pointer" for="en">{{ __('app.english') }}</label>
                                    </a>
                                </li>
                                <li style="list-style-type: none;">
                                    <a class="dropdown-item" href="{{ url('locale/kh') }}">
                                        <img src="{{ $img_kh }}" width="25">
                                        <label class="pointer" for="kh">{{ __('app.khmer') }}</label>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- Call Search -->
                    {{-- <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li> --}}
                    <li style="white-space: nowrap;width: 143px;">
                        <a href="javascript:void(0);" style="color: #217201;" class="dropdown-toggle"
                            data-toggle="dropdown" role="button">
                            <i class="material-icons" style="color: #217201;">access_time</i> <span
                                style="position: absolute;margin-top: 3px;color: red;font-size: 12px;"
                                id="clock">{!! date('d/m/Y H:i:s ') !!}</span>
                        </a>

                    </li>
                    <!-- #END# Call Search -->
                    <li>
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons" style="color: #217201;">notifications</i>
                            <span class="label-count">7</span>
                        </a>
                    </li>
                    <!-- #END# Notifications -->
                    <!-- Tasks -->
                    <li>
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons" style="color: #217201;">message</i>
                            <span class="label-count">9</span>
                        </a>
                    </li>
                    <!-- #END# Tasks -->
                    <li class="pull-right"><a href="javascript:void(0);"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                            class="js-right-sidebar"><i class="material-icons" style="color: #217201;">input</i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            {{-- @php
                $user = Auth::user();
                $url = '';
                if (file_exists($user->profile)) {
                    $url = asset($user->profile);
                } else {
                    $url = asset('images/user.png');
                }
            @endphp --}}
            @php
                $bg = asset('assets/user-img-background (1).png');
            @endphp
            <div class="user-info"
                style="no-repeat no-repeat !important; text-align: center;background-image: linear-gradient(to right, rgb(14 78 130), rgb(0 67 255 / 10%)) !important; background-image:url('{{ $bg }}') !important">
                <div class="image">
                    <img src="{{ $profile }}" width="70" height="70" alt="User" />
                    <p style="color: #ffffff;margin-top: 10px;">{{ $user->name }}</p>
                </div>
                <div class="info-container" style="margin-top: -40px">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                    <div class="email"> {{ $user->email }}</div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            {{-- <li><a href="{{ route('user.change', ['id' => Auth::user()->id]) }}"><i class="material-icons">vpn_key</i>Change Password</a></li> --}}
                            <li role="seperator" class="divider"></li>
                            <li><a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                        class="material-icons">input</i> {{ __('Logout') }}</a></li>
                            <form id="logout-form" action="{{ route('supplier.logOut') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">{{ strtoupper($title) }} SYSTEM</li>
                    <li class="active"></li>
                    <li class="@if (Route::is('home')) active @endif">
                        <a href="{{ url('/supplier/dashboard') }}">
                            <i class="material-icons">home</i>
                            <span>@lang('app.home')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('supplier.purchaseOrderList') }}" >
                            <i class="material-icons">list</i>
                            <span>Purchase Order</span>
                        </a>
                        <a href="{{ route('supplier.purchaseReturnList') }}" >
                            <i class="material-icons">list</i>
                            <span>Purchase Return</span>
                        </a>
                        {{-- <ul class="ml-menu">
                            <li>
                                <a href="{{ route('supplier.purchaseOrderList') }}">List</a>
                            </li>
                        </ul> --}}
                    </li>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2021 <a href="http://wintech.com.kh" target="_blank"
                        style="color: #002959 !important;">Wintech Cambodia</a> V.1.0.0
                </div>
            </div>
            <!-- #Footer -->
        </aside>
    </section>

    <section class="content">
        @yield('content')
    </section>
    <div class="modal fade" id="defaultModals" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="row clearfix" id="print_payment">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="imageProfile" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1463b0; color: #fff">
                    <h4 class="modal-title" id="imageProfileLabel">View Image</h4>
                </div>
                <div class="modal-body" id="imageProfileContentModal" style="min-height: 400px;padding: 5px 5px;">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect pull-right"
                        data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Google Map --}}
    {{-- <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script> <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1PdLQ0TgCjWuiUiTwxYiny83XSiu_qEw&callback=initMaps&libraries=&v=weekly" defer></script> --}}
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.js') }}"></script>

    <!-- Select Plugin Js -->
    <script src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="{{ asset('plugins/jquery-slimscroll/jquery.slimscroll.js') }}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ asset('plugins/node-waves/waves.js') }}"></script>

    <script src="{{ asset('plugins/autosize/autosize.js') }}"></script>

    <script type="text/javascript" src="{{ asset('plugins/jquery/printThis.js') }}"></script>
    <!-- Moment Plugin Js -->
    <script src="{{ asset('plugins/momentjs/moment.js') }}"></script>

    <!-- Bootstrap Material Datetime Picker Plugin Js -->
    <script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}">
    </script>
    <!-- Jquery CountTo Plugin Js -->
    <script src="{{ asset('plugins/jquery-countto/jquery.countTo.js') }}"></script>

    <!-- Morris Plugin Js -->
    <script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('plugins/morrisjs/morris.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <!-- ChartJs -->
    <script src="{{ asset('plugins/chartjs/Chart.bundle.js') }}"></script>

    <!-- Flot Charts Plugin Js -->
    {{--    <script src="{{ asset('plugins/flot-charts/jquery.flot.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/flot-charts/jquery.flot.resize.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/flot-charts/jquery.flot.pie.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/flot-charts/jquery.flot.categories.js') }}"></script> --}}
    {{-- <script src="{{ asset('plugins/flot-charts/jquery.flot.time.js') }}"></script> --}}

    <!-- Sparkline Chart Plugin Js -->
    <script src="{{ asset('plugins/jquery-sparkline/jquery.sparkline.js') }}"></script>
    <script src="{{ asset('plugins/toastr/js/toastr.js') }}"></script>
    <!-- Custom Js -->
    <!-- <script src="{{ asset('js/pages/forms/form-validation.js') }}"></script> -->



    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/datetime.js') }}"></script>

    <!-- Demo Js -->
    <script src="{{ asset('js/demo.js') }}"></script>
    <script type="text/javascript">
        $('#date,#date_from,#date_to,#date_return,.date').bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false
        });
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            switch (type) {
                case 'info':
                    toastr.info("{{ Session::get('message') }}");
                    break;

                case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;

                case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    localStorage.removeItem("identity_number");
                    break;

                case 'error':
                    toastr.error("{{ Session::get('message') }}");
                    break;
            }
        @endif
        $('form').on('keydown', 'input', function(e) {
            if (e.keyCode == 13) {
                return false;
            }
        });
        $('form').submit(function() {
            $('.page-loader-wrapper').css({
                "display": "block"
            });
        });
        $(document).ajaxStart(function() {
            $('.page-loader-wrapper').css({
                "display": "block"
            });
        }).ajaxStop(function() {
            setTimeout(function() {
                $('.page-loader-wrapper').fadeOut();
            }, 50);
        });
        // Allow Numeric loan
        function numeric_only(evt) {
            var max_chars = 8;
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            evt.target.value = evt.target.value.replace(/[^0-9-.]/g, '')
            if (charCode > 31 && charCode > 57) {
                return false;
            } else {
                if (evt.target.value.length > 8) {
                    return false;
                }
                return true;
            }
        }
        $('#interest,#loan_amount,#saving,#loan_term,#operation_fee').on('input', function(evt) {
            numeric_only(evt);
        });

        $(document).ready(function() {
            $(":input").attr("autocomplete", "off");
            $(".select_focus").focus(function() {
                $(this).select();
            });
            $('form').on('keydown', '.password_key', function(e) {
                if (e.keyCode == 13) {
                    return false;
                }
            });
        });

        function post_id(id) {
            $('#change_id').val(id);
            $('.invalid-feedback').html(
                '<label id="password-error" class="error" for="password" style="display:none;"></label>');
        }

        function change_password() {
            $('.invalid-feedback').html(
                '<label id="password-error" class="error" for="password" style="display:none;"></label>');
            var form = $('#form_submit');
            var url = form.attr('action');
            $.ajax({
                type: form.attr('method'),
                url: url,
                data: form.serialize(),
                success: function(data) {
                    if (data.errors) {
                        if (data.errors.password) {
                            $('.invalid-feedback').html(
                                '<label id="password-error" class="error" for="password" style="display:block;">' +
                                data.errors.password + '</label>');
                        }
                    }
                    if (data.success) {
                        toastr.success(data.success);
                        window.setTimeout(function() {}, 9000);
                        location.reload();
                    }
                }
            });
        }

        function print_report() {
            $('#responsive').removeClass('table-responsive');
            $('.responsive').removeClass('table-responsive');
            $('#printarea').printThis({
                importStyle: true,
                importCSS: true
            });
        }

        function print_payment(id) {
            var payment_id = id;
            if (payment_id != '') {
                $.ajax({
                    url: "{{ route('sale.print_payment') }}",
                    type: "get",
                    data: {
                        'payment_id': payment_id,
                    },
                    dataType: 'html',
                    async: false,
                    success: function(data) {
                        $("#print_payment").html(data);
                        $('#responsive').removeClass('table-responsive');
                        $('#print_recipt').printThis({
                            importStyle: true,
                            importCSS: true,
                        });
                    }

                });
            }
        }

        function get_districts(select_class, provice_id, district_id) {
            if (provice_id) {
                $.ajax({
                    url: "{{ route('get_districts') }}",
                    type: "get",
                    data: {
                        'provice_id': provice_id,
                        'district_id': district_id,
                        'select_class': select_class
                    },
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                        $(".form_" + select_class).html(data.district);
                        $(document).find('select').selectpicker('refresh');
                    }

                });
            }
        }

        function get_communes(select_class, district_id, commune_id) {
            if (district_id) {
                $.ajax({
                    url: "{{ route('get_communes') }}",
                    type: "get",
                    data: {
                        'district_id': district_id,
                        'commune_id': commune_id,
                        'select_class': select_class
                    },
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                        $(".form_" + select_class).html(data.commune);
                        $(document).find('select').selectpicker('refresh');
                    }

                });
            }
        }

        function get_villages(select_class, commune_id, village_id) {
            if (commune_id) {
                $.ajax({
                    url: "{{ route('get_villages') }}",
                    type: "get",
                    data: {
                        'commune_id': commune_id,
                        'village_id': village_id,
                        'select_class': select_class
                    },
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                        $(".form_" + select_class).html(data.village);
                        $(document).find('select').selectpicker('refresh');
                    }

                });
            }
        }
        $('.view_image').click(function() {
            $('#imageProfile').modal();
            $('#imageProfileContentModal').html(`
                <a href="` + $(this).attr('src') + `" target="_blank">
                    <img width="100%"; height="auto" src="` + $(this).attr('src') + `" >
                </a>
            `)
        });

    </script>
    @yield('javascript')
    @stack('scripts')
    @stack('myscripts')
    <script src="{{ asset('js/table_resposive_button_action.js') }}"></script>
    <script src="{{ asset('js/pages/charts/chartjs.js') }}"></script>
    <modal></modal>
</body>

</html>
