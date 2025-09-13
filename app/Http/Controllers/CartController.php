<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        return view('pages.cart', compact('cart', 'subtotal'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);

        $cart = session()->get('cart', []);

        if(isset($cart[$id])){
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => $quantity,
                "image" => $product->image,
                "size" => $request->input('size', null)
            ];
        }

        session()->put('cart', $cart);
        $cartCount = array_sum(array_column($cart, 'quantity'));
        $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        return response()->json([
            'cartCount' => $cartCount,
            'subtotal' => $subtotal,
            'cart' => $cart
        ]);
    }

    public function remove(Request $request, $id)
    {
        try {
            $cart = session()->get('cart', []);
            
            // Vérifier que l'ID existe dans le panier
            if (!isset($cart[$id])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produit non trouvé dans le panier'
                ], 404);
            }

            // Supprimer l'élément
            unset($cart[$id]);

            // Sauvegarder le panier mis à jour
            session()->put('cart', $cart);
            
            // Calculer les nouveaux totaux
            $cartCount = array_sum(array_column($cart, 'quantity'));
            $subtotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

            return response()->json([
                'success' => true,
                'cartCount' => $cartCount,
                'subtotal' => $subtotal,
                'cart' => $cart
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression'
            ], 500);
        }
    }
}
