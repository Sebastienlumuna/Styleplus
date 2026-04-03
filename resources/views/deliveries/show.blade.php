@extends('layouts.dashboard')
@section('title', 'Détails de la livraison')
@section('page-title', 'Détails de la livraison #' . $delivery->id)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Informations de livraison</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Statut:</strong><br>
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
                    <div class="col-md-6">
                        <strong>Commande:</strong><br>
                        #{{ $delivery->order_id }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Adresse de livraison:</strong><br>
                        {{ $delivery->address }}<br>
                        {{ $delivery->city }} @if($delivery->postal_code) - {{ $delivery->postal_code }} @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Téléphone:</strong><br>
                        {{ $delivery->phone }}
                    </div>
                    @if($delivery->livreur)
                        <div class="col-md-6">
                            <strong>Livreur:</strong><br>
                            {{ $delivery->livreur->name }}
                        </div>
                    @endif
                </div>

                @if($delivery->notes)
                    <div class="row mb-3">
                        <div class="col-12">
                            <strong>Instructions:</strong><br>
                            <p class="text-muted">{{ $delivery->notes }}</p>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <strong>Créée le:</strong><br>
                        {{ $delivery->created_at->format('d/m/Y à H:i') }}
                    </div>
                    @if($delivery->delivered_at)
                        <div class="col-md-6">
                            <strong>Livrée le:</strong><br>
                            {{ $delivery->delivered_at->format('d/m/Y à H:i') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Articles de la commande</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($delivery->order->items as $item)
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
                            <th colspan="3" class="text-end">Total:</th>
                            <th>{{ number_format($delivery->order->total, 2) }} $</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Client</h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Nom:</strong><br>{{ $delivery->order->user->name }}</p>
                <p class="mb-0"><strong>Email:</strong><br>{{ $delivery->order->user->email }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
