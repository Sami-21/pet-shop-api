<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create(['email' => 'admin@buckhill.co.uk', 'password' => Hash::make('admin'), 'is_admin' => true]);

        User::factory()->count(10)->create()->each(function ($user) {
            $user->orders()->saveMany(Order::factory()->count(5)->make());
        });
    }
}
