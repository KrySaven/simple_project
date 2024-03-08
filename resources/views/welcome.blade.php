<!DOCTYPE html>
<html>


<!-- Mirrored from gurayyarar.github.io/AdminBSBMaterialDesign/pages/examples/sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 04 Jun 2018 04:34:14 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>Sign In | Bootstrap Based Admin Template - Material Design</title>
    <!-- Favicon-->
    <link rel="icon" href="../../favicon.ico" type="image/x-icon">

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
</head>
<!-- <style>
    .ckbox {
        border: 2px solid #2b982b;
        background-color: #2b982b; }
   
</style> -->
<body class="login-page" style="background-color: #009688;">
    @guest
    <div class="login-box">
        <div class="logo">
            <a href="javascript:void(0);">Admin<b>Login</b></a>
            <small>Admin Login - by 855Solution</small>
        </div>
        <div class="card">
            <div class="body">
                <form id="sign_in" method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                @csrf
                    <div class="msg">Sign in to start your session</div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                        <div class="form-line {{ $errors->has('email') ? ' is-invalid' : '' }}">
                            <input type="email" class="form-control" name="email" placeholder="E-mail" required autofocus>
                           
                        </div>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong style="color: red;">{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                        <div class="form-line {{ $errors->has('password') ? ' is-invalid' : '' }}">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-8 p-t-5">
                            <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                            <label for="rememberme">Remember Me</label>
                        </div>
                        <div class="col-xs-4">
                            <button class="btn btn-success waves-effect" type="submit">{{ __('Login') }}</button>
                        </div>
                    </div>
                    <!-- <div class="row m-t-15 m-b--20">
                        <div class="col-xs-6">
                            <a href="sign-up.html">Register Now!</a>
                        </div>
                        <div class="col-xs-6 align-right">
                            <a href="forgot-password.html">Forgot Password?</a>
                        </div>
                    </div> -->
                </form>
            </div>
        </div>
    </div>
    @else
    <section class="content">
        <div class="container-fluid">
            <div class="block-header" style="color: #fff !important;">
                <h2>BLANK PAGE</h2>
            </div>
        </div>
    </section>

    @endif

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
</body>


<!-- Mirrored from gurayyarar.github.io/AdminBSBMaterialDesign/pages/examples/sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 04 Jun 2018 04:34:15 GMT -->
</html>