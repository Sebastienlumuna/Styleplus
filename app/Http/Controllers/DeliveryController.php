<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Voir mes livraisons (client)
    public function myDeliveries()
    {
        $deliveries = Delivery::with(['order.items.product', 'livreur'])
            ->whereHas('order', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->paginate(10);
        
        return view('deliveries.my-deliveries', compact('deliveries'));
    }

    // Détails d'une livraison
    public function show(Delivery $delivery)
    {
        // Vérifier que l'utilisateur est le propriétaire de la commande ou le livreur
        if ($delivery->order->user_id !== Auth::id() && $delivery->livreur_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }
        
        $delivery->load(['order.items.product', 'order.user', 'livreur']);
        
        return view('deliveries.show', compact('delivery'));
    }
}
