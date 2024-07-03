<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid(),
            'type' => fake()->randomElement(['credit_card', 'cash_on_delivery', 'bank_transfer']),
            'details' => json_encode([]),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Payment $payment) {
            switch ($payment->type) {
                case 'credit_card':
                    $payment->details = json_encode([
                        "holder_name" => fake()->name(),
                        "number" => fake()->creditCardNumber(),
                        "ccv" => fake()->numberBetween(100, 999),
                        "expire_date" => fake()->creditCardExpirationDateString()
                    ]);

                    break;
                case 'cash_on_delivery':
                    $payment->details = json_encode([
                        "first_name" => fake()->firstName(),
                        "last_name" => fake()->lastName(),
                        "address" => fake()->address()
                    ]);
                    break;
                case 'bank_transfer':
                    $payment->details = json_encode([
                        "swift" => fake()->swiftBicNumber(),
                        "iban" => fake()->iban(),
                        "name" => fake()->name()
                    ]);
                    break;

                default:
                    # code...
                    break;
            }
            $payment->save();
        });
    }
}
