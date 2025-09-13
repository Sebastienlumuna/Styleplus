@extends('layouts.layout')

@section('title', 'Transaction')

@section('content')
<div class="container my-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-success text-white text-center py-4">
            <h2 class="mb-0">Facture Paiement</h2>
            <small>Transaction réussie ✅</small>
        </div>
        <div class="card-body p-5">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="fw-bold">Informations du paiement</h5>
                    <p><strong>ID Paiement :</strong> #{{ $payment->id }}</p>
                    <p><strong>ID Commande :</strong> #{{ $payment->order_id }}</p>
                    <p><strong>Méthode :</strong> {{ ucfirst($payment->method) }}</p>
                    <p><strong>Montant :</strong> {{ number_format($payment->amount/100, 2) }} {{ $payment->currency }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5 class="fw-bold">Informations utilisateur</h5>
                    <p><strong>Nom :</strong> {{ $payment->user->name }}</p>
                    <p><strong>Email :</strong> {{ $payment->user->email }}</p>
                    <p><strong>Date :</strong> {{ $payment->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Status :</strong> 
                        <span class="badge bg-success">{{ ucfirst($payment->status) }}</span>
                    </p>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    Retour à l'accueil
                </a>
                <a href="{{ route('invoice.pdf', $payment) }}" class="btn btn-success">
                    Télécharger la facture PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
