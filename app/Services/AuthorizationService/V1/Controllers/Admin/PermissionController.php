<?php

namespace App\Services\AuthorizationService\V1\Controllers\Admin;


use App\Services\AuthorizationService\V1\Resources\Permission\PermissionResource;
use Celysium\Permission\Controllers\PermissionController as BasePermissionController;
use Celysium\Permission\Models\Permission;
use Celysium\Responser\Responser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PermissionController extends BasePermissionController
{
    /**
     * @param Request $request
     * @param callable|null $authorize
     * @return JsonResponse
     */
    public function index(Request $request, callable $authorize = null): JsonResponse
    {
        $roles = parent::index($request);

        return Responser::collection(PermissionResource::collection($roles));
    }

    /**
     * @param Request $request
     * @param callable|null $authorize
     * @return JsonResponse
     */
    public function store(Request $request, callable $authorize = null): JsonResponse
    {
        $role = parent::store($request);

        return Responser::created(new PermissionResource($role));
    }

    /**
     * @param Permission $permission
     * @param callable|null $authorize
     * @return Model|JsonResponse
     */
    public function show(Permission $permission, callable $authorize = null): Model|JsonResponse
    {
        $role = parent::show($permission);

        return Responser::info(new PermissionResource($role));
    }

    /**
     * @param Request $request
     * @param Permission $permission
     * @param callable|null $authorize
     * @return JsonResponse
     */
    public function update(Request $request, Permission $permission, callable $authorize = null): JsonResponse
    {
        $permission = parent::update($request, $permission);

        return Responser::success(new PermissionResource($permission));
    }

    /**
     * @param Permission $permission
     * @param callable|null $authorize
     * @return JsonResponse
     * @throws ValidationException
     */
    public function destroy(Permission $permission, callable $authorize = null): JsonResponse
    {
        parent::destroy($permission);

        return Responser::deleted();
    }
}
