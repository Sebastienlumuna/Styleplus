@extends('layouts.dashboard')
@section('title', 'Détails de la commande #' . $order->id)
@section('page-title', 'Commande #' . $order->id)

@section('sidebar-nav')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.admin') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Tableau de bord</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="bi bi-people"></i>
                <span>Utilisateurs</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.products.index') }}">
                <i class="bi bi-box"></i>
                <span>Produits</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.orders.index') }}">
                <i class="bi bi-bag"></i>
                <span>Commandes</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="bi bi-graph-up"></i>
                <span>Statistiques</span>
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
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informations de la commande</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Numéro de commande :</strong><br>
                        #{{ $order->id }}
                    </div>
                    <div class="col-md-6">
                        <strong>Date :</strong><br>
                        {{ $order->created_at->format('d/m/Y à H:i') }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Client :</strong><br>
                        {{ $order->user->name }}<br>
                        <small class="text-muted">{{ $order->user->email }}</small>
                    </div>
                    <div class="col-md-6">
                        <strong>Statut :</strong><br>
                        @if($order->status === 'pending')
                            <span class="badge bg-warning">En attente</span>
                        @elseif($order->status === 'paid')
                            <span class="badge bg-success">Payée</span>
                        @elseif($order->status === 'cancelled')
                            <span class="badge bg-danger">Annulée</span>
                        @else
                            <span class="badge bg-secondary">{{ $order->status }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Articles commandés</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix unitaire</th>
                            <th>Quantité</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ number_format($item->price, 2) }} $</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price * $item->quantity, 2) }} $</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total :</th>
                            <th>{{ number_format($order->total, 2) }} $</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Paiements</h5>
            </div>
            <div class="card-body">
                @forelse($order->payments as $payment)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between">
                            <span>{{ number_format($payment->amount, 2) }} $</span>
                            <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        <small class="text-muted">
                            {{ $payment->payment_method }}<br>
                            {{ $payment->created_at->format('d/m/Y H:i') }}
                        </small>
                        @if($payment->status === 'completed')
                            <div class="mt-2">
                                <a href="{{ route('invoice.show', $payment) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-receipt"></i> Facture
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted mb-0">Aucun paiement</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100 mb-2">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" 
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
