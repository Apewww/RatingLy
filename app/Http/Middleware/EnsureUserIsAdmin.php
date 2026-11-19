<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (! $request->user()->isAdmin()) {
            abort(403, 'Akses ditolak: Hanya Admin yang berhak mengakses Panel Moderasi.');
        }

        return $next($request);
    }
}
