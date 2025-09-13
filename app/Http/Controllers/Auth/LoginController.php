<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');

    }

    public function index(): View
    {
        return view('auth.login');
    }

    public function login(Request $request){

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);


        if (Auth::attempt($credentials, (bool) $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'email' => 'Identifiant incorrecte !',
        ])->onlyInput('email');
     }
     
     public function logout(Request $request): RedirectResponse
     {
         Auth::logout();
     
         $request->session()->flush();
     
         $request->session()->regenerateToken();
     
         return redirect('/');
     }
}
