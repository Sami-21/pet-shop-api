<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Exception;

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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            if ($this->jwtService->validateToken($token)) {
                $parsedToken = $this->jwtService->parseToken($token);
                $userId = $parsedToken->claims()->get('uuid');
                $user = User::where('uuid', $userId)->first();
                if ($user) {
                    return $next($request);
                }
                return response()->json(['error' => 'User not found'], 401);
            }
            return response()->json(['error' => 'Invalid token'], 401);
        } catch (Exception $e) {
            return response()->json(['error' => 'Unauthenticated', 'error' => $e->getMessage()], 401);
        }
    }
}
