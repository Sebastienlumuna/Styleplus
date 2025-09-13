<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Providers\RouteServiceProvider;

class RegisterController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        return view('auth.register');
    }
    public function register(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'between:5,255'],
        'email' => ['required', 'email', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    // Hash du mot de passe
    $validated['password'] = Hash::make($validated['password']);

    // Ajout du rôle client
    $validated['role'] = 'client'; // 🔥 forcé côté serveur

    // Création utilisateur
    $user = User::create($validated);

    // Connexion directe après inscription
    Auth::login($user);

    // Redirection
    return redirect()->intended(RouteServiceProvider::HOME)
                     ->with('success', 'Votre compte a été créé avec succès 🎉');
}
}
