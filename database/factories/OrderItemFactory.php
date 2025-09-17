<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        $product = Product::factory()->create();
        $quantity = $this->faker->numberBetween(1, 10);

        return [
            'order_id' => Order::factory(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->unit_price,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
