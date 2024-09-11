@extends('layouts.app')

@section('content')
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Criar uma nova conta') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->

<main class="py-4">
    <section>
        <div class="header">
            <h1>SIG-COND
            </h1>
            <h6>
                Sistema de gestão financeira para o <br />
                condominio Vida pacífica
            </h6>
        </div>
        <div class="container-login">
            <div class="img-box">
                <img src="../img/condologin.jpg" alt="">
            </div>


            <div class="content-box">
                <div class="form-box">
                    <div class="login">
                        <h2>Entrar</h2>
                        <h6>Tenha o acesso na sua conta e tenha o controle das suas responsabilidades </h6>

                    </div>
                    <form role="form" method="POST" action="{{ route('login') }}" class="mt-4">
                        <!-- Form -->
                        <div class="input-box">
                            <div class="">
                                @csrf
                                <label for="email">E-mail</label>
                                <div class="">

                                    <input type="email" class="" name="email" id="email" placeholder="example@company.com" value="{{ old('email') }}" aria-label="Email" aria-describedby="email-addon" autofocus required />
                                </div>

                                @error('email')
                                <div class="">
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="input-box">

                            <div class="">
                                <label for="password">Palavra-passe</label>
                                <div class="">

                                    <input type="password" placeholder="Password" name="password" required aria-label="Password" aria-describedby="password-addon" class="@error('password') is-invalid @enderror" id="password" required />
                                </div>
                                {{-- @error('password')
                                <div class="invalid-feedback">{{ $message }}
                            </div>
                            @enderror --}}
                        </div>
                </div>
                <!-- End of Form -->
                <div class="remember">
                    <div class="">
                        <label for="vehicle3">
                            <input type="radio" id="vehicle3" name="vehicle3" value="Boat">
                            Relembra-me</label>
                    </div>
                    <div>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-right">Perdeu a
                            Palavra-passe?</a>
                        @endif
                    </div>
                </div>

                 <div class="input-box">
                    <button type="submit" class="" id="">
                        {{ __('Entrar') }}
                    </button>
                </div>
                </form>
                <a class="nav-link" href="{{ route('register') }}">{{ __('Criar uma conta') }}</a>
            </div>
        </div>
    </section>

</main>

@endsection
