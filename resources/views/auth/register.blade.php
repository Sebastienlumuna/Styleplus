@extends('layouts.authlayout')

@section('title', 'Inscription')

@section('content')

<div class="login-card">
    <div class="login-header">
        <h2 class="text-white mb-0">INSCRIPTION</h2>
        <p class="text-light mb-0">Créez votre compte Élégance Noir</p>
    </div>
    
    <div class="login-body">
        {{-- Messages globaux --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="register-form" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nom complet</label>
                <input 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror" 
                    id="name" 
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Votre nom complet" 
                    required
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    id="email" 
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="votre@email.com"
                    required
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    id="password" 
                    name="password"
                    placeholder="Votre mot de passe"
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password_confirmation" 
                    name="password_confirmation"
                    placeholder="Confirmez votre mot de passe"
                    required
                >
            </div>
            
            <button type="submit" class="btn btn-dark w-100 py-3">S'inscrire</button>
        </form>
    </div>
    
    <div class="login-footer">
        <p class="mb-0">Vous avez déjà un compte ? 
            <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Se connecter</a>
        </p>
    </div>
</div>

@endsection
