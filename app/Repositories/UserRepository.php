<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\JwtToken;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Str;

class UserRepository implements UserRepositoryInterface
{
    public function createUser(array $data): User
    {
        return User::create(
            array_merge($data, [
                'uuid' => Str::uuid(),
                'last_login_at' => Carbon::now(),
                'password' => Hash::make($data['password']),
            ])
        );
    }

    public function createJwtToken(array $data): JwtToken
    {
        return JwtToken::create([
            'user_id' => $data['user_id'],
            'token_title' => 'authentication token',
            // 'restrictions' =>$data['restrictions'],
            // 'permissions' =>$data['permissions'],
            'expired_at' => $data['expired_at'],
            // 'last_used_at' => $data['last_used_at'],
            // 'refreshed_at' => $data['refreshed_at'],
        ]);
    }

    public function getUserOrders(User $user, int $limit, string $sortBy, bool $descFilter)
    {
        if ($descFilter) {
            return $user->orders()->orderBy($sortBy, 'desc')->paginate($limit);
        } else {
            return $user->orders()->orderBy($sortBy, 'asc')->paginate($limit);
        }
    }

    public function destroyUser(User $user): void
    {
        $user->delete();

    }

    public function updateUser(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }
}
