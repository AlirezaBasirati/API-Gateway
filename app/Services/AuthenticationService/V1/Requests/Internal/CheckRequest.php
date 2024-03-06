<?php

namespace App\Services\AuthenticationService\V1\Requests\Internal;

use Illuminate\Foundation\Http\FormRequest;

class CheckRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required',
        ];
    }
}
