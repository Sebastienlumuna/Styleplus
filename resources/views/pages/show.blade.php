@extends('layouts.layout')
@section('title', $product->name)
@section('content')

    <!-- Product Detail Section -->
    <section class="py-5 mt-5">
        <div class="container">
            <div class="row">
                <!-- Image produit -->
                <div class="col-md-6">
                    <img src="{{ asset('storage/' . $product->image) }}" 
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
                            <select class="form-select" id="size-select">
                                <option value="s">S</option>
                                <option value="m" selected>M</option>
                                <option value="l">L</option>
                                <option value="xl">XL</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Quantité</label>
                            <div class="input-group">
                                <button class="btn btn-outline-dark" type="button">-</button>
                                <input type="number" class="form-control text-center" 
                                       id="quantity" value="1" min="1">
                                <button class="btn btn-outline-dark" type="button">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton panier -->
                    <form action="" method="POST">
                        @csrf
                        <button class="btn btn-dark btn-lg w-100 py-3" type="submit">
                            Ajouter au panier
                        </button>
                    </form>
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
                            <img src="{{ asset('storage/' . $item->image) }}" 
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

@endsection