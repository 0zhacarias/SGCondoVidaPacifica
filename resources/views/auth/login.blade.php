@extends('layouts.app')

@section('content')
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

<!-- 
  <main>
       
        <section class="containerFF mt-5 mt-lg-0 bg-soft d-flex align-items-center">
            <div class="login-formff">
                <div class="text-center text-md-center mb-4 mt-md-0" style="contain: inline-size;">
                    <img src="../assets/img/LogoM/gphoto.png" alt="IMG" style="width: 50%;">
                </div>
                <form role="form" method="POST" action="{{ route('login') }}" class="mt-4">
                 
                    <div class="form-group">
                        <div class="form-group mb-4">
                            @csrf
                            <label for="email">E-mail</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1"><svg class="icon icon-xs text-bgreen-600"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                        </path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg></span>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="example@company.com" value="{{ old('email') }}" aria-label="Email"
                                    aria-describedby="email-addon" autofocus required />
                            </div>

                            @error('email')
                                <div class="pb-5">
                                    <div class="invalid-feedback" style="position: absolute;">
                                        {{ $message }}</div>
                                </div>
                            @enderror
                        </div>
                    </div>
                  
                    <div class="form-group">
                      
                        <div class="form-group mb-4">
                            <label for="password">Palavra-passe</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon2"><svg class="icon icon-xs text-bgreen-600"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"></path>
                                    </svg></span>
                                <input type="password" placeholder="Password" name="password" required aria-label="Password"
                                    aria-describedby="password-addon"
                                    class="form-control @error('password') is-invalid @enderror" id="password" required />
                            </div>
                            {{-- @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror --}}
                        </div>
                      
                        <div class="d-flex justify-content-between align-items-top mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="remember" />
                                <label class="form-check-label mb-0 pe-5" for="remember">
                                    Lembrar-me
                                </label>
                            </div>
                            <div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small text-right">Perdeu a
                                        Palavra-passe?</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-gray-800" id="boltaologin">
                            {{ __('Entrar') }}
                        </button>
                    </div>
                </form>
            </div>
            <div
                class="gradient-backgroundff d-flex justify-content-center align-items-center flex-column d-none d-md-flex">
          
                <h2 class="text-white">SEJA BEM-VINDO</h2>
                <p class="text-white">Insira as suas credenciais<br>para desfrutar o melhor do sistema</p>
            </div>
        </section>

    </main> -->

@endsection