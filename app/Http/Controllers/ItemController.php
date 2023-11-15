<?php

namespace App\Http\Controllers;

use App\Http\Response\ApiResponse;
use App\Repositories\ItemRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    protected $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * @OA\Get(
     *   path="/items",
     *   summary="Get all items",
     *   description="Retrieves all items from the database",
     *   tags={"Items"},
     *   @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(
     *       @OA\Property(property="items", type="array", @OA\Items(type="object", @OA\Property(property="id", type="integer"), @OA\Property(property="name", type="string"), @OA\Property(property="description", type="string"))),
     *     )
     *   )
     * )
     */
    public function index(): JsonResponse
    {
        $items = $this->itemRepository->all();
        return ApiResponse::format(200, "Items retrieved Successfully",$items);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::format(400, "invalid details",['errors' => $validator->errors()] );
        }
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
