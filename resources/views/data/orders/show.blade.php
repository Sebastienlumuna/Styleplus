@extends('layouts.dashboard')
@section('title', 'Détail Commande #'.$order->id)
@section('page-title', 'Commande #'.$order->id)
@section('page-subtitle', 'Détail de votre commande')
@section('sidebar-nav')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('dashboard.client') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Tableau de bord</span>
            </a>
        </li>
     
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="bi bi-house"></i>
                <span>Retour au site</span>
            </a>
        </li>
    </ul>
@endsection

@section('content')
<div class="card card-dashboard mb-4">
    <div class="card-header">
        <strong>Résumé de la commande</strong>
    </div>
    <div class="card-body">
        <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Statut :</strong>
            <span class="badge bg-{{ $order->status === 'paid' ? 'success' : 'warning' }}">
                {{ ucfirst($order->status) }}
            </span>
        </p>
        <p><strong>Total :</strong> {{ number_format($order->total, 2, ',', ' ') }} $</p>
    </div>
</div>

<div class="card card-dashboard mb-4">
    <div class="card-header">
        <strong>Articles</strong>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? 'Produit supprimé' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 2, ',', ' ') }} $</td>
                            <td>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} $</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($order->status === 'pending')
    <div class="text-end">
        <a href="{{ route('payment', $order) }}" class="btn btn-success">
            <i class="bi bi-credit-card"></i> Payer cette commande
        </a>
    </div>
@endif

<a href="{{ route('dashboard.client') }}" class="btn btn-link mt-3">
    <i class="bi bi-arrow-left"></i> Retour à mes commandes
</a>
@endsection