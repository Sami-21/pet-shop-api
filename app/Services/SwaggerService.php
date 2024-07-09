<?php

namespace App\Services;

/**
 * @OA\Info(
 *     title="Pet Shop API",
 *     version="1.0",
 *     description="Documentation for v1 api of pet store , this API is a recruitement test from Buckhill.",
 * ),
 *
 * @OA\Tag(
 *     name="Users",
 *     description="Users API endpoint"
 * ),
 * @OA\Tag(
 *     name="Brands",
 *     description="Brands API endpoint"
 * ),
 *  * @OA\Tag(
 *     name="Categories",
 *     description="Categories API endpoint"
 * ),
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token in the format: 'Bearer {token}'"
 * )
 */
class SwaggerService
{
    // This class is intentionally left empty.
}
