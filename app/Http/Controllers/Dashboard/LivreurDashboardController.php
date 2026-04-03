<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;

class LivreurDashboardController extends Controller
{
    public function index()
    {
        $livreurId = Auth::id();
        
        // Livraisons assignées au livreur
        $myDeliveries = Delivery::with(['order.user', 'order.items.product'])
            ->where('livreur_id', $livreurId)
            ->whereIn('status', ['assigned', 'in_transit'])
            ->latest()
            ->get();
        
        // Livraisons disponibles (non assignées)
        $availableDeliveries = Delivery::with(['order.user', 'order.items.product'])
            ->whereNull('livreur_id')
            ->where('status', 'pending')
            ->latest()
            ->get();
        
        // Statistiques
        $totalDelivered = Delivery::where('livreur_id', $livreurId)
            ->where('status', 'delivered')
            ->count();
        
        $inProgress = Delivery::where('livreur_id', $livreurId)
            ->whereIn('status', ['assigned', 'in_transit'])
            ->count();
        
        return view('dashboard.livreur', compact(
            'myDeliveries',
            'availableDeliveries',
            'totalDelivered',
            'inProgress'
        ));
    }

    public function assign(Delivery $delivery)
    {
        if ($delivery->livreur_id !== null) {
            return redirect()->back()->with('error', 'Cette livraison est déjà assignée');
        }
        
        $delivery->update([
            'livreur_id' => Auth::id(),
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Livraison assignée avec succès !');
    }

    public function startDelivery(Delivery $delivery)
    {
        if ($delivery->livreur_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Cette livraison ne vous est pas assignée');
        }
        
        $delivery->update(['status' => 'in_transit']);
        
        return redirect()->back()->with('success', 'Livraison en cours !');
    }

    public function completeDelivery(Delivery $delivery)
    {
        if ($delivery->livreur_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Cette livraison ne vous est pas assignée');
        }
        
        $delivery->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
        
        // Mettre à jour le statut de la commande
        $delivery->order->update(['status' => 'shipped']);
        
        return redirect()->back()->with('success', 'Livraison terminée avec succès !');
    }

    public function history()
    {
        $deliveries = Delivery::with(['order.user', 'order.items.product'])
            ->where('livreur_id', Auth::id())
            ->latest()
            ->paginate(20);
        
        return view('deliveries.history', compact('deliveries'));
    }
}
