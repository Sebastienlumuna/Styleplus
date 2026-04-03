<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\UserRole;

class DashboardController extends Controller
{
    /**
     * Redirection vers le bon dashboard selon le rôle
     */
    public function index()
    {
        $user = Auth::user();
        
        // Vérifier le rôle et rediriger
        if ($user->isAdmin()) {
            return redirect()->route('dashboard.admin');
        } elseif ($user->role === UserRole::LIVREUR->value) {
            return redirect()->route('dashboard.livreur');
        } elseif ($user->isClient()) {
            return redirect()->route('dashboard.client');
        }
        
        // Si aucun rôle valide, rediriger vers l'accueil
        return redirect()->route('home')->with('error', 'Rôle utilisateur non reconnu');
    }
}
