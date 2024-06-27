<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


/**
 * @OA\Info(
 *     title="Pet Shop API",
 *     version="1.0",
 *     description="Documentation for v1 api of pet store",
 * )
 */
class TestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     @OA\Response(
     *         response="200",
     *         description="Fetch all users"
     *     )
     * )
     */
    public function index()
    {
        return User::all();
    }
}
