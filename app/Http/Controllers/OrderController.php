<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
    
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Votre panier est vide ❌');
        }
    
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
    
        try {
            DB::transaction(function () use ($cart, $total, &$order) {
                // Création de la commande
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'total'   => $total,
                    'status'  => 'pending'
                ]);
    
                foreach ($cart as $productId => $item) {
                    $product = Product::findOrFail($productId);
    
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stock insuffisant pour le produit {$product->name} ❌");
                    }
    
                    // Créer l'OrderItem
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $productId,
                        'quantity'   => $item['quantity'],
                        'price'      => $item['price'],
                    ]);
    
                    // Décrémenter le stock
                    $product->decrement('stock', $item['quantity']);
                }
    
                // Vider le panier
                session()->forget('cart');
            });
        } catch (\Exception $e) {
            return redirect()->route('cart')->with('error', $e->getMessage());
        }
    
        return redirect()->route('payment', $order);
    }


   

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
