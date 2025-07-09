<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateDeliveryZoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // The user must be authorized to update a zone for this specific restaurant.
        $restaurant = $this->route('restaurant');
        return Gate::allows('update-zone', $restaurant);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            // Type and coordinates are often updated together, but not always required.
            'type' => 'sometimes|in:radius,polygon',
            'coordinates' => 'sometimes|array',
            'coordinates.radius_km' => 'required_if:type,radius|numeric|min:0.1|max:100',
            'coordinates.points' => 'required_if:type,polygon|array|min:3',
            'coordinates.points.*.lat' => 'required_if:type,polygon|numeric|between:-90,90',
            'coordinates.points.*.lng' => 'required_if:type,polygon|numeric|between:-180,180',
            'is_active' => 'sometimes|boolean',
        ];
    }
}