<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Affichage de la facture
    public function show(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }
        return view('pages.invoice', compact('payment'));
    }

    // Export PDF
    public function download(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        $pdf = Pdf::loadView('reports.facture', compact('payment'));
        return $pdf->download('facture-'.$payment->id.'.pdf');
    }
}
