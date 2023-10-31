<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Nurdaulet\FluxItems\Http\Requests\StoreItemReviewRequest;
use Nurdaulet\FluxItems\Repositories\ItemRepository;
use Nurdaulet\FluxBase\Facades\FluxBaseReviewFacade;

class ItemReviewController
{
    public function __construct(
        private ItemRepository $itemRepository
    )
    {
    }

    public function store(StoreItemReviewRequest $request, $id)
    {
        $user = $request->user();
        $item = $this->itemRepository->find($id);
        FluxBaseReviewFacade::create($user, $item, $request->validated());
        return response()->noContent();
    }
}
