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
    /* @OA\Tag(
        *     name="Brands",
        *     description="Brand API endpoints"
        * )
        */
    protected BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/brands",
     *     summary="View brands",
     *      tags={"Brands"},
     * 
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         name="desc",
     *         in="query",
     *         @OA\Schema(type="boolean"),
     *     ),
     * 
     *     @OA\Response(response="200", description="Ok",
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="int", example=1),
     *             @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                     type="object",
     *                      @OA\Property(property="id", type="int", example=1),
     *                     )),
     *               @OA\Property(property="uuid", type="string",            example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *               @OA\Property(property="title", type="string",example="Brand title"),
     *               @OA\Property(property="slug", type="string",example="Brand slug"),
     *               @OA\Property(property="created_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *               @OA\Property(property="updated_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *         )),
     *
     *     @OA\Response(response="401", description="Unauthenticated",
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )),
     * 
     *     @OA\Response(response="403", description="Unauthorized", 
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Unauthorized"),
     *         )),
     * )
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
     * @OA\Post(
     *     path="/api/v1/brand/create",
     *     summary="store brand",
     *     tags={"Brands"},
     *     security={{"bearerAuth":{}}},
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Brand's title",
     *                     example="Brand title example"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     type="string",
     *                     description="Brand's slug",
     *                     example="Brand slug example"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response="200", description="Ok",
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="brand stored with success"),
     *             @OA\Property(property="brand", type="object",
     *                     @OA\Property(property="id", type="int", example=1),
     *                     @OA\Property(property="uuid", type="string", example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                     @OA\Property(property="title", type="string",example="Brand title"),
     *                     @OA\Property(property="slug", type="string",example="Brand slug"),
     *                     @OA\Property(property="created_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *                     @OA\Property(property="updated_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *              ),
     *         )),
     *
     *     @OA\Response(response="401", description="Unauthenticated",
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )),
     * 
     *     @OA\Response(response="403", description="Unauthorized", 
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Unauthorized"),
     *         )),
     * )
     */
    public function store(StoreBrandRequest $request)
    {
        $brand = $this->brandService->storeBrand($request->validated());
        return response()->json(['message' => 'brand stored with success', 'brand' => $brand], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/brand/{uuid}",
     *     summary="View brand",
     *     tags={"Brands"},
     *     @OA\Parameter(
     *         name="uuid",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(response="200", description="Ok",
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="brand", type="object",
     *                     @OA\Property(property="id", type="int", example=1),
     *                     @OA\Property(property="uuid", type="string", example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                     @OA\Property(property="title", type="string",example="Brand title"),
     *                     @OA\Property(property="slug", type="string",example="Brand slug"),
     *                     @OA\Property(property="created_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *                     @OA\Property(property="updated_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *              ),
     *         )),

     *     @OA\Response(response="404", description="Not found"),
     * )
     */
    public function show(string $uuid)
    {
        return response()->json($this->brandService->getBrand($uuid));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/brand/{uuid}",
     *     summary="update brand",
     *     tags={"Brands"},
     *     security={{"bearerAuth":{}}},
     * 
     *     @OA\Parameter(
     *         name="uuid",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="string"),
     *     ),
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"title"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Brand's title",
     *                     example="Brand title example"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     type="string",
     *                     description="Brand's slug",
     *                     example="Brand slug example"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response="200", description="Ok",
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="brand updated with success"),
     *             @OA\Property(property="brand", type="object",
     *                     @OA\Property(property="id", type="int", example=1),
     *                     @OA\Property(property="uuid", type="string", example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                     @OA\Property(property="title", type="string",example="Brand title"),
     *                     @OA\Property(property="slug", type="string",example="Brand slug"),
     *                     @OA\Property(property="created_at", type="string",example="2024-07-08T03:11:38.000000Z"),
     *                     @OA\Property(property="updated_at", type="string",example="2024-07-08T03:11:38.000000Z"),
     *              ),
     *         )),
     *
     *     @OA\Response(response="401", description="Unauthenticated",
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )),
     * 
     *     @OA\Response(response="403", description="Unauthorized", 
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Unauthorized"),
     *         )),
     *     @OA\Response(response="404", description="Not found"),
     * )
     */
    public function update(UpdateBrandRequest $request, string $uuid)
    {
        $brand = $this->brandService->updateBrand($uuid, $request->validated());
        return response()->json(['message' => 'brand updated with success', 'brand' => $brand]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/brand/{uuid}",
     *     summary="delete brand",
     *     tags={"Brands"},
     *     security={{"bearerAuth":{}}},
     * 
     *     @OA\Parameter(
     *         name="uuid",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="string"),
     *     ),
     * 
     *     @OA\Response(response="200", description="Ok",
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="brand deleted with success"),
     *     )),
     *
     *     @OA\Response(response="401", description="Unauthenticated",
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )),
     * 
     *     @OA\Response(response="403", description="Unauthorized", 
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Unauthorized"),
     *         )),
     *     @OA\Response(response="404", description="Not found"),
     * )
     */
    public function destroy(string $uuid)
    {
        $this->brandService->deleteBrand($uuid);

        return response()->json(['message' => 'Brand deleted with success']);
    }
}