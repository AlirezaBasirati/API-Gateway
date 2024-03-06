<?php

namespace App\Services\AuthenticationService\V1\Resources\Internal;

use App\Services\AuthorizationService\V1\Resources\Role\RoleResource;
use Celysium\Permission\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;


/**
 * @property integer $id
 * @property string $username
 * @property integer $type
 * @property integer $status
 * @property Collection<Role> $roles
 */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id"       => $this->id,
            "username" => $this->username,
            "type"     => $this->type,
            "status"   => $this->status,
            'roles'    => RoleResource::collection($this->roles),
        ];
    }
}
