@extends('layouts.layout')
@section('title', 'Panier')
@section('content')

<section class="py-5 mt-5">
    <div class="container">
        <h1 class="text-center mb-5">VOTRE PANIER</h1>
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div id="cart-items">
                            @if(count($cart) > 0)
                                @foreach($cart as $id => $item)
                                    <div class="d-flex align-items-center mb-3 cart-item" data-id="{{ $id }}">
                                        <img src="{{ route('product.image', $id) }}" 
                                             alt="{{ $item['name'] }}" 
                                             style="width:80px; height:80px; object-fit:cover; margin-right:15px;">
                                        <div class="flex-grow-1">
                                            <h6>{{ $item['name'] }}</h6>
                                            <p class="mb-0">Taille: {{ $item['size'] ?? 'N/A' }}</p>
                                            <p class="mb-0">{{ $item['quantity'] }} x {{ number_format($item['price'],2,',',' ') }} €</p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-danger remove-item">
                                                <i class="bi bi-trash me-1"></i>Supprimer
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5" id="empty-cart-message">
                                    <i class="bi bi-cart-x display-1 text-muted"></i>
                                    <p class="mt-3">Votre panier est vide</p>
                                    <a href="{{ route('home') }}" class="btn btn-dark mt-2">Continuer vos achats</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">RÉSUMÉ DE LA COMMANDE</h5>
                        <div class="d-flex justify-content-between mt-4">
                            <span>Sous-total</span>
                            <span id="subtotal">{{ number_format($subtotal,2,',',' ') }} €</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span>Livraison</span>
                            <span id="shipping">0,00 €</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mt-2 fw-bold">
                            <span>Total</span>
                            <span id="total">{{ number_format($subtotal,2,',',' ') }} €</span>
                        </div>

                        {{-- Bouton déclencheur du modal --}}
                        @if(count($cart) > 0)
                            <a href="#" class="btn btn-dark w-100 mt-4 py-3" id="checkout-btn" data-bs-toggle="modal" data-bs-target="#confirmOrderModal">
                                Valider la commande
                            </a>
                        @else
                            <a href="#" class="btn btn-dark w-100 mt-4 py-3 disabled">Panier vide</a>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-muted small"><i class="bi bi-truck"></i> Livraison offerte à partir de 100€ d'achat</p>
                    <p class="text-muted small"><i class="bi arrow-return-left"></i> Retours gratuits sous 30 jours</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ✅ Modal de confirmation --}}
<div class="modal fade" id="confirmOrderModal" tabindex="-1" aria-labelledby="confirmOrderLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmOrderLabel">Confirmation de commande</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        Êtes-vous sûr de vouloir valider cette commande et passer au paiement ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
        
        {{-- Formulaire caché qui sera soumis --}}
        <form action="{{ route('order.store') }}" method="POST" id="orderForm">
            @csrf
            <button type="submit" class="btn btn-dark">Oui, valider</button>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
/* Animation pour les notifications */
.alert.position-fixed {
    animation: slideInRight 0.3s ease-out;
}
@keyframes slideInRight {
    from { transform: translateX(100%); opacity:0; }
    to { transform: translateX(0); opacity:1; }
}

