<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class UserUpdateRequest  extends FormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|integer',
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email',
            'surname' => 'string|max:255',
            'role' => [
                'string',
                Rule::in(['admin', 'regional_admin', 'owner', 'customer']),
            ],
            'password' => 'string|max:255',
            'country' => 'string|max:255',
            'city' => 'string|max:255',
            'address' => 'string|max:255',
            'phone_number' => 'string|max:255'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['message' => 'bad request'], 400));
    }
}
