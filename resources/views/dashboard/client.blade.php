@extends('layouts.dashboard')
@section('title', 'Dashboard Client')
@section('page-title', 'Bonjour, ' . $user->name . ' ! 👋')
@section('page-subtitle', 'Gérez vos commandes et votre compte')

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

<!-- Statistiques -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card card-dashboard text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $totalOrders }}</h4>
                        <p class="card-text">Commandes totales</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-bag display-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-dashboard text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ number_format($totalSpent, 2, ',', ' ') }} $</h4>
                        <p class="card-text">Total dépensé</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-euro display-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Commandes récentes -->
<div class="card card-dashboard">
    <div class="card-header">
        <h5 class="card-title mb-0">Mes commandes récentes</h5>
    </div>
    <div class="card-body">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>N° Commande</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>{{ number_format($order->total, 2, ',', ' ') }} $</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('order.show', $order) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                    @if($order->status === 'pending')
                                        <a href="{{ route('payment', $order) }}" class="btn btn-sm btn-outline-success ms-1">Payer</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        @else
            <div class="text-center py-5">
                <i class="bi bi-bag-x display-1 text-muted"></i>
                <p class="mt-3">Aucune commande pour le moment</p>
                <a href="{{ route('home') }}" class="btn btn-primary">Commencer mes achats</a>
            </div>
        @endif
    </div>
</div>
@endsection
