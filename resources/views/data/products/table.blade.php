@extends('layouts.dashboard')
@section('title', 'Produits')
@section('page-title', 'Gestion des produits')
@section('sidebar-nav')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard.admin') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Tableau de bord</span>
            </a>
        </li>
    
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.products.index') }}">
                <i class="bi bi-box"></i>
                <span>Produits</span>
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


<div class="mb-3 text-end">
    <a href="{{ route('admin.products.report.pdf') }}" class="btn btn-outline-success">
        <i class="bi bi-printer"></i> Exporter PDF produits
    </a>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Ajouter un produit
    </a>
</div>

<div class="card card-dashboard">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Tailles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>{{ number_format($product->price,2,',',' ') }} €</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            @foreach(explode(',', $product->sizes) as $size)
                                <span class="badge bg-secondary">{{ strtoupper(trim($size)) }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                            <!-- Un seul bouton qui passe l'id au modal global -->
                            <button class="btn btn-sm btn-outline-danger ms-1" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}">
                                Supprimer
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $products->links() }}
        </div>
    </div>
</div>

<!-- Modal global unique pour suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Supprimer le produit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir supprimer le produit <span id="modalProductName" class="fw-bold"></span> ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
        <form id="deleteProductForm" method="POST" action="{{ route('admin.products.destroy', $product->id) }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Oui, supprimer</button>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteModal');
    var deleteForm = document.getElementById('deleteProductForm');
    var modalProductName = document.getElementById('modalProductName');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var productId = button.getAttribute('data-product-id');
        var productName = button.getAttribute('data-product-name');
        // Met à jour l'action du formulaire vers la route POST dédiée
        deleteForm.action = "{{ url('admin/products') }}/" + productId + "/delete";
        modalProductName.textContent = productName;
    });
});
</script>
@endpush
@endsection