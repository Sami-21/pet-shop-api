<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['open', 'pending payment', 'paid', 'shipped', 'cancelled'];

        foreach ($statuses as $status) {
            OrderStatus::factory()->create(['title' => $status]);
        }
    }
}
