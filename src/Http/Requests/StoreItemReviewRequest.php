<?php

namespace Nurdaulet\FluxItems\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemReviewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
            'rating'            => 'required|integer|gte:1|lte:5',
            'rating_messages'   => 'nullable|array',
            'rating_messages.*' => 'required|int|exists:rating_messages,id',
            'comment'           => 'nullable|string'
        ];
    }
}
