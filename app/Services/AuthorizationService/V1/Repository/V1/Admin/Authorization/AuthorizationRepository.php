<?php

namespace App\Services\AuthorizationService\V1\Repository\V1\Admin\Authorization;

use App\Services\AuthenticationService\V1\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthorizationRepository implements AuthorizationRepositoryInterface
{

    public function permissions(): array
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->cachePermissions();
    }

}
