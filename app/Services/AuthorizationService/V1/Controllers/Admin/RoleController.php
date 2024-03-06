<?php

namespace App\Services\AuthorizationService\V1\Controllers\Admin;

use App\Services\AuthorizationService\V1\Resources\Role\RoleResource;
use Celysium\Permission\Controllers\RoleController as BaseRoleController;
use Celysium\Permission\Models\Role;
use Celysium\Responser\Responser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoleController extends BaseRoleController
{
    /**
     * @param Request $request
     * @param callable|null $authorize
     * @return JsonResponse
     */
    public function index(Request $request, callable $authorize = null): JsonResponse
    {
        $roles = parent::index( $request);

        return Responser::collection(RoleResource::collection($roles));
    }

    /**
     * @param Request $request
     * @param callable|null $authorize
     * @return JsonResponse
     */
    public function store(Request $request, callable $authorize = null): JsonResponse
    {
        $role = parent::store($request);

        return Responser::created(new RoleResource($role));
    }

    /**
     * @param Role $role
     * @param callable|null $authorize
     * @return Model|JsonResponse
     */
    public function show(Role $role, callable $authorize = null): Model|JsonResponse
    {
        $role = parent::show($role);

        return Responser::info(new RoleResource($role));
    }

    /**
     * @param Request $request
     * @param Role $role
     * @param callable|null $authorize
     * @return JsonResponse
     */
    public function update(Request $request, Role $role, callable $authorize = null): JsonResponse
    {
        $role = parent::update($request, $role);

        return Responser::success(new RoleResource($role));
    }

    /**
     * @param Role $role
     * @param callable|null $authorize
     * @return JsonResponse
     * @throws ValidationException
     */
    public function destroy(Role $role, callable $authorize = null): JsonResponse
    {
        parent::destroy($role);

        return Responser::deleted();
    }

    /**
     * @param Role $role
     * @param Request $request
     * @param callable|null $authorize
     * @return JsonResponse
     */
    public function syncPermissions(Role $role, Request $request, callable $authorize = null): JsonResponse
    {
        $role = parent::syncPermissions($role, $request);

        return Responser::info(new RoleResource($role));
    }
}
