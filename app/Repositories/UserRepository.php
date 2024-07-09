<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
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
