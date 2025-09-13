<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        // Statistiques générales
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'paid')->sum('total');
        
        // Commandes récentes
        $recentOrders = Order::with(['user', 'items.product'])
                           ->orderBy('created_at', 'desc')
                           ->limit(10)
                           ->get();
        
        // Statistiques des commandes par statut
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
                              ->groupBy('status')
                              ->pluck('count', 'status');
        
        // Revenus des 30 derniers jours
        $revenueLast30Days = Order::where('status', 'paid')
                                 ->where('created_at', '>=', now()->subDays(30))
                                 ->sum('total');
        
        return view('dashboard.admin', compact(
            'totalUsers', 
            'totalProducts', 
            'totalOrders', 
            'totalRevenue',
            'recentOrders',
            'ordersByStatus',
            'revenueLast30Days'
        ));
    }
}
