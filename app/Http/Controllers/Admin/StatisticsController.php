<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'paid')->sum('total');
        
        // Revenus par mois (12 derniers mois)
        $revenueByMonth = Order::where('status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Commandes par statut
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
        
        // Top 10 produits les plus vendus
        $topProducts = Product::withCount(['orderItems as total_sold' => function($query) {
            $query->select(DB::raw('SUM(quantity)'));
        }])
        ->orderBy('total_sold', 'desc')
        ->take(10)
        ->get();
        
        // Nouveaux utilisateurs par mois (6 derniers mois)
        $newUsersByMonth = User::where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Commandes par jour (30 derniers jours)
        $ordersByDay = Order::where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        // Produits en rupture de stock
        $lowStockProducts = Product::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->take(10)
            ->get();
        
        // Revenus par catégorie
        $revenueByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'paid')
            ->select(
                'categories.name',
                DB::raw('SUM(order_items.price * order_items.quantity) as revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('revenue', 'desc')
            ->get();
        
        return view('admin.statistics', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'revenueByMonth',
            'ordersByStatus',
            'topProducts',
            'newUsersByMonth',
            'ordersByDay',
            'lowStockProducts',
            'revenueByCategory'
        ));
    }
}
