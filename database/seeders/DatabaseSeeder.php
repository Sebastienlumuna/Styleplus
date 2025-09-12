<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'Homme', 'slug' => 'homme'],
            ['name' => 'Femme', 'slug' => 'femme'],
            ['name' => 'Enfant', 'slug' => 'enfant'],
        ]);
        
            // Users
            User::factory(10)->create();

            // Produits liés aux catégories
            Product::factory(30)->create();

            // Commandes avec items
            Order::factory(20)->create()->each(function ($order) {
                $items = OrderItem::factory(rand(1, 5))->create(['order_id' => $order->id]);
                $order->update(['total' => $items->sum('price')]);
            });

    }
}
