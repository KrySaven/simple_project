@php
    use App\Helpers\MyHelper;
    $UserPermision = MyHelper::UserPermision();
    $checkisadmin = MyHelper::checkisadmin();
    $siteprofile = Session::get('siteprofile');

    $icon = '';
    $logo = '';
    $title = 'Admin 855 Solution';
    if ($siteprofile) {
        if (file_exists($siteprofile->icon)) {
            $icon = asset($siteprofile->icon);
        }
        if (file_exists($siteprofile->logo)) {
            $logo = asset($siteprofile->logo);
        }
        $title = $siteprofile->site_name;
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
    {{-- <title>Welcome To | Bootstrap Based Admin Template - Material Design</title> --}}
    <title>@yield('title') | {{ $title }}</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ $icon }}" type="image/x-png">
    {{--
    <link rel="icon" href="{{ $icon.'?'.time() }}" type="image/x-icon"> --}}
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
    <link href="{{ asset('public/css/my_custom.css') }}" rel="stylesheet">
    <!-- JQuery DataTable Css -->

    <script src="{{ asset('plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>

    <!-- Colorpicker Css -->
    <link href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css') }}" rel="stylesheet" />
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('css/themes/all-themes.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/toastr/css/toastr.css') }}" rel="stylesheet" />
    <meta name="csrf-token" content="example-content" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css"
        integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('public/css/custom_style.css') }}">
</head>
<style>
    @font-face {
        font-family: 'khmer_os';
        src: url('{{ asset('font/KhmerOS.ttf') }}') format('truetype');
    }

    @font-face {
        font-family: khmerOSmuollight;
        src: url('{{ asset('font/Moul-Regular.ttf') }}') format('truetype');
    }

    @font-face {
        font-family: 'Roboto', 'Khmer OS Battambong';
        src: url('{{ asset('css/fonts/KhmerOSbattambang.ttf') }}') format("truetype");
    }
