<?php

namespace App\Services\AuthenticationService\V1\Middlewares;

use App\Services\AuthenticationService\V1\Models\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use Lcobucci\JWT\Parser as JwtParser;

class Authentication
{
    /**
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $user = null;
        if ($request->server('SERVER_ADDR') == '127.0.0.1') {
            if (!$token) {
                return $next($request);
            }
        }
        elseif ($token) {
            /** @var ?string $accessToken */
            $accessToken = null;
            try {
                $accessToken = app(JwtParser::class)->parse($token)->claims()->get('jti');
            }
            catch (\Exception) {
            }

            if (!$accessToken) {
                throw new AuthenticationException(
                    'Unauthenticated.'
                );
            }

            /** @var User $user */
            $user = Token::query()
                ->where('id', $accessToken)
                ->first()?->getAttribute('user');
        }
        elseif ($id = $request->header(config('authenticate.user_id'))) {
            /** @var User $user */
            $user = User::query()->find($id);
        }

        if ($user) {
            Passport::actingAs($user);
        }

        return $next($request);
    }
}
