<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est connecté et s'il est admin
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request); // Continuer vers la page demandée
        }

        // Sinon, rediriger vers l'accueil
        return redirect('/');
    }
}
