<?php

namespace App\Services\AuthorizationService\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuthorizationService\V1\Repository\V1\Admin\Authorization\AuthorizationRepositoryInterface as AuthorizationService;
use Celysium\Responser\Responser;
use Illuminate\Http\JsonResponse;

class AuthorizationController extends Controller
{
    private AuthorizationService $authorizationService;

    public function __construct(AuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    public function permissions(): JsonResponse
    {
        $permissions = $this->authorizationService->permissions();

        return Responser::collection($permissions);
    }

}
