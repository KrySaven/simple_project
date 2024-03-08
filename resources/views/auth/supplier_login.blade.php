
@php
    use App\Siteprofile;
    use App\Helpers\MyHelper;
    $siteprofile = Siteprofile::get()->first();
    $icon = '';
    $title ='Starts Cambodia';
    $company ='Starts Cambodia';
    $logo =asset('images/logo.png');
    if($siteprofile){
        if(file_exists($siteprofile->icon)){
            $icon = asset($siteprofile->icon);
        }
        if(file_exists($siteprofile->logo)){
            $logo = asset($siteprofile->logo);
        }
    $title = $siteprofile->site_name;
    $company = $siteprofile->company;
        }

@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sign In | {{ $title }} </title>
    <!-- Favicon-->
    <link rel="icon" href="{{ $icon.'?'.time() }}" type="image/x-png">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- Bootstrap Core Css -->
    <link href="{{asset('plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet">
    <!-- Waves Effect Css -->
    <link href="{{asset('plugins/node-waves/waves.css')}}" rel="stylesheet" />
    <!-- Animation Css -->
    <link href="{{asset('plugins/animate-css/animate.css')}}" rel="stylesheet" />
    <!-- Custom Css -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/custom.css')}}" rel="stylesheet">
    <!-- Multi Select Css -->
    <link href="{{asset('plugins/multi-select/css/multi-select.css')}}" rel="stylesheet">
     <!-- Bootstrap Select Css -->
    <link href="{{asset('plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
    <style>
        .bootstrap-select .dropdown-toggle:focus {
            outline: unset !important;
            outline: unset !important;
        }
    </style>
</head>
<body class="login-page" style="background-color: #009688;background: url({{ asset('assets/background_stock.jpg') }}) no-repeat center center fixed;-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;">
    <div class="login-box">
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST" action="{{ route('supp.loginfn') }}" aria-label="{{ __('Login') }}">
                @csrf
                    <div class="row" style="display: flex; justify-content: center;">
                         <img src="{{ $logo }}" style="width: 50%;padding-bottom: 20px;">
                    </div>
                    <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line {{ $errors->has('email') ? ' is-invalid' : '' }}">
                            <input type="email" class="form-control" name="email" placeholder="E-mail" autofocus value="{!! old('email') !!}">
                        </div>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <label id="email-error" class="error" for="email">{{ $errors->first('email') }}</label>
                            </span>
                        @endif
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line {{ $errors->has('password') ? ' is-invalid' : '' }}">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <label id="password-error" class="error" for="password">{{ $errors->first('password') }}</label>
                            </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-success waves-effect" type="submit"><span>{{ __('LOG IN') }} </span><i class="material-icons">send</i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Jquery Core Js -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap Core Js -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.js')}}"></script>
    <!-- Waves Effect Plugin Js -->
    <script src="{{asset('plugins/node-waves/waves.js')}}"></script>
    <!-- Validation Plugin Js -->
    <script src="{{asset('plugins/jquery-validation/jquery.validate.js')}}"></script>
    <!-- Custom Js -->
    <script src="{{asset('js/admin.js')}}"></script>
    <script src="{{asset('js/pages/examples/sign-in.js')}}"></script>
    <!-- Select Plugin Js -->
    <script src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>
    <script src="{{ asset('js/pages/forms/advanced-form-elements.js') }}"></script>
</body>
</html>
