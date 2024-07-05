<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $orderStatusIds = OrderStatus::pluck('id')->toArray();

        return [
            'user_id' => User::factory(),
            'uuid' => fake()->uuid(),
            'order_status_id' => fake()->randomElement($orderStatusIds),
            'payment_id' => null,
            'products' => json_encode([
                'product' => fake()->uuid(),
                'quantity' => fake()->numberBetween(
                    0,
                    100
                ),
            ]),
            'address' => json_encode([
                'billing' => fake()->address(),
                'shiping' => fake()->address(),
            ]),
            'delivery_fee' => fake()->randomFloat(2, 0, 100),
            'amount' => fake()->randomFloat(2, 10, 1000),
            'shipped_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Order $order) {
            $paidStatusId = OrderStatus::where('title', 'paid')->first()->id;
            if ($order->order_status_id == $paidStatusId) {
                $payment = Payment::factory()->create();
                $order->payment_id = $payment->id;
                $order->save();
            }
        });
    }
}
