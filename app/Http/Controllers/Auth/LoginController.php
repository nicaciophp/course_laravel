<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/admin/stores';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        //se for dono de loja vai para o admin
        if ($user->role == 'ROLE_OWNER')
            return redirect()->route('admin.stores.index');

        //se for usuario e tiver produtos no carrinho vai para o checkout se não
        //vai para o home
        if ($user->role == "ROLE_USER" && session()->has('cart')){
            return redirect()->route('checkout.index');
        } else{
            return redirect()->route('home');
        }

        return null;
    }
}
