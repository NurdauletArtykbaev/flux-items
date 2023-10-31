<?php

namespace Nurdaulet\FluxItems\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'condition_id' => 'nullable|int',
            'city_id' => 'nullable',
            'city_ids' => 'nullable|array',
            'is_required_deposit' => 'nullable',
            'today_delivery_price' => 'nullable',
            'images' => 'nullable|array',
            'temp_images' => 'nullable|array',
            'categories' => 'nullable|array',
            'rental_day_types' => 'nullable|array',
            'rent_prices' => 'nullable|array',
            'rental_day_types.*.name' => 'required',
            'rental_day_types.*.price' => 'required',
            'methods_receiving' => 'nullable|array',
            'methods_receiving.*.receive_method_id' => 'required',
            'methods_return' => 'nullable|array',
            'methods_return.*.return_method_id' => 'required',
            'protect_methods' => 'nullable|array',
            'lat' => 'nullable',
            'lng' => 'nullable',
            'address' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ];
    }
}
