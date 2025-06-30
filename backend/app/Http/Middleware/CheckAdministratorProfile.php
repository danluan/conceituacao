<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdministratorProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Não autenticado'], 401);
        }

        if (!$user->hasProfile('ADMINISTRATOR')) {
            return response()->json(['error' => 'Ação requer usuário ADMINISTRADOR.'], 403);
        }

        return $next($request);
    }
}
