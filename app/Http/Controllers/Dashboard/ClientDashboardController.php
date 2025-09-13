<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class ClientDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:client']);
    }

    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les commandes du client
        $orders = Order::where('user_id', $user->id)
                      ->with('items.product')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        // Statistiques du client
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)
                          ->where('status', 'paid')
                          ->sum('total');
        
        return view('dashboard.client', compact('user', 'orders', 'totalOrders', 'totalSpent'));
    }
}
