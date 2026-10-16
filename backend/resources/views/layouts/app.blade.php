<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Business Connect â€“ Business Management & Networking Platform')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/css/custom.css" rel="stylesheet">
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top navbar-custom py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-briefcase-fill fs-5"></i>
                </div>
                <span class="brand-font fs-4 text-primary">Business<span class="text-secondary">Connect</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Search Autosuggest Input -->
                <div class="mx-auto my-2 my-lg-0 position-relative style-search" style="max-width: 420px; width: 100%;">
                    <form action="{{ route('businesses.index') }}" method="GET">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="global-search-input" name="q" class="form-control border-start-0 ps-0" placeholder="Search businesses, products, services..." autocomplete="off">
                            <button class="btn btn-primary px-3" type="submit">Search</button>
                        </div>
                    </form>
                    <div id="search-suggestions-dropdown" class="position-absolute w-100 mt-1 glass-card d-none z-3 overflow-hidden"></div>
                </div>

                <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold">
                    <li class="nav-item"><a class="nav-link" href="{{ route('businesses.index') }}"><i class="bi bi-shop me-1"></i> Businesses</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}"><i class="bi bi-box-seam me-1"></i> Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('services.index') }}"><i class="bi bi-tools me-1"></i> Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}"><i class="bi bi-envelope me-1"></i> Contact</a></li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <button id="theme-toggle-btn" class="btn btn-outline-secondary border-0 btn-sm me-2">
                        <i class="bi bi-moon-stars-fill text-primary"></i> Dark Mode
                    </button>

                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2">
                                @if(Auth::user()->isAdmin())
                                    <li><a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2 text-primary"></i> Admin Dashboard</a></li>
                                @elseif(Auth::user()->isBusinessOwner())
                                    <li><a class="dropdown-item py-2" href="{{ route('business.dashboard') }}"><i class="bi bi-kanban me-2 text-primary"></i> Business Owner Dashboard</a></li>
                                @else
                                    <li><a class="dropdown-item py-2" href="{{ route('customer.dashboard') }}"><i class="bi bi-person-workspace me-2 text-primary"></i> Customer Portal</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item py-2 text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">Sign In</a>
                        <div class="dropdown">
                            <button class="btn btn-primary rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown">Register</button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 mt-2">
                                <li><a class="dropdown-item py-2" href="{{ route('register.business') }}"><i class="bi bi-building me-2 text-primary"></i> As Business Owner</a></li>
                                <li><a class="dropdown-item py-2" href="{{ route('register.customer') }}"><i class="bi bi-person me-2 text-secondary"></i> As Customer</a></li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Session Notifications -->
    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="container mt-3">
            <div class="alert alert-info alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="container mt-3">
            <div class="alert alert-warning alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center mb-1">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <strong>Please correct the following errors:</strong>
                </div>
                <ul class="mb-0 ps-4 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="brand-font text-white mb-3"><i class="bi bi-briefcase-fill me-2 text-primary"></i>Business Connect</h5>
                    <p class="text-muted small">Promote your business, showcase high-quality products and services, and connect seamlessly with customers worldwide on an all-in-one verified platform.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-uppercase fw-bold text-light mb-3">Navigation</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="{{ route('businesses.index') }}" class="text-muted text-decoration-none">Browse Businesses</a></li>
                        <li class="mb-2"><a href="{{ route('products.index') }}" class="text-muted text-decoration-none">Products Directory</a></li>
                        <li class="mb-2"><a href="{{ route('services.index') }}" class="text-muted text-decoration-none">Services Directory</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase fw-bold text-light mb-3">User Portals</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('register.business') }}" class="text-muted text-decoration-none">List Your Business</a></li>
                        <li class="mb-2"><a href="{{ route('register.customer') }}" class="text-muted text-decoration-none">Create Customer Account</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}" class="text-muted text-decoration-none">User Login</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase fw-bold text-light mb-3">Contact Support</h6>
                    <p class="text-muted small mb-1"><i class="bi bi-geo-alt me-2"></i> Silicon Tech Tower, Suite 400</p>
                    <p class="text-muted small mb-1"><i class="bi bi-envelope me-2"></i> support@businessconnect.com</p>
                    <p class="text-muted small"><i class="bi bi-telephone me-2"></i> +1 (800) 555-0199</p>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-muted small">
                <div>&copy; {{ date('Y') }} Business Connect. All Rights Reserved.</div>
                <div>Designed for Academic Presentation & Production Readiness</div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>
    <!-- Theme & Custom JS -->
    <script src="/js/theme.js"></script>
    <script src="/js/charts.js"></script>
    <script src="/js/app.js"></script>
</body>
</html>

