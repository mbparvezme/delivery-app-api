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
            'is_active' => 'sometimes|boolean',
        ];

        // If the type is 'polygon', the coordinates field itself is the array of points.
        if ($this->input('type') === 'polygon') {
            return array_merge($rules, [
                'coordinates' => 'required|array|min:3',
                'coordinates.*.lat' => 'required|numeric|between:-90,90',
                'coordinates.*.lng' => 'required|numeric|between:-180,180',
            ]);
        }

        // Otherwise (for 'radius'), the coordinates field is an object with keys.
        return array_merge($rules, [
            'coordinates' => 'required|array',
            'coordinates.center' => 'required|array',
            'coordinates.center.lat' => 'required|numeric|between:-90,90',
            'coordinates.center.lng' => 'required|numeric|between:-180,180',
            'coordinates.radius' => 'required|numeric|min:0',
        ]);
    }
}
