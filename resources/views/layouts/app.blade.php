<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{ $setting->pharmacy_name ?? 'Hypet Pharmacy' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-light">
    <!-- Preloader -->

<div id="preloader">
    <div class="preloader-content">
            @if($setting && $setting->logo)

                    <img src="{{ asset('storage/' . $setting->logo) }}"
                        width="45"
                        height="45"
                        class="me-2 rounded-circle">

                @else

                    <img src="{{ asset('images/pharm_logo.png') }}"
                        width="45"
                        height="45"
                        class="me-2 rounded-circle">

            @endif

        <h3 class="preloader-title">
            {{ $setting->pharmacy_name ?? 'Hypet Pharmacy' }}
        </h3>

        <p class="preloader-text">
            Loading Pharmacy System...
        </p>

        <div class="loading-bar">
            <div class="loading-progress"></div>
        </div>

    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-dark shadow sticky-top"
     style="background:linear-gradient(90deg,#0d6efd,#0b5ed7);">

    <div class="container-fluid px-4">

        {{-- ================= LOGO ================= --}}

       `<a class="navbar-brand d-flex align-items-center fw-bold"
            href="#">

                @if($setting && $setting->logo)

                    <img src="{{ asset('storage/' . $setting->logo) }}"
                        width="45"
                        height="45"
                        class="me-2 rounded-circle">

                @else

                    <img src="{{ asset('images/pharm_logo.png') }}"
                        width="45"
                        height="45"
                        class="me-2 rounded-circle">

                @endif

                <div>

                    <div class="fw-bold">

                        {{ $setting->pharmacy_name ?? 'Hypet Pharmacy' }}

                    </div>

                    <small style="font-size:11px">

                        Inventory Management System

                    </small>

                </div>

        </a>

        <button class="navbar-toggler"
                data-bs-toggle="collapse"
                data-bs-target="#navbar">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse"
             id="navbar">

            {{-- ================= LEFT MENU ================= --}}

            <ul class="navbar-nav ms-5">

                {{-- ========================================= --}}
                {{-- Dashboard (Coming Soon) --}}
                {{-- ========================================= --}}

                
               <li class="nav-item">

                    @if(auth()->user()->role == 'admin')

                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">

                            <i class="fas fa-chart-line me-1"></i>
                            Dashboard

                        </a>

                    @elseif(auth()->user()->role == 'cashier')

                        <a class="nav-link {{ request()->routeIs('cashier.dashboard') ? 'active' : '' }}"
                        href="{{ route('cashier.dashboard') }}">

                            <i class="fas fa-chart-line me-1"></i>
                            Dashboard

                        </a>

                    @elseif(auth()->user()->role == 'pharmacist')

                        <a class="nav-link {{ request()->routeIs('pharmacist.dashboard') ? 'active' : '' }}"
                        href="{{ route('pharmacist.dashboard') }}">

                            <i class="fas fa-chart-line me-1"></i>
                            Dashboard

                        </a>

                    @elseif(auth()->user()->role == 'storekeeper')

                        <a class="nav-link {{ request()->routeIs('storekeeper.dashboard') ? 'active' : '' }}"
                        href="{{ route('storekeeper.dashboard') }}">

                            <i class="fas fa-chart-line me-1"></i>
                            Dashboard

                        </a>

                    @endif

                </li>
               

                {{-- ========================================= --}}
                {{-- MASTER DATA --}}
                {{-- ========================================= --}}
                @if(in_array(auth()->user()->role, ['admin','pharmacist']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                    href="{{ route('categories.index') }}">
                        <i class="fas fa-layer-group me-1"></i>
                        Categories
                    </a>
                </li>
                @endif

               @if(in_array(auth()->user()->role, ['admin','pharmacist','storekeeper']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('medicine.*') || request()->routeIs('medicines.*') ? 'active' : '' }}"
                    href="{{ route('medicines.index') }}">
                        <i class="fas fa-pills me-1"></i>
                        Medicines
                    </a>
                </li>
                @endif

                @if(in_array(auth()->user()->role, ['admin','pharmacist','storekeeper']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}"
                    href="{{ route('inventory.index') }}">
                        <i class="fas fa-boxes-stacked me-1"></i>
                        Inventory
                    </a>
                </li>
                @endif

                {{-- ========================================= --}}
                {{-- PROCUREMENT --}}
                {{-- ========================================= --}}

                @if(in_array(auth()->user()->role, ['admin','storekeeper']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
                    href="{{ route('suppliers.index') }}">
                        <i class="fas fa-truck me-1"></i>
                        Suppliers
                    </a>
                </li>
                @endif

                @if(in_array(auth()->user()->role, ['admin','storekeeper']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('purchase.*') ? 'active' : '' }}"
                    href="{{ route('purchase.index') }}">
                        <i class="fas fa-cart-plus me-1"></i>
                        Purchases
                    </a>
                </li>
                @endif

                {{-- ========================================= --}}
                {{-- SALES --}}
                {{-- ========================================= --}}

                @if(in_array(auth()->user()->role, ['admin','pharmacist','cashier']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"
                    href="{{ route('sales.index') }}">
                        <i class="fas fa-cash-register me-1"></i>
                        Sales
                    </a>
                </li>
                @endif

                {{-- ========================================= --}}
                {{-- CUSTOMER MANAGEMENT --}}
                {{-- ========================================= --}}

               @if(in_array(auth()->user()->role, ['admin','pharmacist','cashier']))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"
                    href="{{ route('customers.index') }}">
                        <i class="fas fa-users me-1"></i>
                        Customers
                    </a>
                </li>
                @endif

            </ul>

            {{-- ================= RIGHT MENU ================= --}}
            <ul class="navbar-nav ms-auto">

                {{-- Notifications --}}
                <li class="nav-item me-3">

                    <a class="nav-link position-relative" href="#">

                        <i class="fas fa-bell fa-lg"></i>

                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>

                    </a>

                </li>

                {{-- Administrator --}}
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle"
                    href="#"
                    data-bs-toggle="dropdown">

                        <i class="fas fa-user-circle me-1"></i>

                        {{ auth()->user()->name ?? 'Administrator' }}

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow">

                        <li>

                            <a class="dropdown-item"
                            href="{{ route('profile.edit') }}">

                                <i class="fas fa-user me-2"></i>

                                Profile

                            </a>

                        </li>

                        @if(auth()->user()->role == 'admin')

                        <li>

                            <a class="dropdown-item"
                            href="{{ route('users.index') }}">

                                <i class="fas fa-users me-2"></i>

                                User Management

                            </a>

                        </li>

                        @endif

                        @if(auth()->user()->role == 'admin')

                        <li>

                            <a class="dropdown-item {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                            href="{{ route('reports.index') }}">

                                <i class="fas fa-chart-bar me-1"></i>

                                Reports

                            </a>

                        </li>

                        @endif

                       @if(auth()->user()->role == 'admin')
                        <li >
                            <a href="{{ route('settings.index') }}" class="dropdown-item">
                                <i class="fas fa-cogs"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                        @endif

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button type="submit"
                                        class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">

                                    <i class="fas fa-sign-out-alt me-2"></i>

                                    Logout

                                </button>

                            </form>

                        </li>

                    </ul>

                </li>

            </ul>

        </div>

    </div>

</nav>
<div class="container-fluid py-4 px-4">

    {{-- ========================= --}}
    {{-- SUCCESS MESSAGE --}}
    {{-- ========================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show shadow-sm">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    {{-- ========================= --}}
    {{-- ERROR MESSAGE --}}
    {{-- ========================= --}}

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show shadow-sm">

            <i class="fas fa-times-circle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    {{-- ========================= --}}
    {{-- VALIDATION ERRORS --}}
    {{-- ========================= --}}

    @if($errors->any())

        <div class="alert alert-danger shadow-sm">

            <h6 class="fw-bold mb-2">

                <i class="fas fa-circle-exclamation me-2"></i>

                Please correct the following:

            </h6>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- ========================= --}}
    {{-- PAGE CONTENT --}}
    {{-- ========================= --}}

    @yield('content')

</div>

{{-- ========================================= --}}
{{-- GLOBAL DELETE CONFIRMATION MODAL --}}
{{-- ========================================= --}}

<div class="modal fade"
     id="deleteModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header bg-danger text-white">

                <h5 class="modal-title">

                    <i class="fas fa-trash-alt me-2"></i>

                    Confirm Deletion

                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body text-center py-4">

                <i class="fas fa-exclamation-triangle text-warning fa-4x mb-3"></i>

                <h5 class="fw-bold">

                    Delete this record?

                </h5>

                <p class="text-muted mb-0">

                    This action cannot be undone.

                </p>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
                    type="button"
                    class="btn btn-danger"
                    id="confirmDelete">

                    <i class="fas fa-trash me-1"></i>

                    Delete

                </button>

            </div>

        </div>

    </div>

</div>



<!-- ========================= -->
<!-- Bootstrap JS -->
<!-- ========================= -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- ========================= -->
<!-- AUTO DISMISS ALERTS -->
<!-- ========================= -->

<script>
    document.addEventListener("DOMContentLoaded", function () {
        
        const alerts = document.querySelectorAll(".alert");
        
        alerts.forEach(function (alert) {
            
            setTimeout(function () {
                
                if (bootstrap.Alert.getOrCreateInstance(alert)) {
                    
                    bootstrap.Alert.getOrCreateInstance(alert).close();

                }

            }, 4000);
            
        });
        
    });
</script>

<!-- ========================= -->
<!-- GLOBAL DELETE MODAL -->
<!-- ========================= -->

    <script>
        const successMessage = @json(session('success'));
        const errorMessage = @json(session('error'));

        const validationErrors = @json($errors->all());

        window.addEventListener("load", function () {

            const preloader = document.getElementById("preloader");

            setTimeout(function () {

                preloader.classList.add("hide");

                setTimeout(function () {

                    preloader.remove();

                    // SUCCESS
                    if(successMessage){

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: successMessage,
                            timer: 2500,
                            showConfirmButton: false
                        });

                    }

                    // ERROR
                    if(errorMessage){

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonColor: '#0d6efd'
                        });

                    }

                    // VALIDATION
                    if(validationErrors.length){

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html:
                                "<ul style='text-align:left'>" +
                                validationErrors.map(e => `<li>${e}</li>`).join("") +
                                "</ul>",
                            confirmButtonColor: '#0d6efd'
                        });

                    }

                },500);

            },1500);

        });
            

    </script>

<!-- ========================= -->
<!-- OPTIONAL ACTIVE MENU -->
<!-- ========================= -->

<script>
    
    document.addEventListener("DOMContentLoaded", function () {
        
        document.querySelectorAll(".navbar-nav .nav-link").forEach(function (link) {
            
            if (link.classList.contains("active")) {
                
                link.style.fontWeight = "600";
                
            }

    });
    
});



</script>

<script>
window.addEventListener("load", function () {

    const preloader = document.getElementById("preloader");

    // Keep the loader visible for at least 1 second
    setTimeout(function () {

        preloader.classList.add("hide");

        setTimeout(function () {
            preloader.remove();
        }, 500);

    }, 1500);

});
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@stack('scripts')

@yield('scripts')
</body>

</html>