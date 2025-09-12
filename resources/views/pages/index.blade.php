@extends('layouts.layout')

@section('title', 'Accueil')

@section('content')

<div class="row">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold text-white">STYLE & CONFORT POUR TOUS</h1>
                    <p class="lead text-light">Découvrez notre collection de vêtements pour Homme, Femme et Enfant</p>
                    <a href="#products" class="btn btn-outline-light btn-lg mt-3">Découvrir</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <!-- Bouton Tous -->
                        <button class="btn btn-outline-dark active" data-filter="all">Tous</button>

                        <!-- Boutons dynamiques par catégorie -->
                        @foreach($categories as $category)
                            <button class="btn btn-outline-dark" data-filter="{{ strtolower($category->slug) }}">
                                {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col text-center">
                    <h2 class="section-title">NOTRE COLLECTION</h2>
                    <p class="section-subtitle">Des vêtements confortables et stylés pour toute la famille</p>
                </div>
            </div>
            <div class="row g-4 products-container">
                <!-- Produits -->
                @foreach($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 product-card" 
                     data-category="{{ strtolower($product->category->slug ?? 'autre') }}">
                    <div class="card h-100">
                        {{-- Image --}}
                        <img src="{{ asset('storage/'.$product->image) }}" 
                             class="card-img-top product-img" 
                             alt="{{ $product->name }}">
                        
                        <div class="card-body">
                            {{-- Catégorie (badge) --}}
                            <span class="badge bg-dark mb-2">
                                {{ $product->category->name ?? 'Sans catégorie' }}
                            </span>

                            {{-- Nom produit --}}
                            <h5 class="card-title product-title">{{ $product->name }}</h5>

                            {{-- Prix --}}
                            <p class="card-text product-price">
                                {{ number_format($product->price, 2, ',', ' ') }} €
                            </p>

                            {{-- Lien détail --}}
                            <a href="{{ route('products.show', $product->id) }}" 
                               class="btn btn-outline-dark">
                                Voir détail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

{{-- Pagination --}}
<div class="mt-3">
    {{ $products->links('vendor.pagination.bootstrap-5') }}
</div>

{{-- Script JS pour filtrage --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const buttons = document.querySelectorAll("[data-filter]");
        const products = document.querySelectorAll(".product-card");

        buttons.forEach(button => {
            button.addEventListener("click", () => {
                // Enlever l'état actif de tous les boutons
                buttons.forEach(btn => btn.classList.remove("active"));
                button.classList.add("active");

                const filter = button.getAttribute("data-filter");

                products.forEach(product => {
                    const category = product.getAttribute("data-category");
                    if (filter === "all" || category === filter) {
                        product.style.display = "block";
                    } else {
                        product.style.display = "none";
                    }
                });
            });
        });
    });
</script>

@endsection
