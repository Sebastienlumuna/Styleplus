@extends('layouts.dashboard')
@section('title', 'Mon profil')
@section('page-title', 'Mon profil')
@section('page-subtitle', 'Gérez vos informations personnelles')

@section('sidebar-nav')
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Tableau de bord</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.products.index') }}">
                <i class="bi bi-box"></i>
                <span>Produits</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('profil.edit') }}">
                <i class="bi bi-person"></i>
                <span>Profil</span>
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
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card card-dashboard">
            <div class="card-body">
                <h5 class="mb-4 text-primary"><i class="bi bi-person-circle me-2"></i>Modifier mes informations</h5>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('profil.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Adresse email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <hr>
                    <h6 class="text-secondary mb-3">Changer le mot de passe</h6>
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password" placeholder="Laisser vide pour ne pas changer">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection