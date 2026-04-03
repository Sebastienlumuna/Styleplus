@extends('layouts.dashboard')
@section('title', 'Mes livraisons')
@section('page-title', 'Mes livraisons 📦')

@section('sidebar-nav')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.client') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Tableau de bord</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('my.deliveries') }}">
                <i class="bi bi-truck"></i>
                <span>Mes livraisons</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('profil.edit') }}">
                <i class="bi bi-person"></i>
                <span>Mon profil</span>
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
    @forelse($deliveries as $delivery)
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Commande #{{ $delivery->order_id }}</h6>
                        @if($delivery->status === 'pending')
                            <span class="badge bg-secondary">En attente</span>
                        @elseif($delivery->status === 'assigned')
                            <span class="badge bg-info">Assignée</span>
                        @elseif($delivery->status === 'in_transit')
                            <span class="badge bg-warning">En transit</span>
                        @elseif($delivery->status === 'delivered')
                            <span class="badge bg-success">Livrée</span>
                        @else
                            <span class="badge bg-danger">Échec</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Adresse:</strong><br>{{ $delivery->address }}, {{ $delivery->city }}</p>
                    <p class="mb-2"><strong>Téléphone:</strong> {{ $delivery->phone }}</p>
                    
                    @if($delivery->livreur)
                        <p class="mb-2"><strong>Livreur:</strong> {{ $delivery->livreur->name }}</p>
                    @endif
                    
                    @if($delivery->delivered_at)
                        <p class="mb-2"><strong>Livrée le:</strong> {{ $delivery->delivered_at->format('d/m/Y à H:i') }}</p>
                    @endif
                    
                    <p class="mb-0"><strong>Total:</strong> {{ number_format($delivery->order->total, 2) }} $</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('deliveries.show', $delivery) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i> Voir détails
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                Vous n'avez pas encore de livraisons
            </div>
        </div>
    @endforelse
</div>

{{ $deliveries->links() }}
@endsection
