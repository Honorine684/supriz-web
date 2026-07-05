<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Autorise l'accès uniquement aux utilisateurs marqués is_admin.
 * Empêche un compte de l'app mobile d'accéder au back-office.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->is_admin) {
            abort(403, "Accès réservé aux administrateurs.");
        }

        return $next($request);
    }
}
