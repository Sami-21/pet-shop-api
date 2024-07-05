<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Exception;
use Illuminate\Http\Request;

class ValidateJwtToken
{
    protected JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->bearerToken();
            if (! $token) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            if ($this->jwtService->validateToken($token)) {
                $user = $this->jwtService->parseToken($token);
                if ($user) {
                    $request->setUserResolver(function () use ($user) {
                        return $user;
                    });

                    return $next($request);
                }

                return response()->json(['error' => 'Invalid token'], 401);
            }

            return response()->json(['error' => 'Invalid token'], 401);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthenticated', 'error' => $e->getMessage()], 401);
        }
    }
}
