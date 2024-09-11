@extends('layouts.app')

@section('content')

<main>
    <!-- Section -->

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
                    <h2>Login</h2>
                    <h6>Tenha o acesso na sua conta e tenha o controle das suas responsabilidades </h6>
                  
                    </div>  <!--  <div class="text-center text-md-center mb-4 mt-md-0" style="contain: inline-size;">
                    <img src="../assets/img/LogoM/gphoto.png" alt="IMG" style="width: 50%;">
                </div> -->
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

                <div class="input-box button">
                    <button type="submit" class="" id="">
                        {{ __('Entrar') }}
                    </button>
                </div>
                </form>
            </div>
        </div>
        </div>
    </section>

</main>
@endsection