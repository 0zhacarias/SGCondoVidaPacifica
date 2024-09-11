@extends('layouts.app')

@section('content')
    <div class="container-login100">
        <div class="container">
            <div class="row justify-content-center ">
                <div class="col-md-5 ">
                    <div class="card ">
                        <div class="card-header">{{ __('Reset Password') }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="row mb-3">
                                    {{-- <label for="email" class="col-md-4 col-form-label text-md-end ">{{ __('Email Address') }}</label> --}}

                                    <div class="col-md-12">
                                        <input id="email" type="email"
                                            class="form-control input100 @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" required autocomplete="email"
                                            autofocus placeholder="E-mail">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-0">
                                    <div class=" container-login100-form-btn">
                                        <button type="submit" class="btn btn btn-w-800">
                                            {{ __('Send Password Reset Link') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
