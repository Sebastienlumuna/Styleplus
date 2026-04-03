@extends('layouts.dashboard')
@section('title', 'Statistiques')
@section('page-title', 'Statistiques et Monitoring')

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
            <a class="nav-link" href="{{ route('admin.orders.index') }}">
                <i class="bi bi-bag"></i>
                <span>Commandes</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.statistics') }}">
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
        <div class="card border-start border-primary border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Utilisateurs</div>
                        <div class="h5 mb-0 fw-bold">{{ $totalUsers }}</div>
                    </div>
                    <i class="bi bi-people fa-2x text-muted"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-success border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Produits</div>
                        <div class="h5 mb-0 fw-bold">{{ $totalProducts }}</div>
                    </div>
                    <i class="bi bi-box fa-2x text-muted"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-info border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Commandes</div>
                        <div class="h5 mb-0 fw-bold">{{ $totalOrders }}</div>
                    </div>
                    <i class="bi bi-bag fa-2x text-muted"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-start border-warning border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Revenus</div>
                        <div class="h5 mb-0 fw-bold">{{ number_format($totalRevenue, 2) }} $</div>
                    </div>
                    <i class="bi bi-currency-dollar fa-2x text-muted"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="row mb-4">
    <!-- Revenus par mois -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Revenus par mois (12 derniers mois)</h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Commandes par statut -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Commandes par statut</h6>
            </div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Commandes par jour -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Commandes par jour (30 derniers jours)</h6>
            </div>
            <div class="card-body">
                <canvas id="ordersChart" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Nouveaux utilisateurs -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Nouveaux utilisateurs (6 mois)</h6>
            </div>
            <div class="card-body">
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Top produits -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Top 10 produits les plus vendus</h6>
            </div>
            <div class="card-body">
                <canvas id="topProductsChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <!-- Revenus par catégorie -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-primary">Revenus par catégorie</h6>
            </div>
            <div class="card-body">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Produits en rupture de stock -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 fw-bold text-danger">Produits en stock faible (< 10)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Catégorie</th>
                                <th>Stock</th>
                                <th>Prix</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->stock == 0 ? 'danger' : 'warning' }}">
                                            {{ $product->stock }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($product->price, 2) }} $</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tous les produits ont un stock suffisant</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Revenus par mois
const revenueCtx = document.getElementById('revenueChart');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: [
            @foreach($revenueByMonth as $item)
                '{{ date("M Y", mktime(0, 0, 0, $item->month, 1, $item->year)) }}',
            @endforeach
        ],
        datasets: [{
            label: 'Revenus ($)',
            data: [
                @foreach($revenueByMonth as $item)
                    {{ $item->revenue }},
                @endforeach
            ],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true
            }
        }
    }
});

// Commandes par statut
const statusCtx = document.getElementById('statusChart');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($ordersByStatus as $status => $count)
                '{{ ucfirst($status) }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($ordersByStatus as $count)
                    {{ $count }},
                @endforeach
            ],
            backgroundColor: [
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)'
            ]
        }]
    }
});

// Commandes par jour
const ordersCtx = document.getElementById('ordersChart');
new Chart(ordersCtx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($ordersByDay as $item)
                '{{ date("d/m", strtotime($item->date)) }}',
            @endforeach
        ],
        datasets: [{
            label: 'Commandes',
            data: [
                @foreach($ordersByDay as $item)
                    {{ $item->count }},
                @endforeach
            ],
            backgroundColor: 'rgba(54, 162, 235, 0.8)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});

// Nouveaux utilisateurs
const usersCtx = document.getElementById('usersChart');
new Chart(usersCtx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($newUsersByMonth as $item)
                '{{ date("M Y", mktime(0, 0, 0, $item->month, 1, $item->year)) }}',
            @endforeach
        ],
        datasets: [{
            label: 'Nouveaux utilisateurs',
            data: [
                @foreach($newUsersByMonth as $item)
                    {{ $item->count }},
                @endforeach
            ],
            backgroundColor: 'rgba(153, 102, 255, 0.8)'
        }]
    }
});

// Top produits
const topProductsCtx = document.getElementById('topProductsChart');
new Chart(topProductsCtx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($topProducts as $product)
                '{{ Str::limit($product->name, 20) }}',
            @endforeach
        ],
        datasets: [{
            label: 'Quantité vendue',
            data: [
                @foreach($topProducts as $product)
                    {{ $product->total_sold ?? 0 }},
                @endforeach
            ],
            backgroundColor: 'rgba(255, 159, 64, 0.8)'
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: true
    }
});

// Revenus par catégorie
const categoryCtx = document.getElementById('categoryChart');
new Chart(categoryCtx, {
    type: 'pie',
    data: {
        labels: [
            @foreach($revenueByCategory as $item)
                '{{ $item->name }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($revenueByCategory as $item)
                    {{ $item->revenue }},
                @endforeach
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 159, 64, 0.8)'
            ]
        }]
    }
});
</script>
@endpush
