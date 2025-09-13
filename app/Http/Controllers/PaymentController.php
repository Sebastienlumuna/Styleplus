<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PaymentController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('home')->with('error', 'Cette commande n\'est plus valide ❌');
        }

        return view('pages.payment', compact('order'));
    }

    public function process(Request $request, Order $order)
    {
        // Validation des champs
        $validated = $request->validate([
            'firstName'   => 'required|string|max:255',
            'lastName'    => 'required|string|max:255',
            'email'       => 'required|email',
            'address'     => 'required|string|max:255',
            'postalCode'  => 'required|string|max:20',
            'city'        => 'required|string|max:100',
            'cardNumber'  => 'required|digits_between:12,19',
                'expiryDate' => 'required|regex:/^(0[1-9]|1[0-2])\/\d{2}$/',
            'cvv'         => 'required|digits:3',
        ]);

        // 👉 Simulation paiement réussi
        $order->update([
            'status' => 'paid',
        ]);

        return redirect()->route('home')->with('success', 'Paiement réussi ✅, merci pour votre commande !');
    }
}
