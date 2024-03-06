<?php

namespace App\Services\AuthenticationService\V1\Middlewares;

use App\Services\AuthenticationService\V1\Models\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeshAuthentication
{
    /**
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader(config('authenticate.user_id'))) {
            throw new AuthenticationException(
                'Unauthenticated.'
            );
        }

        $userId = $request->header(config('authenticate.user_id'));
        /** @var User $user */
        $user = User::query()->find($userId);

        if (!$userId || !$user) {
            throw new AuthenticationException(
                'Unauthenticated.'
            );
        }

        Auth::login($user);

        return $next($request);
    }
}
