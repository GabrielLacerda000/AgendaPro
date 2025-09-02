<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsProfessional
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->professional) {
            // redireciona com erro ou aborta
            return redirect()->route('settings.professional')
                             ->with('error', 'Você precisa completar seu perfil profissional primeiro.');
        }

        return $next($request);
    }
}

