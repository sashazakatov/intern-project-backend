<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class DeviceAddRequest extends FormRequest
{
    public function rules()
    {
        return [
            'serial_number' => 'string|max:255',
            'owner_id' => 'required|exists:users,id',
            'administrator_id' => 'required|exists:users,id',
            'name' => 'string|max:255',
            'device_type' => 'string|max:255',
            'phase_active' => 'boolean',
            'phase_type' => 'string|max:255',
            'sum_power' => 'numeric',
            'group_id' => 'nullable',
            'location' => 'nullable|json',
            'country' => 'string|max:255',
            'city' => 'string|max:255',
            'address' => 'string|max:255',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationException($validator, response()->json(['message' => 'bad request'], 400));
    }
}
