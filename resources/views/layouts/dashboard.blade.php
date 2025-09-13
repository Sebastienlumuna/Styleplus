<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - STYLEPLUS</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-dashboard {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #fff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            text-align: center;
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
            text-decoration: none;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-item {
            margin: 0.25rem 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #6c757d;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover {
            color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
            border-left-color: #667eea;
        }
        
        .nav-link.active {
            color: #667eea;
            background-color: rgba(102, 126, 234, 0.15);
            border-left-color: #667eea;
            font-weight: 600;
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }
        
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
        
        .content-header {
            background: #fff;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 2rem;
        }
        
        .content-body {
            padding: 0 2rem 2rem;
        }
        
        .footer-minimal {
            background: #fff;
            border-top: 1px solid #e9ecef;
            padding: 1rem 2rem;
            margin-top: auto;
        }
        
        .toggle-sidebar {
            background: none;
            border: none;
            color: #667eea;
            font-size: 1.2rem;
            cursor: pointer;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        .card-dashboard {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .card-dashboard:hover {
            transform: translateY(-2px);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('home') }}" class="sidebar-brand">
                <i class="bi bi-shop"></i>
                <span class="brand-text">STYLEPLUS</span>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            @yield('sidebar-nav')
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dashboard">
            <div class="container-fluid">
                <button class="toggle-sidebar me-3" id="toggle-sidebar">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="navbar-nav ms-auto">
                    <!-- Panier -->
                    <a class="nav-link text-white me-3" href="{{ route('cart') }}">
                        <i class="bi bi-cart3"></i>
                        <span class="badge bg-danger" id="cart-count">{{ $cartCount ?? 0 }}</span>
                    </a>
                    
                    <!-- Profil utilisateur -->
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar me-2">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="bi bi-person me-2"></i>Profil
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-muted mb-0">@yield('page-subtitle', 'Gérez votre compte')</p>
                </div>
                <div>
                    @yield('page-actions')
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="content-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="footer-minimal">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        © {{ date('Y') }} STYLEPLUS. Tous droits réservés.
                    </small>
                </div>
                <div>
                    <small class="text-muted">
                        Connecté en tant que {{ Auth::user()->name }}
                    </small>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });

        // Mobile sidebar toggle
        if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.add('collapsed');
        }
    </script>
    
    @yield('scripts')
</body>
</html>
