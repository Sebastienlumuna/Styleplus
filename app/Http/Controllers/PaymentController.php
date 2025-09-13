<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Order;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Formulaire de paiement
    public function index(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('home')->with('error', 'Cette commande n\'est plus valide ❌');
        }
        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $methods = config('payment.methods');
        return view('pages.payment', compact('order','methods'));
    }

    // Traitement du paiement
    public function process(Request $request, Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->route('home')->with('error', 'Cette commande n\'est plus valide pour le paiement ❌');
        }
        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette commande');
        }

        $available = array_keys(config('payment.methods') ?? []);
        $mobileMethods = config('payment.mobile_money', []);

        $rules = [
            'method' => ['required', Rule::in($available)],
        ];

        $method = $request->input('method');

        if (in_array($method, $mobileMethods)) {
            $rules['phone'] = ['required','regex:/^\+?[0-9]{8,15}$/'];
        } elseif ($method === 'carte_bancaire') {
            $rules['cardNumber'] = 'required|digits_between:12,19';
            $rules['expiryDate'] = ['required','regex:/^(0[1-9]|1[0-2])\/\d{2}$/'];
            $rules['cvv'] = 'required|digits_between:3,4';
        }

        try {
            $validated = $request->validate($rules);

            // Validation supplémentaire pour la date d'expiration
            if ($method === 'carte_bancaire' && isset($validated['expiryDate'])) {
                $expiryDate = $validated['expiryDate'];
                $currentDate = now();
                $expiryMonth = (int) substr($expiryDate, 0, 2);
                $expiryYear = 2000 + (int) substr($expiryDate, 3, 2);
                $expiryDateTime = \Carbon\Carbon::createFromDate($expiryYear, $expiryMonth, 1)->endOfMonth();
                
                if ($expiryDateTime->isPast()) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['expiryDate' => 'La carte a expiré']);
                }
            }

            // Simulation du traitement du paiement
            $transactionId = strtoupper($method) . '-' . time() . '-' . rand(1000, 9999);
            
            // Création du paiement
            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'method' => $method,
                'status' => 'paid', // simulation - en production, ceci viendrait du provider
                'amount' => (int) round($order->total * 100), // en centimes
                'currency' => 'XAF',
                'transaction_id' => $transactionId,
                'data' => $method === 'carte_bancaire' ? [
                    'card_last4' => substr(str_replace(' ', '', $validated['cardNumber']), -4),
                    'expiry_date' => $validated['expiryDate'],
                ] : [
                    'phone' => $validated['phone'] ?? null
                ],
                'metadata' => [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'processed_at' => now()->toISOString(),
                ]
            ]);

            // Mise à jour du statut de la commande
            $order->update(['status' => 'paid']);

            return redirect()->route('invoice.show', $payment)
                ->with('success', "Paiement effectué avec succès ! Transaction: {$transactionId}");

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Erreur de paiement: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'method' => $method,
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors du traitement du paiement. Veuillez réessayer.');
        }
    }
}
