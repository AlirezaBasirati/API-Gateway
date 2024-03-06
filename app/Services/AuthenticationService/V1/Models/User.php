<?php

namespace App\Services\AuthenticationService\V1\Models;

use Celysium\Permission\Models\Permission;
use Celysium\Permission\Models\Role;
use Celysium\Permission\Traits\Permissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Passport\HasApiTokens;

/**
 * @property Collection<Permission> $permissions
 * @property Collection<Role> $roles
 * @property integer $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property integer $type
 * @property integer $status
 * @property string $password
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Permissions;

    const TYPE_USER = 0;
    const TYPE_INTEGRATION = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'username',
        'type',
        'status',
        'password',
        'deleted_at',
    ];

    protected $hidden = [
        'password'
    ];

    /**
     * Find the user instance for the given username.
     */
    public function findForPassport(string $username): Model
    {
        return $this->query()->where('username', $username)->first();
    }
}
