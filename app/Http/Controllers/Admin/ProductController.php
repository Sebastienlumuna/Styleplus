<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('data.products.table', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('data.products.show', [
            'product' => new Product(),
            'categories' => $categories,
            'edit' => false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:5', // min:5 pour forcer au moins 5
            'category_id' => 'nullable|exists:categories,id',
            'sizes'       => 'nullable|string',
            'image'       => 'required|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produit ajouté avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $categories = Category::all();
        return view('data.products.show', [
            'product' => $product,
            'categories' => $categories,
            'edit' => true
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return redirect()->route('admin.products.show', $product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'sizes'       => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'quantity'    => 'nullable|integer|min:1',
        ]);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Gestion du stock
        if ($request->filled('quantity')) {
            $product->stock += (int)$request->input('quantity');
        }
        // Si pas de champ quantité, ne change pas le stock
        $product->fill($validated);
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès !');
    }

    /**
     * Export products to PDF.
     */
    public function exportPdf()
    {
        $products = \App\Models\Product::with('category')->get();
        $totalStock = $products->sum('stock');

        $pdf = Pdf::loadView('reports.product', [
            'products' => $products,
            'totalStock' => $totalStock,
        ]);

        return $pdf->download('rapport-produits.pdf');
    }
}
