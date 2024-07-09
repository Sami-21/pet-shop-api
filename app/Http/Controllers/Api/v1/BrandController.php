<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Services\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $limit = (int) $request->query('limit', '10');
        $sortBy = $request->query('sortBy', 'created_at');
        $descFilter = filter_var($request->input('desc'), FILTER_VALIDATE_BOOLEAN);
        $response = $this->brandService->getBrands($limit, $sortBy, $descFilter);

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        $brand = $this->brandService->storeBrand($request->validated());
        return response()->json(['message' => 'brand stored with success', 'brand' => $brand], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        return response()->json($this->brandService->getBrand($uuid));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, string $uuid)
    {
        $brand = $this->brandService->updateBrand($uuid, $request->validated());
        return response()->json(['message' => 'brand updated with success', 'brand' => $brand]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $this->brandService->deleteBrand($uuid);

        return response()->json(['message' => 'Brand deleted with success']);
    }
}
