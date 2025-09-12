<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $quantity = fake()->numberBetween(1, 5);
        $price = $product->price * $quantity;

        return [
            'order_id' => Order::inRandomOrder()->first()->id ?? Order::factory(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price,
        ];
    }
}
