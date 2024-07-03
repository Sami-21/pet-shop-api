<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\JwtService;
use Auth;
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

    protected JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }
    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="View user account",
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
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user",
     *     summary="Delete user account",
     *     @OA\Response(response="200", description="Ok"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function destroy(Request $request)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/orders",
     *     summary="List all orders for the authenticated user",
     *     @OA\Response(response="200", description="Ok"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Unauthorized"),
     *     @OA\Response(response="404", description="Not found"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function getOrders(Request $request)
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/create",
     *     summary="Create new user account",
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
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/forgot-password",
     *     summary="Create a token to reset user password",
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
    public function forgetPassword(Request $request)
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     summary="Login to his account",
     *     @OA\Response(
     *         response="200",
     *         description="Login successful"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"email","password"},
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
     *     @OA\Response(response="401", description="incorrect email/password"),
     *     @OA\Response(response="404", description="Route not found"),
     *     @OA\Response(response="422", description="Unproccessable content"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function login(Request $request)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/logout",
     *     summary="Logout user",
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
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/reset-password-token",
     *     summary="Reset user password with token",
     *     @OA\Response(
     *         response="200",
     *         description="New user created with success"
     *     ),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="500", description="Internal server error"),
     * )
     */
    public function resetPassword(Request $request)
    {
    }

    /**
     * @OA\Put(
     *     path="/api/v1/user/edit",
     *     summary="Update use account",
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
    }
}
