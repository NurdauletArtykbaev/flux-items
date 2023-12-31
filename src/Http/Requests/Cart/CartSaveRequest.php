<?php

namespace Nurdaulet\FluxItems\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartSaveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'nullable',
            'phone' => 'nullable',
            'payment_method_id' => 'nullable'
        ];
    }
}
