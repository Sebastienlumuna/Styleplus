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

    public function getImage(Product $product)
    {
        // Si l'image est stockée en base64
        if (str_starts_with($product->image, 'data:image/')) {
            // Extraire le type MIME et les données base64
            $imageData = explode(',', $product->image);
            $mimeType = str_replace('data:', '', str_replace(';base64', '', $imageData[0]));
            $imageContent = base64_decode($imageData[1]);
            
            return response($imageContent)
                ->header('Content-Type', $mimeType)
                ->header('Cache-Control', 'public, max-age=3600');
        }
        
        // Si l'image est une URL externe
        if (filter_var($product->image, FILTER_VALIDATE_URL)) {
            return redirect($product->image);
        }
        
        // Si l'image est un chemin de fichier local
        if (file_exists(public_path($product->image))) {
            return response()->file(public_path($product->image));
        }
        
        // Si l'image est dans le storage
        if (file_exists(storage_path('app/public/' . $product->image))) {
            return response()->file(storage_path('app/public/' . $product->image));
        }
        
        // Image par défaut si rien n'est trouvé
        $defaultImagePath = public_path('images/default-product.jpg');
        if (file_exists($defaultImagePath)) {
            return response()->file($defaultImagePath);
        }
        
        // Retourner une image SVG par défaut si aucune image n'est trouvée
        $svg = '<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg">
            <rect width="100%" height="100%" fill="#f8f9fa"/>
            <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="16" fill="#6c757d" text-anchor="middle" dy=".3em">
                Image non disponible
            </text>
        </svg>';
        
        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
