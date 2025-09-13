@extends('layouts.dashboard')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrateur 🛠️')
@section('page-subtitle', 'Gérez votre plateforme e-commerce')

@section('page-actions')
    <div class="btn-group">
        <button type="button" class="btn btn-sm btn-outline-primary">Exporter</button>
        <button type="button" class="btn btn-sm btn-primary">Ajouter</button>
    </div>
@endsection

@section('sidebar-nav')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('dashboard.admin') }}">
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
            <a class="nav-link" href="#">
                <i class="bi bi-box"></i>
                <span>Produits</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
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

<!-- Statistiques générales -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-dashboard border-start border-primary border-4">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                            Utilisateurs</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalUsers }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-dashboard border-start border-success border-4">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">
                            Produits</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalProducts }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-dashboard border-start border-info border-4">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">
                            Commandes</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $totalOrders }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-bag fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-dashboard border-start border-warning border-4">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                            Chiffre d'affaires</div>
                        <div class="h5 mb-0 fw-bold text-gray-800">{{ number_format($totalRevenue, 2, ',', ' ') }} €</div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-currency-euro fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Commandes récentes -->
<div class="row">
    <div class="col-lg-8">
        <div class="card card-dashboard mb-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Commandes récentes</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Total</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($order->total, 2, ',', ' ') }} €</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-dashboard mb-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Statut des commandes</h6>
            </div>
            <div class="card-body">
                @foreach($ordersByStatus as $status => $count)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-capitalize">{{ $status }}</span>
                        <span class="badge bg-primary">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card card-dashboard mb-4">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Revenus 30 derniers jours</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h3 class="text-success">{{ number_format($revenueLast30Days, 2, ',', ' ') }} €</h3>
                    <p class="text-muted">Chiffre d'affaires</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
