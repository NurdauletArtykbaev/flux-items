<?php

namespace Nurdaulet\FluxItems\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'condition_id' => 'required|int',
            'city_id' => 'nullable',
            'is_required_deposit' => 'nullable',
            'today_delivery_price' => 'nullable',
            'images' => 'nullable|array',
            'temp_images' => 'required|array',
            'categories' => 'required|array',
            'rental_day_types' => 'nullable|array',
            'rental_day_types.*.name' => 'required',
            'rental_day_types.*.price' => 'required',
            'methods_receiving' => 'nullable|array',
            'city_ids' => 'nullable|array',
            'methods_receiving.*.id' => 'required',
            'methods_return' => 'required|array',
            'protect_methods' => 'nullable|array',
            'lat' => 'nullable',
            'lng' => 'nullable',
            'address' => 'nullable|string',
        ];
    }
}
