<?php

namespace Nurdaulet\FluxItems\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class SimilarProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'city_id' => 'nullable',
            'lord_items' => 'nullable'
        ];
    }
}
