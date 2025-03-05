@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('LOGO.ico') }}">
    @section('css')
    <style>
        /* Body styles with animated linear gradient background */
        body {
            background: linear-gradient(135deg, 
                rgb(192, 196, 116), 
                rgb(73, 152, 190), 
                rgb(23,222,61,1), 
                rgb(151, 158, 83),
                rgb(43, 107, 140), 
                rgb(26, 103, 52),
                rgb(12, 61, 78), 
                rgb(120, 165, 73), 
                rgb(4, 84, 164)
            );
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
            font-family: 'Arial', sans-serif;
            color: #333;
            height: 100vh;
            margin: 0;
        }

        /* Gradient animation to create a flowing effect */
        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Main login container */
        .login-box {
            width: 400px;
            margin-top: 50px;
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white background for the form */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Form title */
        .login-box .card-header {
            background-color: #0056b3;  /* Darker blue */
            color: #fff;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        /* Form fields */
        .form-control {
            border-radius: 5px;
            box-shadow: none;
            border: 1px solid #ced4da;
            margin-bottom: 15px;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Input group */
        .input-group-text {
            background-color: #f4f6f9;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        /* Button styles */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-block {
            width: 100%;
            padding: 12px;
            text-align: center;
        }

        /* Remember me checkbox */
        .icheck-primary input[type="checkbox"]:checked ~ .icheck-label {
            color: #007bff;
        }

        /* Forgot password link */
        .forgot-password-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .forgot-password-link a {
            color: #007bff;
            text-decoration: none;
        }

        .forgot-password-link a:hover {
            text-decoration: underline;
        }

        /* Responsive design */
        @media (max-width: 767px) {
            .login-box {
                width: 100%;
                padding: 15px;
            }
        }
    </style>


@stop

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
@endif

@section('auth_header', __('adminlte::adminlte.login_message'))

@section('auth_body')
    <form action="{{ $login_url }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="{{ __('adminlte::adminlte.password') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Login field --}}
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label for="remember">
                        {{ __('adminlte::adminlte.remember_me') }}
                    </label>
                </div>
            </div>

            <div class="col-5">
                <button type=submit class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.sign_in') }}
                </button>
            </div>
        </div>

    </form>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    <!-- @if($password_reset_url)
        <p class="my-0">
            <a href="{{ $password_reset_url }}">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
    @endif -->

    {{-- Register link --}}
    <!-- @if($register_url)
        <p class="my-0">
            <a href="{{ $register_url }}">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif -->
@stop
