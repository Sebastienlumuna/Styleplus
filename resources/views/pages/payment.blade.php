@extends('layouts.layout')
@section('title', 'Paiement')
@section('content')

<section class="py-5 mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="text-center mb-5">FINALISER VOTRE COMMANDE</h1>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">COORDONNÉES</h5>
                        
                        {{-- Message d'erreur global --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form action="{{ route('payment.process', $order->id) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">Prénom</label>
                                    <input type="text" name="firstName" class="form-control @error('firstName') is-invalid @enderror" 
                                           value="{{ old('firstName') }}" required>
                                    @error('firstName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Nom</label>
                                    <input type="text" name="lastName" class="form-control @error('lastName') is-invalid @enderror" 
                                           value="{{ old('lastName') }}" required>
                                    @error('lastName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Adresse</label>
                                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address') }}" required>
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="postalCode" class="form-label">Code postal</label>
                                    <input type="text" name="postalCode" class="form-control @error('postalCode') is-invalid @enderror"
                                           value="{{ old('postalCode') }}" required>
                                    @error('postalCode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="city" class="form-label">Ville</label>
                                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                                           value="{{ old('city') }}" required>
                                    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <h5 class="card-title mb-4">PAIEMENT</h5>
                            
                            <div class="mb-3">
                                <label for="cardNumber" class="form-label">Numéro de carte</label>
                                <input type="text" name="cardNumber" class="form-control @error('cardNumber') is-invalid @enderror"
                                       placeholder="1234 5678 9012 3456" value="{{ old('cardNumber') }}" required>
                                @error('cardNumber') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="expiryDate" class="form-label">Date d'expiration</label>
                                    <input type="text" name="expiryDate" class="form-control @error('expiryDate') is-invalid @enderror"
                                           placeholder="MM/AA" value="{{ old('expiryDate') }}" required>
                                    @error('expiryDate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <input type="text" name="cvv" class="form-control @error('cvv') is-invalid @enderror"
                                           placeholder="123" value="{{ old('cvv') }}" required>
                                    @error('cvv') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-dark btn-lg w-100 py-3">Payer maintenant</button>
                        </form>
                    </div>
                </div>
                
                <div class="text-center">
                    <p class="text-muted small">
                        <i class="bi bi-lock-fill"></i> Vos données sont sécurisées et chiffrées
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
