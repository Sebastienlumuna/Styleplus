<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders (admin only).
     */
    public function index()
    {
        $orders = Order::with(['user', 'items.product', 'latestPayment'])
            ->latest()
            ->paginate(15);
        
        return view('data.orders.table', compact('orders'));
    }

    /**
     * Display the specified order (admin only).
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payments']);
        
        return view('data.orders.admin-show', compact('order'));
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        
        return redirect()->route('admin.orders.index')
            ->with('success', 'Commande supprimée avec succès !');
    }

    /**
     * Export all orders to PDF.
     */
    public function exportPdf()
    {
        $orders = Order::with('user')->latest()->get();

        $totalCommandes = $orders->count();
        $totalPayees = $orders->where('status', 'paid')->count();
        $totalRevenu = $orders->where('status', 'paid')->sum('total');

        $pdf = Pdf::loadView('reports.order', [
            'orders' => $orders,
            'totalCommandes' => $totalCommandes,
            'totalPayees' => $totalPayees,
            'totalRevenu' => $totalRevenu,
        ]);

        return $pdf->download('rapport-commandes.pdf');
    }
}
