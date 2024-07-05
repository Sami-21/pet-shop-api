<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Auth;
use DateTimeImmutable;

class UserService
{
    protected JwtService $jwtService;

    protected UserRepository $userRepository;

    public function __construct(JwtService $jwtService, UserRepository $UserRepositoryInstance)
    {
        $this->userRepository = $UserRepositoryInstance;
        $this->jwtService = $jwtService;
    }

    public function getUser(string $token)
    {
        $user = $this->jwtService->parseToken($token);

        return ['data' => ['user' => $user], 'success' => 1];
    }

    public function getUserOrders(User $user, int $limit, string $sortBy, bool $descFilter)
    {
        $orders = $this->userRepository->getUserOrders($user, $limit, $sortBy, $descFilter);

        return ['data' => ['orders' => $orders], 'success' => 1];
    }

    public function login(mixed $credentials): array
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $now = new DateTimeImmutable();
            $this->userRepository->updateUser($user, ['last_login_at' => $now]);
            $token = $this->jwtService->generateToken('uuid', $user->uuid);

            // $this->userRepository->createJwtToken(['user_id' => $user->id, 'expired_at' => $now->modify('+' . config('jwt.jwt_expiration') . ' minutes')]);
            return ['data' => ['token' => $token], 'success' => 1];
        }

        return ['error' => 'Failed to authenticate user, check your credentials', 'success' => false];
    }

    public function register(array $data): array
    {
        $user = $this->userRepository->createUser($data);
        $token = $this->jwtService->generateToken('uuid', $user->uuid);

        // $now   = new DateTimeImmutable();
        // $this->userRepository->createJwtToken(['user_id' => $user->id, 'expired_at' => $now->modify('+' . config('jwt.jwt_expiration') . ' minutes')]);
        return ['data' => ['token' => $token], 'success' => 1];
    }

    public function logout()
    {
        Auth::logout();

        return ['success' => 1];
    }

    public function deleteUser(User $user)
    {
        $this->userRepository->destroyUser($user);

        return ['data' => ['message' => 'user deleted with success'], 'success' => 1];
    }

    public function updateUser(User $user, array $data)
    {
        $this->userRepository->updateUser($user, $data);

        return ['data' => ['message' => 'user updated with success'], 'success' => 1];
    }
}
