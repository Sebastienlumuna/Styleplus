@extends('layouts.layout')
@section('title', $product->name)
@section('content')

<!-- Product Detail Section -->
<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <!-- Image produit -->
            <div class="col-md-6">
                <img src="{{ route('product.image', $product) }}" 
                     class="img-fluid rounded product-detail-img" 
                     alt="{{ $product->name }}">
            </div>

            <!-- Infos produit -->
            <div class="col-md-6">
                <p class="text-muted">
                    {{ $product->category->name ?? 'Autres' }}
                </p>
                <h1 class="fw-bold">{{ $product->name }}</h1>
                <p class="h4 my-3">{{ number_format($product->price, 2, ',', ' ') }} €</p>

                <div class="my-4">
                    <h5>Description</h5>
                    <p>{{ $product->description }}</p>
                </div>

                <!-- Taille + Quantité -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="size-select" class="form-label">Taille</label>
                        <select class="form-select" id="size-select" name="size">
                            @foreach(explode(',', $product->sizes) as $size)
                                <option value="{{ trim($size) }}">{{ strtoupper(trim($size)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Quantité</label>
                        <div class="input-group">
                            <button class="btn btn-outline-dark btn-minus" type="button">-</button>
                            <input type="number" class="form-control text-center" 
                                   id="quantity" name="quantity" value="1" min="1">
                            <button class="btn btn-outline-dark btn-plus" type="button">+</button>
                        </div>
                    </div>
                </div>

                <!-- Bouton panier -->
                <button class="btn btn-dark btn-lg w-100 py-3" id="add-to-cart">
                    Ajouter au panier
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">VOUS AIMEREZ AUSSI</h2>
        <div class="row g-4">
            @foreach ($related as $item)
                <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                    <div class="card product-card h-100">
                        <img src="{{ route('product.image', $item) }}" 
                             class="card-img-top product-img" 
                             alt="{{ $item->name }}">
                        <div class="card-body">
                            <span class="badge bg-dark mb-2">{{ $item->category->name ?? 'Autres' }}</span>
                            <h5 class="card-title product-title">{{ $item->name }}</h5>
                            <p class="card-text product-price">
                                {{ number_format($item->price, 2, ',', ' ') }} €
                            </p>
                            <a href="{{ route('products.show', $item->id) }}" 
                               class="btn btn-outline-dark">Voir détail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Script JS pour la quantité et ajout au panier dynamique --}}
<script>
document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("quantity");
    const btnMinus = document.querySelector(".btn-minus");
    const btnPlus = document.querySelector(".btn-plus");

    btnMinus.addEventListener("click", () => {
        let current = parseInt(input.value);
        if (current > 1) input.value = current - 1;
    });

    btnPlus.addEventListener("click", () => {
        let current = parseInt(input.value);
        input.value = current + 1;
    });

    const addBtn = document.getElementById('add-to-cart');
    const sizeSelect = document.getElementById('size-select');

    addBtn.addEventListener('click', () => {
        const productId = "{{ $product->id }}";
        const quantity = parseInt(input.value);
        const size = sizeSelect.value;

        fetch(`{{ url('/cart/add') }}/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ quantity: quantity, size: size })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP ${res.status}: ${res.statusText}`);
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                const badge = document.querySelector('#cart-count');
                if(badge) {
                    badge.innerText = data.cartCount;
                    badge.style.display = data.cartCount > 0 ? 'inline' : 'none';
                }
                
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success position-fixed';
                alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                alertDiv.innerHTML = `
                    <i class="bi bi-check-circle me-2"></i>
                    Produit ajouté au panier avec succès !
                    <button type="button" class="btn-close float-end" onclick="this.parentElement.remove()"></button>
                `;
                document.body.appendChild(alertDiv);
                
                setTimeout(() => {
                    if(alertDiv.parentElement) {
                        alertDiv.remove();
                    }
                }, 3000);
            } else {
                throw new Error(data.message || 'Erreur inconnue');
            }
        })
        .catch(err => {
            console.error('Erreur ajout panier:', err);
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger position-fixed';
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                <i class="bi bi-exclamation-triangle me-2"></i>
                Erreur lors de l'ajout au panier: ${err.message}
                <button type="button" class="btn-close float-end" onclick="this.parentElement.remove()"></button>
            `;
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                if(alertDiv.parentElement) {
                    alertDiv.remove();
                }
            }, 5000);
        });
    });
});
</script>

@endsection
