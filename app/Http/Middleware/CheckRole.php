<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {

        if (! $request->user()->hasRole($role)) {
            return response()->json([
                'message' => 'No tienes autorización para ingresar.'
            ], 403);
        }

        return response()->json([
            'message' => 'tienes autorización para ingresar.',
            'request' => $next($request)
        ], 200);
    }
}
