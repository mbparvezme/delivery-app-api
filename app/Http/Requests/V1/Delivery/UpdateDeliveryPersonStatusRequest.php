<?php

namespace App\Http\Requests\V1\Delivery;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryPersonStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'is_available' => 'sometimes|boolean',
            'current_latitude' => 'required_with:current_longitude|numeric|between:-90,90',
            'current_longitude' => 'required_with:current_latitude|numeric|between:-180,180',
        ];
    }
}
