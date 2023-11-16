<?php

namespace Nurdaulet\FluxItems\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CartItemSaveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_address_id' => 'nullable',
            'receive_method_id' => 'nullable',
        ];
    }
}
