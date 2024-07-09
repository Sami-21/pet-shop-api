<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="View user account",
     *      tags={"Users"},
     *
     *     @OA\Response(response="200", description="Ok",
     *
     *     @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="success", type="int", example=1),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdCIsInN1YiI........."),
     *             ),
     *             @OA\Property(property="error", type="string|null", example=null),
     *             @OA\Property(property="errors", type="array",
     *
     *                     @OA\Items(
     *                     type="object",
     *                 )),
     *
     *             @OA\Property(property="extra", type="array",
     *
     *                     @OA\Items(
     *                     type="object",
     *                 )),
     *         )),
     *
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Unauthorized"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="422", description="Unproccessable content"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function me(Request $request): JsonResponse
    {
        $token = (string) $request->bearerToken();
        $response = $this->userService->getUser($token);

        return $this->createJsonResponse($response['success'], $response['data']);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user",
     *     summary="Delete user account",
     *      tags={"Users"},
     *
     *     @OA\Response(response="200", description="Ok"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();
        $response = $this->userService->deleteUser($user);

        return $this->createJsonResponse($response['success'], $response['data']);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/orders",
     *     summary="List all orders for the authenticated user",
     *      tags={"Users"},
     *
     *     @OA\Response(response="200", description="Ok"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Unauthorized"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function getOrders(Request $request): JsonResponse
    {
        $limit = (int) $request->query('limit', '10');
        $sortBy = $request->query('sortBy', 'created_at');
        $descFilter = filter_var($request->input('desc'), FILTER_VALIDATE_BOOLEAN);
        $user = $request->user();
        $response = $this->userService->getUserOrders($user, $limit, $sortBy, $descFilter);

        return $this->createJsonResponse($response['success'], $response['data']);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/create",
     *     summary="Create new user account",
     *      tags={"Users"},
     *
     *     @OA\Response(
     *         response="200",
     *         description="New user created with success"
     *     ),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $response = $this->userService->register($request->validated());

        return $this->createJsonResponse($response['success'], $response['data']);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/forgot-password",
     *     summary="Create a token to reset user password",
     *      tags={"Users"},
     *
     *     @OA\Response(
     *         response="200",
     *         description="New user created with success"
     *     ),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Unauthorized"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="422", description="Unproccessable content"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function forgetPassword(Request $request): void {}

    /**
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     summary="Login to his account",
     *      tags={"Users"},
     *
     *     @OA\Response(
     *         response="200",
     *         description="Login successful"
     *     ),
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *
     *             @OA\Schema(
     *                 required={"email","password"},
     *
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     description="User email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     description="User password"
     *                 ),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(response="401", description="incorrect email/password"),
     *     @OA\Response(response="404", description="Route not found"),
     *     @OA\Response(response="422", description="Unproccessable content"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        $response = $this->userService->login($credentials);

        return $this->createJsonResponse($response['success'], $response['data'], $response['error']);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/logout",
     *     summary="Logout user",
     *      tags={"Users"},
     *
     *     @OA\Response(
     *         response="200",
     *         description="Logout successful"
     *     ),
     *     @OA\Response(response="404", description="Route not found"),
     *     @OA\Response(response="422", description="Unproccessable content"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function logout(): JsonResponse
    {
        $response = $this->userService->logout();

        return $this->createJsonResponse($response['success']);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/reset-password-token",
     *     summary="Reset user password with token",
     *      tags={"Users"},
     *
     *     @OA\Response(
     *         response="200",
     *         description="New user created with success"
     *     ),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function resetPassword(Request $request): void {}

    /**
     * @OA\Put(
     *     path="/api/v1/user/edit",
     *     summary="Update use account",
     *      tags={"Users"},
     *
     *     @OA\Response(
     *         response="200",
     *         description="New user created with success"
     *     ),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Unauthorized"),
     *     @OA\Response(response="404", description="Route not found"),
     *     @OA\Response(response="422", description="Unproccessable content"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function update(UpdateUserRequest $request): JsonResponse
    {
        $response = $this->userService->updateUser($request->user(), $request->validated());

        return $this->createJsonResponse($response['success'], $response['data']);
    }

    private function createJsonResponse(int $success = 0, array $data = [], ?string $error = '', array $errors = [], $extra = []): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'data' => $data,
            'error' => $error,
            'errors' => $errors,
            'extra' => $extra,
        ]);
    }
}
