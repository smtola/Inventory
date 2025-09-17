<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['purchase', 'sales']),
            'user_id' => User::factory(),
            'supplier_id' => Supplier::factory(),
            'customer_info' => $this->faker->name() . ', ' . $this->faker->phoneNumber(),
            'order_date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'total_amount' => $this->faker->randomFloat(2, 50, 1000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
