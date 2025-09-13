@extends('layouts.layout')

@section('title', 'Payment')

@section('content')
<div class="payment-wrapper">
    <div class="payment-card">
        <div class="payment-header">
            <h1>Paiement</h1>
            <p>Commande #{{ $order->id }}</p>
            <p><strong>Montant :</strong> {{ number_format($order->total, 2) }} XAF</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="payment-user">
            <strong>Utilisateur</strong>
            <span>{{ Auth::user()->name }} ({{ Auth::user()->email }})</span>
        </div>

        <form method="POST" action="{{ route('payment.process', $order) }}">
            @csrf

            <div class="form-group mb-3">
                <label for="method">Mode de paiement <span class="text-danger">*</span></label>
                <select id="method" name="method" class="form-control @error('method') is-invalid @enderror" required>
                    <option value="">-- Choisir un mode de paiement --</option>
                    @foreach($methods as $key => $meta)
                        <option value="{{ $key }}" {{ old('method') == $key ? 'selected' : '' }}>
                            {{ $meta['label'] }}
                        </option>
                    @endforeach
                </select>
                @error('method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- mobile money fields --}}
            <div id="mobile-fields" style="display:none;">
                <div class="form-group mb-3">
                    <label for="phone">Numéro de téléphone <span class="text-danger">*</span></label>
                    <input id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                           placeholder="+243..." value="{{ old('phone') }}" />
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Format: +243XXXXXXXXX</small>
                </div>
            </div>

            {{-- card fields --}}
            <div id="card-fields" style="display:none;">
                <div class="form-group mb-3">
                    <label for="cardNumber">Numéro de carte <span class="text-danger">*</span></label>
                    <input id="cardNumber" name="cardNumber" class="form-control @error('cardNumber') is-invalid @enderror" 
                           placeholder="4242 4242 4242 4242" value="{{ old('cardNumber') }}" maxlength="19" />
                    @error('cardNumber')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="expiryDate">Expiration (MM/YY) <span class="text-danger">*</span></label>
                        <input id="expiryDate" name="expiryDate" class="form-control @error('expiryDate') is-invalid @enderror" 
                               placeholder="04/26" value="{{ old('expiryDate') }}" maxlength="5" />
                        @error('expiryDate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cvv">CVV <span class="text-danger">*</span></label>
                        <input id="cvv" name="cvv" class="form-control @error('cvv') is-invalid @enderror" 
                               placeholder="123" value="{{ old('cvv') }}" maxlength="4" />
                        @error('cvv')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <small class="form-text text-muted d-block mb-3">⚠️ Ne stockez jamais le CVV sur votre serveur.</small>
            </div>

            <button class="btn btn-primary mt-3" type="submit">💳 Payer</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const methodSelect = document.getElementById('method');
    const mobile = document.getElementById('mobile-fields');
    const card = document.getElementById('card-fields');
    const form = document.querySelector('form');

    function toggle() {
        const v = methodSelect.value;
        const mobileMethods = @json(config('payment.mobile_money'));
        
        if (mobileMethods.includes(v)) {
            mobile.style.display = 'block';
            card.style.display = 'none';
        } else if (v === 'carte_bancaire') {
            mobile.style.display = 'none';
            card.style.display = 'block';
        } else {
            mobile.style.display = 'none';
            card.style.display = 'none';
        }
    }

    // Validation du numéro de téléphone
    function validatePhone(phone) {
        const phoneRegex = /^\+?[0-9]{8,15}$/;
        return phoneRegex.test(phone);
    }

    // Validation du numéro de carte
    function validateCardNumber(cardNumber) {
        const cardRegex = /^[0-9]{12,19}$/;
        return cardRegex.test(cardNumber.replace(/\s/g, ''));
    }

    // Validation de la date d'expiration
    function validateExpiryDate(expiryDate) {
        const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
        if (!expiryRegex.test(expiryDate)) return false;
        
        const [month, year] = expiryDate.split('/');
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear() % 100;
        const currentMonth = currentDate.getMonth() + 1;
        
        const expYear = parseInt(year);
        const expMonth = parseInt(month);
        
        return expYear > currentYear || (expYear === currentYear && expMonth >= currentMonth);
    }

    // Validation du CVV
    function validateCVV(cvv) {
        const cvvRegex = /^[0-9]{3,4}$/;
        return cvvRegex.test(cvv);
    }

    // Validation du formulaire
    form.addEventListener('submit', function(e) {
        const method = methodSelect.value;
        const mobileMethods = @json(config('payment.mobile_money'));
        
        if (mobileMethods.includes(method)) {
            const phone = document.getElementById('phone').value;
            if (!validatePhone(phone)) {
                e.preventDefault();
                alert('Veuillez entrer un numéro de téléphone valide (ex: +243XXXXXXXXX)');
                return;
            }
        } else if (method === 'carte_bancaire') {
            const cardNumber = document.getElementById('cardNumber').value;
            const expiryDate = document.getElementById('expiryDate').value;
            const cvv = document.getElementById('cvv').value;
            
            if (!validateCardNumber(cardNumber)) {
                e.preventDefault();
                alert('Veuillez entrer un numéro de carte valide (12-19 chiffres)');
                return;
            }
            
            if (!validateExpiryDate(expiryDate)) {
                e.preventDefault();
                alert('Veuillez entrer une date d\'expiration valide (MM/YY)');
                return;
            }
            
            if (!validateCVV(cvv)) {
                e.preventDefault();
                alert('Veuillez entrer un CVV valide (3-4 chiffres)');
                return;
            }
        }
    });

    // Formatage automatique du numéro de carte
    document.getElementById('cardNumber').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });

    // Formatage automatique de la date d'expiration
    document.getElementById('expiryDate').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    methodSelect.addEventListener('change', toggle);
    toggle();
});
</script>
@endsection
