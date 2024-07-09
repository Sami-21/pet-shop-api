<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Auth;
use Carbon\Carbon;
use DateTimeImmutable;
use Str;

class UserService
{
    protected JwtService $jwtService;

    protected UserRepository $userRepository;

    public function __construct(JwtService $jwtService, UserRepository $UserRepositoryInstance)
    {
        $this->userRepository = $UserRepositoryInstance;
        $this->jwtService = $jwtService;
    }

    /**
     * @return array{data: array{user: mixed}, success: int}
     */
    public function getUser(string $token): array
    {
        $user = $this->jwtService->parseToken($token);

        return ['data' => ['user' => $user], 'success' => 1];
    }

    /**
     * @return array{data: array{orders: mixed}, success: int}
     */
    public function getUserOrders(User $user, int $limit, string $sortBy, bool $descFilter): array
    {
        $orders = $this->userRepository->getUserOrders($user, $limit, $sortBy, $descFilter);

        return ['data' => ['orders' => $orders], 'success' => 1];
    }

    /**
     * @return array{data: array{token: string}, success: int}
     */
    public function login(mixed $credentials): array
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $now = new DateTimeImmutable();
            $this->userRepository->updateUser($user, ['last_login_at' => $now]);
            $token = $this->jwtService->generateToken('uuid', $user->uuid);
            $user->token()->update(['expired_at' => $now->modify('+'.config('jwt.jwt_expiration').' minutes'), 'refreshed_at' => $now]);

            return ['data' => ['token' => $token], 'success' => 1, 'error' => null];
        }

        return ['data' => [], 'error' => 'Failed to authenticate user, check your credentials', 'success' => 0];
    }

    /**
     * @return array{data: array{token: string}, success: int}
     */
    public function register(array $data): array
    {
        $user = $this->userRepository->createUser($data);
        $token = $this->jwtService->generateToken('uuid', $user->uuid);
        Auth::login($user);
        $now = new DateTimeImmutable();
        $user->token()->create([
            'token_title' => $user->first_name.' '.$user->last_name.' token',
            'uuid' => Str::uuid(),
            'expired_at' => $now->modify('+'.config('jwt.jwt_expiration').' minutes'),
        ]);

        return ['data' => ['token' => $token], 'success' => 1];
    }

    /**
     * @return array{success: int}
     */
    public function logout(): array
    {
        $user = Auth::user();
        $user->token()->update(['expired_at' => Carbon::now()]);
        Auth::logout();

        return ['success' => 1];
    }

    /**
     * @return array{data: array{message: string}, success: int}
     */
    public function deleteUser(User $user): array
    {
        $this->userRepository->destroyUser($user);

        return ['data' => ['message' => 'user deleted with success'], 'success' => 1];
    }

    /**
     * @return array{data: array{message: string}, success: int}
     */
    public function updateUser(User $user, array $data): array
    {
        $this->userRepository->updateUser($user, $data);

        return ['data' => ['message' => 'user updated with success'], 'success' => 1];
    }
}
