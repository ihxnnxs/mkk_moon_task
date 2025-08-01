<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class GeoSearchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:0',
            'min_lat' => 'nullable|numeric',
            'max_lat' => 'nullable|numeric',
            'min_lng' => 'nullable|numeric',
            'max_lng' => 'nullable|numeric',
        ];
    }
}
