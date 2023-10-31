<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Illuminate\Http\Request;

class ComplaintItemController
{

    public function store($id, Request $request)
    {
        config('flux-items.models.complain_item')::create([
            'complaint_reason_id' => $request->get('complaint_reason_id'),
            'ad_id' => $id,
            'user_id' => auth()->guard('sanctum')->user()?->id,
            'comment' => $request->has('comment') ? $request->get('comment') : null,
        ]);

        return response()->noContent();
    }

}
