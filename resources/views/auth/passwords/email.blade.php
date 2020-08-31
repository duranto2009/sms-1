<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{config('app.name')}} - Reset</title>
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
        <!-- Tweaks for older IEs-->
        <!--[if lt IE 9]>
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
        <div class="container-fluid h-100 overflow-y">
            <div class="row flex-row h-100">
                <div class="col-12 my-auto">
                    <div class="password-form mx-auto">
                        <div class="logo-centered">
                            <a href="db-default.html">
                                <img src="{{asset('admin/img/logo.png')}}" alt="logo">
                            </a>
                        </div>
                        <h3>Password Recovery</h3>
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                            <div class="group material-input">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <span class="highlight"></span>
                                <span class="bar"></span>
                                <label>Email</label>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="button text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                    </form>
                        <div class="back">
                            <a href="{{route('login')}}">Sign In</a>
                        </div>
                    </div>
                </div>
                <!-- End Col -->
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
