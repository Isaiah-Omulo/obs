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





<!-- BEGIN #header -->
<header id="header" class="app-header py-2 px-3 shadow-sm" {{ $appHeaderAttr }} style="background-color: #f8f5f5;">
    <div class="container-fluid">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            
            <!-- Navbar Brand and Mobile Togglers -->
            <div class="d-flex align-items-center gap-3">
                @if ($appSidebarTwo)
                    <button class="btn btn-sm d-md-none" type="button" data-toggle="app-sidebar-end-mobile">
                        <i class="fa fa-bars"></i>
                    </button>
                @endif

                <a href="/" class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-5 text-dark">
                    <img src="{{ asset('images/icon_ku.png') }}" alt="University Logo" height="36" />
                    <span><strong>Kenyatta University</strong></span>
                </a>

                @if ($appHeaderMegaMenu && !$appSidebarTwo)
                    <button class="btn btn-sm d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#top-navbar">
                        <i class="fa fa-cog"></i>
                    </button>
                @endif

                @if (($appTopMenu && !$appSidebarHide) || ($appTopMenu && $appSidebarHide && !$appSidebarTwo))
                    <button class="btn btn-sm d-md-none" type="button" data-toggle="app-top-menu-mobile">
                        <i class="fa fa-cog"></i>
                    </button>
                @endif

                @if (!$appSidebarHide)
                    <button class="btn btn-sm d-md-none" type="button" data-toggle="app-sidebar-mobile">
                        <i class="fa fa-bars"></i>
                    </button>
                @endif
            </div>

            <!-- Center Title -->
            <!-- Add class to trigger animation -->
            <div class="text-center flex-grow-1 title-animate">
                <h1 class="m-0 fs-4 text-primary">Occurrence Book System</h1>
            </div>


            <!-- Right-side Navbar Items -->
            <div class="d-flex align-items-center gap-3">
                <!-- Notifications -->
                <div class="dropdown">
                   <a href="#" id="notificationDropdownToggle" data-bs-toggle="dropdown" class="btn btn-info position-relative">

                        <i class="fa fa-bell"></i>
                        <span id="notification-count-total" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">7</span>
                    </a>
                    @include('includes.component.header-dropdown-notification')

                </div>

                <!-- Language Bar -->
                @isset($appHeaderLanguageBar)
                    @include('includes.component.header-language-bar')
                @endisset

                <!-- User Dropdown -->
                <div class="navbar-item navbar-user dropdown">
                    <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                       
                        <span>
                            <span class="d-none d-md-inline">{{ $full_name }}</span>
                            <b class="caret"></b>
                        </span>
                    </a>
                    @include('includes.component.header-dropdown-profile')
                </div>

                <!-- App Sidebar End -->
                @if ($appSidebarTwo)
                    <a href="javascript:;" data-toggle="app-sidebar-end" class="btn btn-light d-none d-md-inline-block">
                        <i class="fa fa-th"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>
<!-- END #header -->


@push('scripts')

@endpush