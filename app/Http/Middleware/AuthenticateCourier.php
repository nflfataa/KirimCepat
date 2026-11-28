<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateCourier
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('web')->check()) {
            return response()->json([
                'message' => 'Masuk sebagai kurir untuk memperbarui status paket.',
            ], 401);
        }

        return $next($request);
    }
}
