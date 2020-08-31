<!DOCTYPE html>
<!--
Item Name: Elisyam - Web App & Admin Dashboard Template
Version: 1.5
Author: SAEROX

** A license must be purchased in order to legally use this template for your project **
-->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{config('app.name')}} - Login</title>
        <meta name="description" content="manoar">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Google Fonts -->
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
        <script>
          WebFont.load({
            google: {"families":["Montserrat:400,500,600,700","Noto+Sans:400,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('admin/img/apple-touch-icon.png')}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{asset('admin/img/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{asset('admin/img/favicon-16x16.png')}}">
        <!-- Stylesheet -->
        <link rel="stylesheet" href="{{asset('admin/vendors/css/base/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('admin/vendors/css/base/elisyam-1.5.css')}}">
        <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
    </head>
    <body class="bg-white">
        <!-- Begin Preloader -->
        <div id="preloader">
            <div class="canvas">
                <img src="{{asset('admin/img/logo.png')}}" alt="logo" class="loader-logo">
                <div class="spinner"></div>
            </div>
        </div>
        <!-- End Preloader -->
        <!-- Begin Container -->
        <div class="container-fluid no-padding h-100">
            <div class="row flex-row h-100 bg-white">
                <!-- Begin Left Content -->
                <div class="col-xl-8 col-lg-6 col-md-5 no-padding">
                    <div class="elisyam-bg background-01">
                        <div class="elisyam-overlay overlay-01"></div>
                        <div class="authentication-col-content mx-auto">
                            <h1 class="gradient-text-01">
                                Welcome To {{config('app.name')}}!
                            </h1>
                            <span class="description">
                                Etiam consequat urna at magna bibendum, in tempor arcu fermentum vitae mi massa egestas.
                            </span>
                        </div>
                    </div>
                </div>
                <!-- End Left Content -->
                <!-- Begin Right Content -->
                <div class="col-xl-4 col-lg-6 col-md-7 my-auto no-padding">
                    <!-- Begin Form -->
                    <div class="authentication-form mx-auto">
                        <div class="logo-centered">
                            <a href="db-default.html">
                                <img src="{{asset('admin/img/logo.png')}}" alt="logo">
                            </a>
                        </div>
                        <h3>Sign In To {{config('app.name')}}</h3>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="group material-input">
							    <input type="text" name="email" value="admin@sms.com" required autocomplete="off" autofocus class=" @error('email') is-invalid @enderror">
							    <span class="highlight"></span>
							    <span class="bar"></span>
                                <label>Email</label>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="group material-input">
							    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" value="password">
							    <span class="highlight"></span>
							    <span class="bar"></span>
                                <label>Password</label>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        <div class="row">
                            <div class="col text-left">
                                <div class="styled-checkbox">
                                    <input type="checkbox" name="checkbox" id="remeber">
                                    <label for="remeber">Remember me</label>
                                </div>
                            </div>
                            <div class="col text-right">
                                @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">
                                    {{ __('Forgot Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="sign-btn text-center">
                            <button type="submit" class="btn btn-lg btn-gradient-01">Sign in</button>
                        </div>
                        </form>
                        {{-- <div class="register">
                            Don't have an account?
                            <br>
                            <a href="pages-register.html">Create an account</a>
                        </div> --}}
                    </div>
                    <!-- End Form -->
                </div>
                <!-- End Right Content -->
            </div>
            <!-- End Row -->
        </div>
        <!-- End Container -->
        <!-- Begin Vendor Js -->
        <script src="{{asset('admin/vendors/js/base/jquery.min.js')}}"></script>
        <script src="{{asset('admin/vendors/js/base/core.min.js')}}"></script>
        <!-- End Vendor Js -->
        <!-- Begin Page Vendor Js -->
        <script src="{{asset('admin/vendors/js/app/app.min.js')}}"></script>
        <!-- End Page Vendor Js -->
    </body>
</html>