</style>

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
                    <img src="{{ $logo }}" alt="" style="max-height: 55px;">
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
                    {{-- <li><a href="javascript:void(0);" class="js-search" data-close="true"><i
                                class="material-icons">search</i></a></li> --}}
                    <li style="white-space: nowrap;width: 143px;">
                        <a href="javascript:void(0);" style="color: #217201;" class="dropdown-toggle"
                            data-toggle="dropdown" role="button">
                            <i class="material-icons" style="color: #217201;">access_time</i> <span
                                style="position: absolute;margin-top: 3px;color: red;font-size: 12px;"
                                id="clock">{!! date('d/m/Y H:i:s ') !!}</span>
                        </a>

                    </li>
                    <!-- #END# Call Search -->

                    <!-- <li class="pull-right"><a href="javascript:void(0);" class="js-right-sidebar" data-close="true"><i class="material-icons">more_vert</i></a></li> -->
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
                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
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
            @php
                $user = Auth::user();
                $url = '';
                if (file_exists($user->profile)) {
                    $url = asset($user->profile);
                } else {
                    $url = asset('images/user.png');
                }

            @endphp
            @php
                $bg = asset('assets/user-img-background (1).png');
            @endphp
            <div class="user-info"
                style="text-align: center;background-image: linear-gradient(to right, rgb(14 78 130), rgb(0 67 255 / 10%)) !important;background-image:url('{{ $bg }}') !important">
                <div class="image">
                    <img src="{{ $url }}" width="70" height="70" alt="User" />
                    <p style="color: #ffffff;margin-top: 10px;">{{ Auth::user()->name }}</p>
                </div>
                <div class="info-container" style="margin-top: -40px">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                    {{-- <div class="email"> {{ Auth::user()->email }}</div> --}}
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="{{ route('user.change', ['id' => Auth::user()->id]) }}"><i
                                        class="material-icons">vpn_key</i>Change Password</a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i
                                        class="material-icons">input</i> {{ __('Logout') }}</a></li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
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
                        <a href="{{ url('/home') }}">
                            <i class="material-icons">home</i>
                            <span>@lang('app.home')</span>
                        </a>
                    </li>

                    <li class="@if (Route::is('product.create') ||
                            Route::is('products') ||
                            Route::is('product.create') ||
                            Route::is('product.edit') ||
                            Route::is('categories') ||
                            Route::is('category.create') ||
                            Route::is('category.edit') ||
                            Route::is('sizes') ||
                            Route::is('size.create') ||
                            Route::is('size.edit') ||
                            Route::is('colors') ||
                            Route::is('color.create') ||
                            Route::is('color.edit') ||
                            Route::is('unit.index') ||
                            Route::is('unit.create') ||
                            Route::is('unit.edit') ||
                            Route::is('unit.update') ||
                            Route::is('unit.destroy')) active @endif">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">card_giftcard</i>
                            <span>@lang('app.product_setup')</span>
                        </a>
                        <ul class="ml-menu">
                            @if (isset($checkisadmin) || isset($UserPermision['categories']))
                                <li class="@if (Route::is('categories') || Route::is('category.create') || Route::is('category.edit')) active @endif">
                                    <a href="{{ route('categories') }}">@lang('app.category')</a>
                                </li>
                            @endif

                            @if (isset($checkisadmin) || isset($UserPermision['unit.index']))
                                <li class="@if (Route::is('unit.index') || Route::is('unit.create') || Route::is('unit.edit')) active @endif">
                                    <a href="{{ route('unit.index') }}">@lang('app.unit')</a>
                                </li>
                            @endif

                            @if (isset($checkisadmin) || isset($UserPermision['sizes']))
                                <li class="@if (Route::is('sizes') || Route::is('size.create') || Route::is('size.edit')) active @endif">
                                    <a href="{{ route('sizes') }}">@lang('app.size')</a>
                                </li>
                            @endif
                            @if (isset($checkisadmin) || isset($UserPermision['colors']))
                                <li class="@if (Route::is('colors') || Route::is('color.create') || Route::is('color.edit')) active @endif">
                                    <a href="{{ route('colors') }}">@lang('app.color')</a>
                                </li>
                            @endif
                            @if (isset($checkisadmin) || isset($UserPermision['products']))
                                <li class="@if (Route::is('products') || Route::is('product.create') || Route::is('product.edit')) active @endif">
                                    <a href="{{ route('products') }}">@lang('app.product')</a>
                                </li>
                            @endif
                        </ul>
                    </li>


                    {{-- Purchase Return --}}
                    @if (isset($checkisadmin) ||
                            isset($UserPermision['purchase_return.create']) ||
                            isset($UserPermision['purchase_returns']))
                        <li class="@if (Route::is('purchase_return.create', 'purchase_returns', 'purchase_return.show', 'purchase_return.edit')) active @endif">
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">account_box</i>
                                <span>@lang('app.purchase_return')</span>
                            </a>
                            <ul class="ml-menu">
                                @if (isset($checkisadmin) || isset($UserPermision['purchase_return.create']))
                                    <li class="@if (Route::is('purchase_return.create')) active @endif">
                                        <a href="{{ route('purchase_return.create') }}">@lang('app.create')</a>
                                    </li>
                                @endif
                                @if (isset($checkisadmin) || isset($UserPermision['purchase_returns']))
                                    <li class="@if (Route::is('purchase_returns', 'purchase_return.edit', 'purchase_return.show')) active @endif">
                                        <a href="{{ route('purchase_returns') }}">@lang('app.list')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif


                    @if (isset($checkisadmin) || isset($UserPermision['purchase.create']) || isset($UserPermision['purchases']))
                        <li class="@if (Route::is('purchase.create', 'purchases', 'purchase.show', 'purchase.edit')) active @endif">
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">account_box</i>
                                <span>@lang('app.purchase')</span>
                            </a>
                            <ul class="ml-menu">
                                @if (isset($checkisadmin) || isset($UserPermision['purchase.create']))
                                    <li class="@if (Route::is('purchase.create')) active @endif">
                                        <a href="{{ route('purchase.create') }}">@lang('app.create')</a>
                                    </li>
                                @endif
                                @if (isset($checkisadmin) || isset($UserPermision['purchases']))
                                    <li class="@if (Route::is('purchases', 'purchase.edit', 'purchase.show')) active @endif">
                                        <a href="{{ route('purchases') }}">@lang('app.list')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif


                    @if (isset($checkisadmin) || isset($UserPermision['supplier.create']) || isset($UserPermision['suppliers']))
                        <li class="@if (Route::is('supplier.create') || Route::is('suppliers')) active @endif">
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">account_box</i>
                                <span>@lang('app.supplier')</span>
                            </a>
                            <ul class="ml-menu">
                                @if (isset($checkisadmin) || isset($UserPermision['supplier.create']))
                                    <li class="@if (Route::is('supplier.create')) active @endif">
                                        <a href="{{ route('supplier.create') }}">@lang('app.create')</a>
                                    </li>
                                @endif
                                @if (isset($checkisadmin) || isset($UserPermision['suppliers']))
                                    <li class="@if (Route::is('suppliers') || Route::is('supplier.edit')) active @endif">
                                        <a href="{{ route('suppliers') }}">@lang('app.supplier')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    <li class="@if (Route::is('exchanges') ||
                            Route::is('exchange.create') ||
                            Route::is('currencies') ||
                            Route::is('currency.create') ||
                            Route::is('currency.edit') ||
                            Route::is('exchange.edit')) active @endif">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">monetization_on</i>
                            <span>@lang('app.money_setup')</span>
                        </a>
                        <ul class="ml-menu">
                            @if (isset($checkisadmin) || isset($UserPermision['exchanges']))
                                <li class="@if (Route::is('exchanges') || Route::is('exchanges.create')) active @endif">
                                    <a href="{{ route('exchanges') }}">@lang('app.exchanges')</a>
                                </li>
                            @endif
                            @if (isset($checkisadmin) || isset($UserPermision['currencies']))
                                <li class="@if (Route::is('currencies') || Route::is('currency.create')) active @endif">
                                    <a href="{{ route('currencies') }}">@lang('app.currency')</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    @if (isset($checkisadmin) || isset($UserPermision['siteprofiles']))
                        <li class="@if (Route::is('siteprofiles') || Route::is('exchange.create')) active @endif">
                            <a href="javascript:void(0);" class="menu-toggle">
                                <i class="material-icons">settings</i>
                                <span>@lang('app.setting')</span>
                            </a>
                            <ul class="ml-menu">
                                @if (isset($checkisadmin) || isset($UserPermision['siteprofiles']))
                                    <li class="@if (Route::is('siteprofiles')) active @endif">
                                        <a href="{{ route('siteprofiles') }}">@lang('app.site_profile')</a>
                                    </li>
                                @endif

                                @if (isset($checkisadmin) || isset($UserPermision['status.index']))
                                    <li class="@if (Route::is('status.index') || Route::is('status.edit') || Route::is('status.create')) active @endif">
                                        <a href="{{ route('status.index') }}">@lang('app.status')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if (isset($checkisadmin) ||
                            isset($UserPermision['usergroup.create']) ||
                            isset($UserPermision['usergroups']) ||
                            isset($UserPermision['user.create']) ||
                            isset($UserPermision['users']))
                        <li class="@if (Route::is('usergroup.create') ||
                                Route::is('usergroups') ||
                                Route::is('user.create') ||
                                Route::is('users') ||
                                Route::is('user.edit') ||
                                Route::is('usergroup.edit')) active @endif">
                            <a href="javascript:void(0);"
                                class="menu-toggle @if (Route::is('usergroup.create') || Route::is('usergroups') || Route::is('user.create') || Route::is('users')) waves-block toggled @endif">
                                <i class="material-icons">people</i>
                                <span>@lang('app.user')</span>
                            </a>
                            <ul class="ml-menu">
                                @if (isset($checkisadmin) || isset($UserPermision['usergroup.create']))
                                    <li class="@if (Route::is('usergroup.create')) active @endif">
                                        <a href="{{ route('usergroup.create') }}">@lang('app.create_user_group')</a>
                                    </li>
                                @endif
                                @if (isset($checkisadmin) || isset($UserPermision['usergroups']))
                                    <li class="@if (Route::is('usergroups') || Route::is('usergroup.edit')) active @endif">
                                        <a href="{{ route('usergroups') }}">@lang('app.user_group')</a>
                                    </li>
                                @endif
                                @if (isset($checkisadmin) || isset($UserPermision['user.create']))
                                    <li class="@if (Route::is('user.create')) active @endif">
                                        <a href="{{ route('user.create') }}">@lang('app.create_user')</a>
                                    </li>
                                @endif
                                @if (isset($checkisadmin) || isset($UserPermision['users']))
                                    <li class="@if (Route::is('users') || Route::is('user.edit')) active @endif">
                                        <a href="{{ route('users') }}">@lang('app.user')</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif


                </ul>
            </div>

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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    {{-- Google Map --}}
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

    <!-- Sparkline Chart Plugin Js -->
    <script src="{{ asset('plugins/jquery-sparkline/jquery.sparkline.js') }}"></script>
    <script src="{{ asset('plugins/toastr/js/toastr.js') }}"></script>
    <!-- Custom Js -->
    <!-- <script src="{{ asset('js/pages/forms/form-validation.js') }}"></script> -->
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/datetime.js') }}"></script>
    <!-- Demo Js -->
    <script src="{{ asset('js/demo.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
        integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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

        //  =========== disable inspect element ===========
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
    <modal></modal>
</body>

</html>
