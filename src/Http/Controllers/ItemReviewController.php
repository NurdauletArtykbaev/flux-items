<?php

namespace Nurdaulet\FluxItems\Http\Controllers;

use Nurdaulet\FluxItems\Http\Requests\StoreItemReviewRequest;
use Nurdaulet\FluxItems\Http\Resources\Product\ProductReviewResource;
use Nurdaulet\FluxItems\Repositories\ItemRepository;
use Nurdaulet\FluxBase\Facades\FluxBaseReviewFacade;
use Illuminate\Http\Request;

class ItemReviewController
{
    public function __construct(
        private ItemRepository $itemRepository
    )
    {
    }

    public function index(Request $request, $id)
    {
        $item = $this->itemRepository->find($id);
        $reviews = FluxBaseReviewFacade::list( $item);
        $reviews->load(['user', 'rating']);
        return ProductReviewResource::collection($reviews);
    }

    public function store(StoreItemReviewRequest $request, $id)
    {
        $user = $request->user();
        $item = $this->itemRepository->find($id);
        FluxBaseReviewFacade::create($user, $item, $request->validated());
        return response()->noContent();
    }
}
