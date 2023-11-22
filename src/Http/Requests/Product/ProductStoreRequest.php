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
            'is_required_deposit' => 'nullable',
            'is_required_confirm' => 'nullable',
            'today_delivery_price' => 'nullable',
            'images' => 'nullable|array',
            'temp_images' => 'required|array',
            'catalogs' => 'required|array',
            'methods_receiving' => 'nullable|array',
            'city_ids' => 'nullable|array',
            'price' => 'nullable|array',
            'rent_prices' => 'nullable|array',
            'rent_prices*.id' => 'required',
            'rent_prices*.price' => 'required',
            'methods_receiving.*.id' => 'required',
            'methods_return' => 'required|array',
            'protect_methods' => 'nullable|array',
            'lat' => 'nullable',
            'lng' => 'nullable',
            'address' => 'nullable|string',
            'user_address_id' => 'nullable',
        ];
    }
}
