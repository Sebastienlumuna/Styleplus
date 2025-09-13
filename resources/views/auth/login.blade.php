@extends('layouts.authlayout')

@section('title', 'Connexion')

@section('content')

<div class="login-card">
    <div class="login-header">
        <h2 class="text-white mb-0">CONNEXION</h2>
        <p class="text-light mb-0">Accédez à votre compte Élégance Noir</p>
    </div>
    
    <div class="login-body">
        {{-- Messages de succès --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Messages d'erreur globaux --}}
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Erreurs de validation --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="login-form" action="{{ route('login') }}" method="POST">
            @csrf
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
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>
            
            <button type="submit" class="btn btn-dark w-100 py-3">Se connecter</button>
        </form>
    </div>
    
    <div class="login-footer">
        <p class="mb-0">Vous n'avez pas de compte ? 
            <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Créer un compte</a>
        </p>
    </div>
</div>

@endsection
