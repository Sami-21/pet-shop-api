<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Pet Shop API",
 *     version="1.0",
 *     description="Documentation for v1 api of pet store , this API is a recruitement test from Buckhill.",
 * )
 */
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
     *
     *     @OA\Response(response="200", description="Ok"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Unauthorized"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="422", description="Unproccessable content"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function me(Request $request)
    {
        $token = $request->bearerToken();
        $response = $this->userService->getUser($token);

        return $this->createJsonResponse($response);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user",
     *     summary="Delete user account",
     *
     *     @OA\Response(response="200", description="Ok"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        $response = $this->userService->deleteUser($user);

        return $this->createJsonResponse($response);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/orders",
     *     summary="List all orders for the authenticated user",
     *
     *     @OA\Response(response="200", description="Ok"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Unauthorized"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function getOrders(Request $request)
    {
        $limit = $request->query('limit', 10);
        $sortBy = $request->query('sortBy', 'created_at');
        $descFilter = filter_var($request->input('desc'), FILTER_VALIDATE_BOOLEAN);
        $user = $request->user();
        $response = $this->userService->getUserOrders($user, $limit, $sortBy, $descFilter);

        return $this->createJsonResponse($response);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/create",
     *     summary="Create new user account",
     *
     *     @OA\Response(
     *         response="200",
     *         description="New user created with success"
     *     ),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $response = $this->userService->register($request->validated());

        return $this->createJsonResponse($response);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/forgot-password",
     *     summary="Create a token to reset user password",
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
    public function forgetPassword(Request $request) {}

    /**
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     summary="Login to his account",
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
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $response = $this->userService->login($credentials);

        return $this->createJsonResponse($response);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/logout",
     *     summary="Logout user",
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
    public function logout(Request $request)
    {
        $response = $this->userService->logout();

        return $this->createJsonResponse($response);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/reset-password-token",
     *     summary="Reset user password with token",
     *
     *     @OA\Response(
     *         response="200",
     *         description="New user created with success"
     *     ),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function resetPassword(Request $request) {}

    /**
     * @OA\Put(
     *     path="/api/v1/user/edit",
     *     summary="Update use account",
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
    public function update(UpdateUserRequest $request)
    {
        $response = $this->userService->updateUser($request->user(), $request->validated());

        return $this->createJsonResponse($response);
    }

    private function createJsonResponse(array $payload): JsonResponse
    {
        return response()->json([
            'success' => array_key_exists('success', $payload) ? $payload['success'] : 0,
            'data' => array_key_exists('data', $payload) ? $payload['data'] : [],
            'error' => array_key_exists('error', $payload) ? $payload['error'] : null,
            'errors' => array_key_exists('errors', $payload) ? $payload['errors'] : [],
            'extra' => array_key_exists('extra', $payload) ? $payload['extra'] : [],
        ]);
    }
}
