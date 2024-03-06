<?php

namespace App\Services\AuthenticationService\V1\Requests\Internal;

use Illuminate\Foundation\Http\FormRequest;

class ResetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'exists:users,username'],
            'password' => ['required', 'min:6'],
        ];
    }
}