/* Amélioration des boutons */
.remove-item { transition: all 0.2s ease; }
.remove-item:hover { transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
.remove-item:disabled { opacity: 0.6; cursor: not-allowed; }

/* Bouton de commande désactivé */
#checkout-btn.disabled { opacity: 0.6; cursor: not-allowed; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Fonction utilitaire pour formater les prix de manière sécurisée
    function formatPrice(price) {
        const numPrice = parseFloat(price);
        if (isNaN(numPrice)) {
            console.warn('Prix invalide:', price);
            return '0,00';
        }
        return numPrice.toFixed(2).replace('.', ',');
    }

    // Attacher les événements de suppression au chargement
    attachRemoveEvents();

    function updateCartUI(cart, subtotal){
        const cartItems = document.getElementById('cart-items');
        const emptyMsg = document.getElementById('empty-cart-message');
        const subtotalElem = document.getElementById('subtotal');
        const totalElem = document.getElementById('total');
        const checkoutBtn = document.getElementById('checkout-btn');

        // Mettre à jour les totaux
        if(subtotalElem) subtotalElem.innerText = subtotal.toFixed(2).replace('.',',') + " €";
        if(totalElem) totalElem.innerText = subtotal.toFixed(2).replace('.',',') + " €";

        // Mettre à jour le badge du panier
        const badge = document.querySelector('#cart-count');
        if(badge){
            const totalQuantity = Object.values(cart).reduce((a,b)=>a+b.quantity,0);
            badge.innerText = totalQuantity;
            badge.style.display = totalQuantity > 0 ? 'inline' : 'none';
        }

        // Gérer l'affichage du panier vide
        if(Object.keys(cart).length === 0){
            if(emptyMsg) emptyMsg.style.display = 'block';
            if(cartItems) cartItems.innerHTML = '';
            if(checkoutBtn) { 
                checkoutBtn.disabled = true; 
                checkoutBtn.textContent = 'Panier vide'; 
                checkoutBtn.classList.add('disabled'); 
            }
            return; // Sortir de la fonction si le panier est vide
        }

        // Panier non vide
        if(emptyMsg) emptyMsg.style.display = 'none';
        if(checkoutBtn) { 
            checkoutBtn.disabled = false; 
            checkoutBtn.textContent = 'Valider la commande'; 
            checkoutBtn.classList.remove('disabled'); 
        }

        // Reconstruire les éléments du panier seulement si nécessaire
        if(cartItems) {
            // Vérifier si la reconstruction est nécessaire
            const currentItems = cartItems.querySelectorAll('.cart-item');
            const cartIds = Object.keys(cart);
            
            // Si le nombre d'éléments correspond, on peut juste mettre à jour
            if(currentItems.length === cartIds.length) {
                currentItems.forEach(item => {
                    const id = item.dataset.id;
                    if(cart[id]) {
                        const quantityElem = item.querySelector('p:last-child');
                        if(quantityElem) {
                            quantityElem.textContent = `${cart[id].quantity} x ${formatPrice(cart[id].price)} €`;
                        }
                    }
                });
                return; // Pas besoin de reconstruire
            }

            // Reconstruction complète nécessaire
            cartItems.innerHTML = '';
            const fragment = document.createDocumentFragment();
            
            for(const id in cart){
                const item = cart[id];
                const div = document.createElement('div');
                div.className = 'd-flex align-items-center mb-3 cart-item';
                div.setAttribute('data-id', id);
                div.innerHTML = `
                    <img src="{{ url('/product-image') }}/${id}" style="width:80px; height:80px; object-fit:cover; margin-right:15px;">
                    <div class="flex-grow-1">
                        <h6>${item.name}</h6>
                        <p class="mb-0">Taille: ${item.size ?? 'N/A'}</p>
                        <p class="mb-0">${item.quantity} x ${formatPrice(item.price)} €</p>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-danger remove-item" type="button">
                            <i class="bi bi-trash me-1"></i>Supprimer
                        </button>
                    </div>
                `;
                fragment.appendChild(div);
            }
            
            cartItems.appendChild(fragment);
            
            // Réattacher les événements après reconstruction
            attachRemoveEvents();
        }
    }

    // Fonction pour attacher les événements de suppression
    function attachRemoveEvents() {
        const cartItems = document.getElementById('cart-items');
        if (!cartItems) return;
        cartItems.removeEventListener('click', handleRemoveClick);
        cartItems.addEventListener('click', handleRemoveClick);
    }

    // Fonction de gestion des clics de suppression
    function handleRemoveClick(e) {
        const button = e.target.closest('.remove-item');
        if (!button || button.disabled) return;
        e.preventDefault();
        e.stopPropagation();
        
        const cartItem = button.closest('.cart-item');
        if (!cartItem) return;

        const id = cartItem.dataset.id;
        const itemName = cartItem.querySelector('h6').textContent;

        // Désactiver le bouton immédiatement
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Suppression...';

        // Faire la requête de suppression
        removeFromCart(id, itemName, button);
    }

    // Fonction pour supprimer un produit du panier
    function removeFromCart(productId, itemName, button) {
        fetch(`/cart/remove/${productId}`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            }
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`Erreur HTTP: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                // Mettre à jour l'interface avec les nouvelles données
                updateCartUI(data.cart, data.subtotal);
                showNotification(`"${itemName}" a été supprimé du panier`, 'success');
            } else {
                throw new Error(data.message || 'Erreur de suppression');
            }
        })
        .catch(err => {
            console.error('Erreur de suppression:', err);
            button.disabled = false;
            button.innerHTML = '<i class="bi bi-trash me-1"></i>Supprimer';
            showNotification(`Erreur: ${err.message}`, 'error');
        });
    }

    function showNotification(message, type = 'success'){
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        alertDiv.style.cssText = 'top:20px; right:20px; z-index:9999; min-width:300px;';
        alertDiv.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close float-end" onclick="this.parentElement.remove()"></button>
        `;
        document.body.appendChild(alertDiv);
        setTimeout(()=>{ if(alertDiv.parentElement) alertDiv.remove(); }, 3000);
    }
});
</script>

@endsection
