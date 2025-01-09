<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('CheckAdminRole middleware called', [
            'user' => auth()->user(),
            'is_admin' => auth()->user() ? auth()->user()->isAdmin() : false,
            'role' => auth()->user() ? auth()->user()->role : null
        ]);

        if (!auth()->check()) {
            Log::warning('User not authenticated');
            return redirect()->route('login');
        }

        if (!auth()->user()->isAdmin()) {
            Log::warning('User not authorized', [
                'user' => auth()->user(),
                'role' => auth()->user()->role
            ]);
            return redirect()->route('home')->with('error', 'No tienes permisos de administrador.');
        }

        Log::info('User authorized as admin', [
            'user' => auth()->user(),
            'role' => auth()->user()->role
        ]);

        return $next($request);
    }
}