<?php

namespace App\Http\Requests\V1\Zone;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDeliveryZoneRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(['radius', 'polygon'])],
            'active' => 'sometimes|boolean',
        ];

        if ($this->input('type') === 'polygon') {
            return array_merge($rules, [
                'value' => 'required|array|min:3',
                'value.*.lat' => 'required|numeric|between:-90,90',
                'value.*.lng' => 'required|numeric|between:-180,180',
            ]);
        }

        return array_merge($rules, [
            'value' => 'required|numeric|min:0',
        ]);
    }
}
