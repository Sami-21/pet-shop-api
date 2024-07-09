<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /* @OA\Tag(
        *     name="Categories",
        *     description="Category API endpoints"
        * )
        */
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="View categories",
     *      tags={"Categories"},
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *
     *         @OA\Schema(type="integer"),
     *     ),
     *
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *
     *         @OA\Schema(type="integer"),
     *     ),
     *
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *
     *         @OA\Schema(type="string"),
     *     ),
     *
     *     @OA\Parameter(
     *         name="desc",
     *         in="query",
     *
     *         @OA\Schema(type="boolean"),
     *     ),
     *
     *     @OA\Response(response="200", description="Ok",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="current_page", type="int", example=1),
     *             @OA\Property(property="data", type="array",
     *
     *                     @OA\Items(
     *                     type="object",
     *
     *                      @OA\Property(property="id", type="int", example=1),
     *                      @OA\Property(property="uuid", type="string", example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                      @OA\Property(property="title", type="string",example="Category title"),
     *                      @OA\Property(property="slug", type="string",example="Category slug"),
     *                      @OA\Property(property="created_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *                      @OA\Property(property="updated_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *                     )),
     *             @OA\Property(property="per_page", type="int",example=10),
     *             @OA\Property(property="total", type="int",example=10),
     *         )),
     *
     *     @OA\Response(response="401", description="Unauthenticated",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )),
     *
     *     @OA\Response(response="403", description="Unauthorized",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Unauthorized"),
     *         )),
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $limit = (int) $request->query('limit', '10');
        $sortBy = $request->query('sortBy', 'created_at');
        $descFilter = filter_var($request->input('desc'), FILTER_VALIDATE_BOOLEAN);
        $response = $this->categoryService->getCategories($limit, $sortBy, $descFilter);

        return response()->json($response);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/category/create",
     *     summary="store category",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 required={"title"},
     *
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Category's title",
     *                     example="Category title example"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     type="string",
     *                     description="Category's slug",
     *                     example="Category slug example"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response="200", description="Ok",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="category stored with success"),
     *             @OA\Property(property="category", type="object",
     *                     @OA\Property(property="id", type="int", example=1),
     *                     @OA\Property(property="uuid", type="string", example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                     @OA\Property(property="title", type="string",example="Category title"),
     *                     @OA\Property(property="slug", type="string",example="Category slug"),
     *                     @OA\Property(property="created_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *                     @OA\Property(property="updated_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *              ),
     *         )),
     *
     *     @OA\Response(response="401", description="Unauthenticated",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )),
     *
     *     @OA\Response(response="403", description="Unauthorized",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Unauthorized"),
     *         )),
     * )
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->categoryService->storeCategory($request->validated());

        return response()->json(['message' => 'category stored with success', 'category' => $category], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/category/{uuid}",
     *     summary="View category",
     *     tags={"Categories"},
     *
     *     @OA\Parameter(
     *         name="uuid",
     *         required=true,
     *         in="path",
     *
     *         @OA\Schema(type="string"),
     *     ),
     *
     *     @OA\Response(response="200", description="Ok",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="category", type="object",
     *                     @OA\Property(property="id", type="int", example=1),
     *                     @OA\Property(property="uuid", type="string", example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                     @OA\Property(property="title", type="string",example="Category title"),
     *                     @OA\Property(property="slug", type="string",example="Category slug"),
     *                     @OA\Property(property="created_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *                     @OA\Property(property="updated_at", type="string",example="2024-07-09T03:11:38.000000Z"),
     *              ),
     *         )),

     *
     *     @OA\Response(response="404", description="Not found"),
     * )
     */
    public function show(string $uuid): JsonResponse
    {
        return response()->json($this->categoryService->getCategory($uuid));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/category/{uuid}",
     *     summary="update category",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="uuid",
     *         required=true,
     *         in="path",
     *
     *         @OA\Schema(type="string"),
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 required={"title"},
     *
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Category's title",
     *                     example="Category title example"
     *                 ),
     *                 @OA\Property(
     *                     property="slug",
     *                     type="string",
     *                     description="Category's slug",
     *                     example="Category slug example"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response="200", description="Ok",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="category updated with success"),
     *             @OA\Property(property="category", type="object",
     *                     @OA\Property(property="id", type="int", example=1),
     *                     @OA\Property(property="uuid", type="string", example="d166d772-7c61-4e53-95f1-8e0a27748e3a"),
     *                     @OA\Property(property="title", type="string",example="Category title"),
     *                     @OA\Property(property="slug", type="string",example="Category slug"),
     *                     @OA\Property(property="created_at", type="string",example="2024-07-08T03:11:38.000000Z"),
     *                     @OA\Property(property="updated_at", type="string",example="2024-07-08T03:11:38.000000Z"),
     *              ),
     *         )),
     *
     *     @OA\Response(response="401", description="Unauthenticated",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )),
     *
     *     @OA\Response(response="403", description="Unauthorized",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Unauthorized"),
     *         )),
     *
     *     @OA\Response(response="404", description="Not found"),
     * )
     */
    public function update(UpdateCategoryRequest $request, string $uuid): JsonResponse
    {
        $category = $this->categoryService->updateCategory($uuid, $request->validated());

        return response()->json(['message' => 'category updated with success', 'category' => $category]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/category/{uuid}",
     *     summary="delete category",
     *     tags={"Categories"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="uuid",
     *         required=true,
     *         in="path",
     *
     *         @OA\Schema(type="string"),
     *     ),
     *
     *     @OA\Response(response="200", description="Ok",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="category deleted with success"),
     *     )),
     *
     *     @OA\Response(response="401", description="Unauthenticated",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         )),
     *
     *     @OA\Response(response="403", description="Unauthorized",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Unauthorized"),
     *         )),
     *
     *     @OA\Response(response="404", description="Not found"),
     * )
     */
    public function destroy(string $uuid): JsonResponse
    {
        $this->categoryService->deleteCategory($uuid);

        return response()->json(['message' => 'Category deleted with success']);
    }
}
