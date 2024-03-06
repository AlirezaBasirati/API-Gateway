<?php

namespace App\Services\AuthenticationService\V1\Requests\Internal;

use Illuminate\Foundation\Http\FormRequest;

class FetchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:users,id'],
        ];
    }
}
