<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;
    public function definition(): array
    {
        $sizes = ['S', 'M', 'L', 'XL'];
        $colors = ['Rouge', 'Noir', 'Blanc', 'Bleu'];
        return [
            //
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(10),
            'price' => fake()->randomFloat(2, 10, 200),
            'image' => fake()->imageUrl(400, 400, 'fashion', true),
            'stock' => fake()->numberBetween(1, 100),
            'category_id' => Category::inRandomOrder()->first()->id ?? 1, // random catégorie
            'sizes' => implode(',', fake()->randomElements($sizes, rand(1, 3))),
        ];
    }
}
