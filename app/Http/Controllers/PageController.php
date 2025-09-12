<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //
    public function index()
    {
        $categories = Category::all();
        $products = Product::with('category')->paginate(12); 
        return view('pages.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load('category');

    // Produits similaires : même catégorie, exclure le produit en cours
    $related = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->inRandomOrder()
                ->take(4)
                ->get();

    return view('pages.show', compact('product', 'related'));
    }
}
