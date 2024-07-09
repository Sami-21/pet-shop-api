<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /* @OA\Tag(
        *     name="Products",
        *     description="Product API endpoints"
        * )
    */
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     summary="View products",
     *      tags={"Products"},
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
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         @OA\Schema(type="float"),
     *     ),
     *     @OA\Parameter(
     *         name="brand",
     *         in="query",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(response="200", description="Ok",
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="int", example=1),
     *             @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                     type="object",
     *                      @OA\Property(property="id", type="int", example=1),
     *                      @OA\Property(property="uuid", type="string", example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                      @OA\Property(property="category_uuid", type="string",example="d166d772-7c61-4e53-95f1-gq31fds654fdg"),
     *                      @OA\Property(property="title", type="string",example="Product title"),
     *                      @OA\Property(property="price", type="float",example="1350.00"),
     *                      @OA\Property(property="description", type="string",example="Product description"),
     *                      @OA\Property(property="metadata", type="object",
     *                          @OA\Property(property="file", type="string",example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                          @OA\Property(property="brand", type="string",example="d166d772-7c61-4e53-95f1-8e0a27748e3a")
     *                       ),
     *                      @OA\Property(property="deleted_at", type="string|null",example="null"),
     *                      @OA\Property(property="created_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *                      @OA\Property(property="updated_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *                     )),
     *             @OA\Property(property="per_page", type="int",example=10),
     *             @OA\Property(property="total", type="int",example=10),
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
        $category = $request->query('category', '');
        $price = (int) $request->query('price', '0');
        $sortBy = $request->query('sortBy', 'created_at');
        $descFilter = filter_var($request->input('desc'), FILTER_VALIDATE_BOOLEAN);
        $title =  $request->query('title', '');
        $brandTitle =  $request->query('brand', '');
        $response = $this->productService->getProducts($limit, $sortBy, $descFilter, $category, $price, $title, $brandTitle);

        return response()->json($response);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/product/create",
     *     summary="store product",
     *     tags={"Products"},
     *     security={{"bearerAuth":{}}},
     * 
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"title" , "category_uuid" , "price" , "description" , "image" , "brand"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Product's title",
     *                     example="Product title example"
     *                 ),
     *                 @OA\Property(
     *                     property="category_uuid",
     *                     type="string",
     *                     description="Product category",
     *                     example="d166d772-7c61-4e53-95f1-8e0a27748e3a"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="float",
     *                     description="Product price",
     *                     example="12350.00"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Product description",
     *                     example="Product description example"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     description="Product image uuid",
     *                     example="d166d772-7c61-dfs0-95f1-8e0a27748e3a"
     *                 ),
     *                 @OA\Property(
     *                     property="brand",
     *                     type="string",
     *                     description="Product brand uuid",
     *                     example="d166d772-7c61-4e53-dsf9-8e0a27748e3a"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response="200", description="Ok",
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="product stored with success"),
     *             @OA\Property(property="product", type="object",
     *                      @OA\Property(property="id", type="int", example=1),
     *                      @OA\Property(property="uuid", type="string", example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                      @OA\Property(property="category_uuid", type="string",example="d166d772-7c61-4e53-95f1-gq31fds654fdg"),
     *                      @OA\Property(property="title", type="string",example="Product title"),
     *                      @OA\Property(property="price", type="float",example="1350.00"),
     *                      @OA\Property(property="description", type="string",example="Product description"),
     *                      @OA\Property(property="metadata", type="object",
     *                          @OA\Property(property="file", type="string",example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                          @OA\Property(property="brand", type="string",example="d166d772-7c61-4e53-95f1-8e0a27748e3a")
     *                       ),
     *                      @OA\Property(property="created_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *                      @OA\Property(property="updated_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *            ),
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
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->storeProduct($request->validated());
        return response()->json(['message' => 'product stored with success', 'product' => $product], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product/{uuid}",
     *     summary="View product",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="uuid",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(response="200", description="Ok",
     *     @OA\JsonContent(
     *             type="object",
     *                      @OA\Property(property="id", type="int", example=1),
     *                      @OA\Property(property="uuid", type="string", example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                      @OA\Property(property="category_uuid", type="string",example="d166d772-7c61-4e53-95f1-gq31fds654fdg"),
     *                      @OA\Property(property="title", type="string",example="Product title"),
     *                      @OA\Property(property="price", type="float",example="1350.00"),
     *                      @OA\Property(property="description", type="string",example="Product description"),
     *                      @OA\Property(property="metadata", type="object",
     *                          @OA\Property(property="file", type="string",example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                          @OA\Property(property="brand", type="string",example="d166d772-7c61-4e53-95f1-8e0a27748e3a")
     *                       ),
     *                      @OA\Property(property="created_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *                      @OA\Property(property="updated_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *         )),
        
     *     @OA\Response(response="404", description="Not found"),
     * )
     */
    public function show(string $uuid): JsonResponse
    {
        return response()->json($this->productService->getProduct($uuid));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/product/{uuid}",
     *     summary="update product",
     *     tags={"Products"},
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
     *                 required={"title" , "category_uuid" , "price" , "description" , "image" , "brand"},
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Product's title",
     *                     example="Product title example"
     *                 ),
     *                 @OA\Property(
     *                     property="category_uuid",
     *                     type="string",
     *                     description="Product category",
     *                     example="d166d772-7c61-4e53-95f1-8e0a27748e3a"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="float",
     *                     description="Product price",
     *                     example="12350.00"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Product description",
     *                     example="Product description example"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     description="Product image uuid",
     *                     example="d166d772-7c61-dfs0-95f1-8e0a27748e3a"
     *                 ),
     *                 @OA\Property(
     *                     property="brand",
     *                     type="string",
     *                     description="Product brand uuid",
     *                     example="d166d772-7c61-4e53-dsf9-8e0a27748e3a"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response="200", description="Ok",
     *     @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="product updated with success"),
     *             @OA\Property(property="product", type="object",
     *                      @OA\Property(property="id", type="int", example=1),
     *                      @OA\Property(property="uuid", type="string", example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                      @OA\Property(property="category_uuid", type="string",example="d166d772-7c61-4e53-95f1-gq31fds654fdg"),
     *                      @OA\Property(property="title", type="string",example="Product title"),
     *                      @OA\Property(property="price", type="float",example="1350.00"),
     *                      @OA\Property(property="description", type="string",example="Product description"),
     *                      @OA\Property(property="metadata", type="object",
     *                          @OA\Property(property="file", type="string",example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                          @OA\Property(property="brand", type="string",example="d166d772-7c61-4e53-95f1-8e0a27748e3a")
     *                       ),
     *                      @OA\Property(property="created_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *                      @OA\Property(property="updated_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *            ),
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
    public function update(UpdateProductRequest $request, string $uuid): JsonResponse
    {
        $product = $this->productService->updateProduct($uuid, $request->validated());
        return response()->json(['message' => 'product updated with success', 'product' => $product]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/product/{uuid}",
     *     summary="delete product",
     *     tags={"Products"},
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
     *             @OA\Property(property="message", type="string", example="product deleted with success"),
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
    public function destroy(string $uuid): JsonResponse
    {
        $this->productService->deleteProduct($uuid);

        return response()->json(['message' => 'Product deleted with success']);
    }
}
