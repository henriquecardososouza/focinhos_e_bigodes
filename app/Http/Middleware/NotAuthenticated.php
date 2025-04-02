<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $route = "landing", ?string $model = null): Response
    {
        if (Auth::check()) return to_route($route);

        if ($model) {
            if ($model::where("credencial", "LIKE", Auth::id())->count() > 0) return to_route($route);
        }

        return $next($request);
    }
}
