<?php

namespace App\Services\AuthenticationService\V1\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Services\AuthenticationService\V1\Repository\Internal\Authentication\AuthenticationRepositoryInterface;
use App\Services\AuthenticationService\V1\Requests\Internal\AdminChangePasswordRequest;
use App\Services\AuthenticationService\V1\Requests\Internal\AdminResetRequest;
use App\Services\AuthenticationService\V1\Requests\Internal\AppChangePasswordRequest;
use App\Services\AuthenticationService\V1\Requests\Internal\CheckRequest;
use App\Services\AuthenticationService\V1\Requests\Internal\FetchRequest;
use App\Services\AuthenticationService\V1\Requests\Internal\LoginRequest;
use App\Services\AuthenticationService\V1\Requests\Internal\RefreshRequest;
use App\Services\AuthenticationService\V1\Requests\Internal\ResetRequest;
use App\Services\AuthenticationService\V1\Requests\Internal\StoreRequest;
use App\Services\AuthenticationService\V1\Resources\Internal\UserResource;
use Celysium\Responser\Responser;
use Illuminate\Http\JsonResponse;

class AuthenticationController extends Controller
{
    public function __construct(private readonly AuthenticationRepositoryInterface $authenticationService)
    {
    }

    public function adminLogin(LoginRequest $request): JsonResponse
    {
        $result = $this->authenticationService->adminLogin($request->validated());

        return Responser::success(
            [
                'user'  => new UserResource($result['user']),
                'auth' => $result['auth']
            ]
        );
    }

    public function appLogin(LoginRequest $request): JsonResponse
    {
        $result = $this->authenticationService->appLogin($request->validated());

        return Responser::success(
            [
                'user'  => new UserResource($result['user']),
                'auth' => $result['auth']
            ]
        );
    }

    public function pickerLogin(LoginRequest $request): JsonResponse
    {
        $result = $this->authenticationService->pickerLogin($request->validated());

        return Responser::success(
            [
                'user'  => new UserResource($result['user']),
                'auth' => $result['auth']
            ]
        );
    }

    public function managerLogin(LoginRequest $request): JsonResponse
    {
        $result = $this->authenticationService->managerLogin($request->validated());

        return Responser::success(
            [
                'user'  => new UserResource($result['user']),
                'auth' => $result['auth']
            ]
        );
    }

    public function check(CheckRequest $request): JsonResponse
    {
        $user = $this->authenticationService->check($request->validated());

        return Responser::info($user ? new UserResource($user) : null);
    }

    public function fetch(FetchRequest $request): JsonResponse
    {
        $user = $this->authenticationService->fetch($request->validated());

        return Responser::info(new UserResource($user));
    }

    public function appStore(StoreRequest $request): JsonResponse
    {
        $result = $this->authenticationService->appStore($request->validated());

        return Responser::success([
            'user'  => new UserResource($result['user']),
            'auth' => $result['auth']
        ]);
    }

    public function logout(): JsonResponse
    {
        $this->authenticationService->logout();

        return Responser::success();
    }

    public function me(): JsonResponse
    {
        $user = $this->authenticationService->me();

        return Responser::info(new UserResource($user));
    }

    public function appChangePassword(AppChangePasswordRequest $request): JsonResponse
    {

        $this->authenticationService->changePassword($request->validated());

        return Responser::success([], [
            [
                'type' => 'success',
                'text' => __('authentication::authentication.change-password'),
            ]
        ]);
    }

    public function adminChangePassword(AdminChangePasswordRequest $request): JsonResponse
    {

        $this->authenticationService->changePassword($request->validated());

        return Responser::success([], [
            [
                'type' => 'success',
                'text' => __('authentication::authentication.change-password'),
            ]
        ]);
    }

    public function appReset(ResetRequest $request): JsonResponse
    {
        $result = $this->authenticationService->appReset($request->validated());

        return Responser::success([
            'user'  => new UserResource($result['user']),
            'auth' => $result['auth']
        ], [
            [
                'type' => 'success',
                'text' => __('authentication::authentication.reset'),
            ]
        ]);
    }

    public function adminReset(AdminResetRequest $request): JsonResponse
    {
        $result = $this->authenticationService->adminReset($request->validated());

        return Responser::success([
            'user'  => new UserResource($result['user']),
            'auth' => $result['auth']
        ], [
            [
                'type' => 'success',
                'text' => __('authentication::authentication.reset'),
            ]
        ]);
    }

    public function refresh(RefreshRequest $request): JsonResponse
    {
        $result = $this->authenticationService->refresh($request->validated());

        return Responser::success($result);
    }
}
