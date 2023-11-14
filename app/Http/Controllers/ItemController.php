<?php

namespace App\Http\Controllers;

use App\Http\Response\ApiResponse;
use App\Repositories\ItemRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    protected $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function index(): JsonResponse
    {
        $items = $this->itemRepository->all();
        return ApiResponse::format(200, "Items retrieved Successfully",$items);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->only(['name', 'description']);
        $item = $this->itemRepository->create($data);
        return ApiResponse::format(201, "Item Created", $item);
    }

    public function show($id): JsonResponse
    {
        $item = $this->itemRepository->find($id);
        if (!$item) {
            return ApiResponse::format(404, "Item not found", $item);
        }
        return ApiResponse::format(200, "Item Retrieved", $item);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->only(['name', 'description']);
        $item = $this->itemRepository->update($data, $id);

        if (!$item) {
            return ApiResponse::format(404, "Item not found", $item);
        }

        return ApiResponse::format(200, "Item Updated", $item);
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->itemRepository->delete($id);

        if (!$deleted) {
            return ApiResponse::format(404, "Item not found");
        }

        return ApiResponse::format(200, "Item Deleted");
    }
}
