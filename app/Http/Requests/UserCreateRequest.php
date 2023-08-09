<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'surname' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255'
        ];
    }
    
    public function messages()
    {
        return [
            'name.required' => 'Name field is required',
            'email.required' => 'Email field is required',
            'surname.required' => 'Surname field is required',
            'role.required' => 'Role field is required',
            'password.required' => 'Password field is required',
            'country.required' => 'Country field is required',
            'city.required' => 'City field is required',
            'address.required' => 'Address field is required',
            'phone_number.required' => 'Phone number field is required',
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => $validator->errors()->first()], 422));
    }
}
