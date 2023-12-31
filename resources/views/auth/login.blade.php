@extends('layouts.app')

@section('content')
    <div class="wrap-login100">
        <div class="login100-form-title" style="background-image: url({{ url('/') }}/login_assets/images/bg-01-01.jpg);">
            <span class="login100-form-title-1" style="-webkit-text-stroke: 2px black; color:green">
                Smart Job
            </span>
        </div>


        <form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="wrap-input100 validate-input m-b-26" data-validate="Email is required">
                <span class="label-input100">Email</span>
                <input id="email" type="email" class="input100 @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" placeholder="Enter Email" required autocomplete="email" autofocus>
                <span>
                    @error('email')
                        <span role="alert" style="font-size: 14px;font-weight: 300;color: red;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </span>
            </div>

            <div class="wrap-input100 validate-input m-b-18" data-validate="Password is required">
                <span class="label-input100">Password</span>
                <input id="password" type="password" class="input100 @error('password') is-invalid @enderror" name="password"
                    placeholder="Enter password" required autocomplete="current-password">
                <span>
                    @error('password')
                        <span role="alert" style="font-size: 14px;font-weight: 300;color: red;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </span>
            </div>

            <div class="flex-sb-m w-full p-b-30">
                <div class="contact100-form-checkbox">
                    {{-- @if (Route::has('password.request'))
                        <a class="txt1" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif --}}
                </div>
                <div>
                    {{-- <a href="{{url('/register')}}" style="font-family: Poppins-Regular; font-size: 13px; line-height: 1.4; color: #57b846;">Create a New Account</a> --}}
                </div>
            </div>


            <div class="container-login100-form-btn">
                <button type="submit" class="login100-form-btn">
                    Login
                </button>
            </div>
        </form>


    </div>
@endsection
