@extends('layouts.dashboard')
@section('title', 'Gestion des commandes')
@section('page-title', 'Gestion des commandes')

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
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="text-muted">Total : {{ $orders->total() }} commande(s)</span>
    </div>
    <a href="{{ route('admin.orders.export.pdf') }}" class="btn btn-danger">
        <i class="bi bi-file-pdf"></i> Exporter PDF
    </a>
</div>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Date</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Paiement</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ number_format($order->total, 2) }} $</td>
                    <td>
                        @if($order->status === 'pending')
                            <span class="badge bg-warning">En attente</span>
                        @elseif($order->status === 'paid')
                            <span class="badge bg-success">Payée</span>
                        @elseif($order->status === 'cancelled')
                            <span class="badge bg-danger">Annulée</span>
                        @else
                            <span class="badge bg-secondary">{{ $order->status }}</span>
                        @endif
                    </td>
                    <td>
                        @if($order->latestPayment)
                            <span class="badge bg-{{ $order->latestPayment->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->latestPayment->status) }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                            <i class="bi bi-eye"></i>
                        </a>
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Supprimer cette commande ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Aucune commande</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $orders->links() }}
@endsection
