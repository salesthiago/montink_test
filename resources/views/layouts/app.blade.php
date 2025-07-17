<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Pedidos - Montink</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --dark-color: #5a5c69;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
        }

        .navbar-montink {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .navbar-brand {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            font-weight: 600;
        }

        .container-main {
            margin-top: 2rem;
            background-color: white;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            padding: 2rem;
        }

        .alert {
            border-left: 4px solid;
        }

        .alert-success {
            border-left-color: var(--secondary-color);
        }

        .alert-danger {
            border-left-color: #e74a3b;
        }

        /* Dark mode toggle */
        .theme-toggle {
            cursor: pointer;
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .theme-toggle:hover {
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .navbar-collapse {
                padding-top: 1rem;
            }

            .nav-link {
                margin: 0.25rem 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar Moderna -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-montink sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('products.index') }}">
                <i class="fas fa-store-alt me-2"></i>
                <span>Montink</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="fas fa-boxes me-1"></i> Produtos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                            <i class="fas fa-clipboard-list me-1"></i> Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('coupons.*') ? 'active' : '' }}" href="{{ route('coupons.index') }}">
                            <i class="fas fa-tags me-1"></i> Cupons
                        </a>
                    </li>
                </ul>


            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <main class="container container-main my-4">
        <!-- Mensagens Flash -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Rodapé -->
    <footer class="bg-light py-4 mt-5">
        <div class="container text-center text-muted">
            <p class="mb-0">&copy; {{ date('Y') }} Montink - Sistema de Pedidos. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Scripts Customizados -->
    <script>
        // Tema dark/light
        document.getElementById('themeToggle').addEventListener('click', function() {
            const html = document.documentElement;
            const icon = this.querySelector('i');

            if (html.getAttribute('data-bs-theme') === 'dark') {
                html.setAttribute('data-bs-theme', 'light');
                icon.classList.replace('fa-sun', 'fa-moon');
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-bs-theme', 'dark');
                icon.classList.replace('fa-moon', 'fa-sun');
                localStorage.setItem('theme', 'dark');
            }
        });

        // Verificar preferência de tema salva
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            document.getElementById('themeToggle').querySelector('i').classList.replace('fa-moon', 'fa-sun');
        }

        // Tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
