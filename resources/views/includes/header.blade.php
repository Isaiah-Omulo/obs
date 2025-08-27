@php
    $appHeaderAttr = !empty($appHeaderInverse) ? ' data-bs-theme=dark' : '';
    $appHeaderMenu = $appHeaderMenu ?? '';
    $appHeaderMegaMenu = $appHeaderMegaMenu ?? '';
    $appHeaderTopMenu = $appHeaderTopMenu ?? '';
 
 
use Illuminate\Support\Facades\Auth;

$full_name = "";

if (Auth::check()) {
    // User is authenticated
    $user = Auth::user();
    // If full_name exists and is not null, assign it, else keep empty string
    $full_name = $user->name ?? '';
}

@endphp


@push('styles')

<style>

/* Header styling */
.app-header {
    background-color: #f8f5f5;
    padding: 0.5rem 0;
}

.navbar-brand img {
    max-height: 36px;
}

/* Center title responsive */
.app-header h1 {
    font-size: 1rem; /* smaller on mobile */
    text-align: center;
    line-height: 1.2;
}

/* Hide center title on mobile */
/* Mobile compact title */
@media (max-width: 767px) {
    .sticky-header h1 {
        font-size: 1rem; /* smaller font for mobile */
        line-height: 1.2rem;
        margin: 0;
    }
}


/* Mobile hamburger button */
.btn[data-toggle="app-sidebar-mobile"] {
    background: transparent;
    border: none;
    font-size: 1.2rem;
    color: #333;
}

/* User dropdown adjustments */
.navbar-user .navbar-link {
    font-size: 0.9rem;
    padding: 0.25rem 0.5rem;
}

/* Sticky header */
.sticky-header {
    position: sticky;
    top: 0;
    z-index: 1030; /* above content */
    transition: box-shadow 0.3s ease, background-color 0.3s ease;
}

/* Shadow when scrolling */
.sticky-header.scrolled {
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    background-color: #f8f5f5; /* optional slightly darker */
}

/* Mobile tweaks */
@media (max-width: 767px) {
    .sticky-header .navbar-brand span {
        display: none;
    }
}

/* Desktop / tablet */
.app-title-desktop h1 {
    font-size: 1.0rem; /* bigger than fs-6 */
    line-height: 1.2;
}

/* Mobile */
.app-title-mobile h1 {
    font-size: 0.8rem; /* slightly smaller for mobile */
    line-height: 1.2;
}

/* Optional: make it scale nicely for really large screens */
@media (min-width: 1400px) {
    .app-title-desktop h1 {
        font-size: 2rem;
    }
}


 </style>   
@endpush


<header id="header" class="app-header shadow-sm sticky-header" {{ $appHeaderAttr }}>
    <div class="container-fluid d-flex align-items-center justify-content-between px-3 py-2">
        <!-- LEFT: Brand + Mobile Toggle -->
        <div class="d-flex align-items-center gap-2">
            @if (!$appSidebarHide)
                <button class="btn btn-sm d-md-none" type="button" data-toggle="app-sidebar-mobile">
                    <i class="fa fa-bars"></i>
                </button>
            @endif

            <a href="/" class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark">
                <img src="{{ asset('images/icon_ku.png') }}" alt="University Logo" height="36" />
                <span class="d-none d-md-inline"><strong>Kenyatta University</strong></span>
            </a>
        </div>

        <!-- CENTER: Title -->
       
        <!-- DESKTOP / TABLET TITLE -->
        <div class="d-none d-md-block text-center flex-grow-1 app-title-desktop">
            <h1 class="fw-semibold text-primary-emphasis mb-0">
                Accommodation Services: 
                <span class="text-secondary fw-bold">Occurrence Booking System</span>
            </h1>
        </div>

        <!-- MOBILE TITLE -->
        <div class="d-block d-md-none text-center flex-grow-1 app-title-mobile">
            <h1 class="fw-semibold text-primary-emphasis mb-0">
                Occurrence Booking System
            </h1>
        </div>

        <!-- RIGHT: User & Optional Icons -->
        <div class="d-flex align-items-center gap-2">
            @isset($appHeaderLanguageBar)
                @include('includes.component.header-language-bar')
            @endisset

            <!-- User Dropdown -->
            <div class="navbar-item navbar-user dropdown">
                <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <span class="d-none d-md-inline">{{ $full_name }}</span>
                    <b class="caret ms-1"></b>
                </a>
                @include('includes.component.header-dropdown-profile')
            </div>
        </div>
    </div>
</header>




@push('scripts')



<!-- 
<script>
document.addEventListener("click", function(e) {
  if (e.target.closest("[data-toggle='app-sidebar-mobile']")) {
    console.log("Toggle button clicked!");
      document.body.classList.toggle("app-sidebar-mobile-toggled");


       const sidebar = document.getElementById("sidebar");
    const backdrop = document.getElementById("sidebar-backdrop");
    sidebar.classList.toggle("active");
    backdrop.classList.toggle("active");
  }
});
</script>
-->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const header = document.getElementById('header');

    window.addEventListener('scroll', function() {
        if (window.scrollY > 5) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
});
</script>

@endpush