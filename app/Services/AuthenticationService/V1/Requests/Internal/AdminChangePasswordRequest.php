<?php

namespace App\Services\AuthenticationService\V1\Requests\Internal;

use Illuminate\Foundation\Http\FormRequest;

class AdminChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'current' => ['required', 'current_password:api'],
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }
}
