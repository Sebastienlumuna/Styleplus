@extends('layouts.layout')
@section('title', 'Dashboard Client')

@section('content')
<div class="container-fluid py-5 mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-bag me-2"></i>Mes commandes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-person me-2"></i>Mon profil
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Bonjour, {{ $user->name }} ! 👋</h1>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card text-white bg-primary">
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
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">{{ number_format($totalSpent, 2, ',', ' ') }} €</h4>
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
            <div class="card">
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
                                            <td>{{ number_format($order->total, 2, ',', ' ') }} €</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'paid' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-outline-primary">Voir</a>
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
        </main>
    </div>
</div>

<style>
.sidebar {
    position: fixed;
    top: 56px; /* Hauteur de la navbar */
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar .nav-link {
    color: #333;
    padding: 0.75rem 1rem;
}

.sidebar .nav-link:hover {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}

.sidebar .nav-link.active {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}

main {
    margin-left: 0;
}

@media (min-width: 768px) {
    main {
        margin-left: 240px;
    }
}
</style>
@endsection
