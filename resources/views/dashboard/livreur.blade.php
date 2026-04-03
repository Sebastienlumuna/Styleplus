@extends('layouts.dashboard')
@section('title', 'Dashboard Livreur')
@section('page-title', 'Dashboard Livreur 🚚')
@section('page-subtitle', 'Gérez vos livraisons')

@section('sidebar-nav')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('dashboard.livreur') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Tableau de bord</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('livreur.history') }}">
                <i class="bi bi-clock-history"></i>
                <span>Historique</span>
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
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="card border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Livraisons terminées</div>
                        <div class="h5 mb-0 fw-bold">{{ $totalDelivered }}</div>
                    </div>
                    <i class="bi bi-check-circle fa-2x text-muted"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="card border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">En cours</div>
                        <div class="h5 mb-0 fw-bold">{{ $inProgress }}</div>
                    </div>
                    <i class="bi bi-truck fa-2x text-muted"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mes livraisons en cours -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">🚚 Mes livraisons en cours</h5>
    </div>
    <div class="card-body">
        @forelse($myDeliveries as $delivery)
            <div class="card mb-3 border-start border-primary border-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Commande #{{ $delivery->order_id }}</h6>
                            <p class="mb-1"><strong>Client:</strong> {{ $delivery->order->user->name }}</p>
                            <p class="mb-1"><strong>Adresse:</strong> {{ $delivery->address }}, {{ $delivery->city }}</p>
                            <p class="mb-1"><strong>Téléphone:</strong> {{ $delivery->phone }}</p>
                            @if($delivery->notes)
                                <p class="mb-1"><small class="text-muted">📝 {{ $delivery->notes }}</small></p>
                            @endif
                            <p class="mb-0">
                                <span class="badge bg-{{ $delivery->status === 'assigned' ? 'info' : 'warning' }}">
                                    {{ $delivery->status === 'assigned' ? 'Assignée' : 'En transit' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <p class="mb-2"><strong>{{ number_format($delivery->order->total, 2) }} $</strong></p>
                            @if($delivery->status === 'assigned')
                                <form action="{{ route('livreur.start', $delivery) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning mb-1">
                                        <i class="bi bi-play"></i> Démarrer
                                    </button>
                                </form>
                            @endif
                            @if($delivery->status === 'in_transit')
                                <form action="{{ route('livreur.complete', $delivery) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success mb-1">
                                        <i class="bi bi-check"></i> Livré
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Détails
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted text-center">Aucune livraison en cours</p>
        @endforelse
    </div>
</div>

<!-- Livraisons disponibles -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">📦 Livraisons disponibles</h5>
    </div>
    <div class="card-body">
        @forelse($availableDeliveries as $delivery)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h6>Commande #{{ $delivery->order_id }}</h6>
                            <p class="mb-1"><strong>Client:</strong> {{ $delivery->order->user->name }}</p>
                            <p class="mb-1"><strong>Adresse:</strong> {{ $delivery->address }}, {{ $delivery->city }}</p>
                            <p class="mb-1"><strong>Téléphone:</strong> {{ $delivery->phone }}</p>
                            @if($delivery->notes)
                                <p class="mb-1"><small class="text-muted">📝 {{ $delivery->notes }}</small></p>
                            @endif
                        </div>
                        <div class="col-md-4 text-end">
                            <p class="mb-2"><strong>{{ number_format($delivery->order->total, 2) }} $</strong></p>
                            <form action="{{ route('livreur.assign', $delivery) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-hand-thumbs-up"></i> Prendre en charge
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted text-center">Aucune livraison disponible pour le moment</p>
        @endforelse
    </div>
</div>
@endsection
