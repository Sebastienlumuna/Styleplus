<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

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
        //
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Votre panier est vide ❌');
        }

        // Calcul du total
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        // Création de la commande
        $order = Order::create([
            'user_id' => auth()->id(), // ou null si pas connecté
            'total'   => $total,
            'status'  => 'pending'
        ]);

        // Création des OrderItems
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $productId,
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
            ]);
        }

        // Vider le panier de la session
        session()->forget('cart');

        // Redirection vers paiement (tu passes l'ID de la commande)
        return redirect()->route('payment', ['order' => $order->id]);
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
