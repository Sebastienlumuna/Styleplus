@extends('layouts.dashboard')
@section('title', $edit ? 'Modifier produit' : 'Ajouter produit')
@section('page-title', $edit ? 'Modifier le produit' : 'Ajouter un produit')

@section('sidebar-nav')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('dashboard.admin') }}">
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
            <a class="nav-link" href="#">
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
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" 
      action="{{ $edit ? route('admin.products.update', $product) : route('admin.products.store') }}" 
      enctype="multipart/form-data">
    @csrf
    @if($edit)
        @method('PUT')
    @endif

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Nom</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Catégorie</label>
            <select name="category_id" class="form-select">
                <option value="">-- Choisir --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @if(old('category_id', $product->category_id)==$cat->id) selected @endif>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control">{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">Prix ($)</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="col-md-4">
            @if($edit)
                <label class="form-label">Stock existant : <span class="fw-bold">{{ $product->stock }}</span></label>
                <input type="number" name="quantity" class="form-control" placeholder="Ajouter quantité">
                <div class="form-text">Laisser vide pour ne pas modifier le stock</div>
            @else
                <label class="form-label">Stock initial</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock') }}" required>
            @endif
        </div>
        <div class="col-md-4">
            <label class="form-label">Tailles (séparées par virgule)</label>
            <input type="text" name="sizes" class="form-control" value="{{ old('sizes', $product->sizes) }}">
            <div class="form-text">Ex: S,M,L,XL</div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Image</label>
        @if($edit && $product->image)
            <div class="mb-2">
                @if(Str::startsWith($product->image, 'http'))
                    <img src="{{ $product->image }}" alt="" width="100">
                @else
                    <img src="{{ asset('storage/'.$product->image) }}" alt="" width="100">
                @endif
            </div>
        @endif
        <input type="file" name="image" class="form-control" {{ $edit ? '' : 'required' }}>
        <div class="form-text">Max 2Mo. Formats: jpg, png, webp...</div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary">{{ $edit ? 'Mettre à jour' : 'Ajouter' }}</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-link">Retour</a>
    </div>
</form>
@endsection