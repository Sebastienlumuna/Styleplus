  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <span class="fw-bold">STYLEPLUS</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-filter="femme">Femme</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-filter="homme">Homme</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-filter="enfant">Enfant</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.html"><i class="bi bi-cart3"></i> Panier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.html"><i class="bi bi-person"></i> Connexion</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
