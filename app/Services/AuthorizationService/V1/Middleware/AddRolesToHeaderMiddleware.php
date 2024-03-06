<?php

namespace App\Services\AuthorizationService\V1\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddRolesToHeaderMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $request->headers->set('roles', auth()->user()->roles()->pluck('name')->toArray());
        }

        return $next($request);
    }
}
