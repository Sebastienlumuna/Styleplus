@extends('layouts.dashboard')
@section('title', 'Historique des livraisons')
@section('page-title', 'Historique des livraisons')

@section('sidebar-nav')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.livreur') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Tableau de bord</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('livreur.history') }}">
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
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Commande</th>
                <th>Client</th>
                <th>Adresse</th>
                <th>Montant</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deliveries as $delivery)
                <tr>
                    <td>#{{ $delivery->order_id }}</td>
                    <td>{{ $delivery->order->user->name }}</td>
                    <td>{{ $delivery->city }}</td>
                    <td>{{ number_format($delivery->order->total, 2) }} $</td>
                    <td>
                        @if($delivery->status === 'delivered')
                            <span class="badge bg-success">Livrée</span>
                        @elseif($delivery->status === 'in_transit')
                            <span class="badge bg-warning">En transit</span>
                        @elseif($delivery->status === 'assigned')
                            <span class="badge bg-info">Assignée</span>
                        @else
                            <span class="badge bg-danger">{{ ucfirst($delivery->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $delivery->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Aucune livraison</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $deliveries->links() }}
@endsection
