<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function createUser(array $data): User;

    public function getUserOrders(User $user, int $limit, string $sortBy, bool $descFilter);

    public function destroyUser(User $user): void;

    public function updateUser(User $user, array $data): User;
}
