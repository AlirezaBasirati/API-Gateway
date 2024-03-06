<?php

namespace App\Services\AuthorizationService\V1\Controllers\Admin;

use App\Services\AuthenticationService\V1\Models\User;
use App\Services\AuthenticationService\V1\Resources\Internal\UserResource;
use App\Services\AuthorizationService\V1\Repository\V1\Admin\User\UserRepositoryInterface;
use App\Services\AuthorizationService\V1\Requests\User\StoreBatchRequest;
use App\Services\AuthorizationService\V1\Requests\User\StoreRequest;
use App\Services\AuthorizationService\V1\Requests\User\UpdateRequest;
use Celysium\Permission\Controllers\UserController as BaseUserController;
use Celysium\Responser\Responser;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseUserController
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $users = $this->userRepository->index($request->query());

        return Responser::collection(UserResource::collection($users));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->userRepository->store($request->validated());

        return Responser::success(new UserResource($user));
    }

    public function storeBatch(StoreBatchRequest $request): JsonResponse
    {
        $users = $this->userRepository->storeBatch($request->validated());

        return Responser::success(UserResource::collection($users));
    }

    public function update(UpdateRequest $request, User $user): JsonResponse
    {
        $user = $this->userRepository->update($user, $request->validated());

        return Responser::success(new UserResource($user));
    }

    public function show(User $user): JsonResponse
    {
        $user = $this->userRepository->show($user);

        return Responser::info(new UserResource($user));
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userRepository->destroy($user);

        return Responser::deleted();
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function syncPermissions(Request $request, User $user): JsonResponse
    {
        $user = parent::syncPermissionsById($request, $user);

        return Responser::success(new UserResource($user));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function syncRoles(Request $request, User $user): JsonResponse
    {
        $user = parent::syncRolesById($request, $user);

        return Responser::success(new UserResource($user));
    }
}
