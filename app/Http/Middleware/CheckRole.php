<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('loginform');
        }

        $user = Auth::user();
        
        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403, 'Accès refusé. Rôle administrateur requis.');
        }
        
        if ($role === 'client' && !$user->isClient()) {
            abort(403, 'Accès refusé. Rôle client requis.');
        }

        return $next($request);
    }
}