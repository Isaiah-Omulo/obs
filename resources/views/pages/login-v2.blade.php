@extends('layouts.default', [
    'paceTop' => true,
    'appSidebarHide' => true,
    'appHeaderHide' => true,
    'appContentClass' => 'p-0',
])

@section('title', 'Login')

@push('styles')
<style>
    /* -------------------------------
       Base & Background Setup
       ------------------------------- */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden; /* Prevents scrollbars from appearing */
    }

    .login {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        width: 100%;
    }

    /* Full-screen background image */
    .login-cover-img {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* Dark overlay for better text readability */
    .login-cover-bg {
        background: linear-gradient(45deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.4));
    }

    /* -------------------------------
       Modern Login Container
       ------------------------------- */
    .login-container {
        width: 100%;
        max-width: 420px; /* Optimal width for the login form */
        padding: 2.5rem;
        z-index: 10;
        
        /* Frosted Glass Effect */
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px); /* For Safari */
        
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
    }

    /* -------------------------------
       Header & Brand Styling
       ------------------------------- */
    .login-header .brand {
        color: #fff;
        text-align: center;
        margin-bottom: 2rem;
    }

    .login-header .brand img {
        width: 60px; /* Slightly larger logo */
        height: 60px;
        margin-bottom: 0.5rem;
    }

    .login-header .brand .brand-title {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    
    .login-header .brand .brand-subtitle {
        font-size: 1rem;
        font-weight: 300;
        color: rgba(255, 255, 255, 0.8);
    }

    /* Remove the default lock icon for a cleaner look */
    .login-header .icon {
        display: none;
    }

    /* -------------------------------
       Content & Button Styling
       ------------------------------- */
    .login-content .btn-danger {
        transition: all 0.3s ease;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        font-weight: 600;
    }

    .login-content .btn-danger:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }

    .login-content .text-gray-500 {
        color: rgba(255, 255, 255, 0.7) !important;
        font-size: 0.875rem;
        margin-top: 1.5rem;
    }
    
    .alert-danger {
        background-color: #dc3545;
        color: white;
        border: none;
    }

    /* -------------------------------
       Mobile Responsiveness
       ------------------------------- */
    @media (max-width: 576px) {
        .login-container {
            margin: 1rem;
            padding: 1.5rem;
        }

        .login-header .brand .brand-title {
            font-size: 1.25rem;
        }

        .login-header .brand .brand-subtitle {
            font-size: 0.9rem;
        }
    }
</style>
@endpush

@section('content')
    <!-- BEGIN login -->
    <div class="login login-v2 fw-bold">
        <!-- BEGIN login-cover -->
        <div class="login-cover">
            <div class="login-cover-img" style="background-image: url('{{ asset('images/entrance.jpg') }}')"></div>
            <div class="login-cover-bg"></div>
        </div>
        <!-- END login-cover -->

         @if ($errors->has('google_login'))
            <div class="alert alert-danger">
                {{ $errors->first('google_login') }}
            </div>
        @endif




        <!-- BEGIN login-container -->
        @php
            $year = date('Y');
        @endphp

        <div class="d-flex justify-content-center align-items-center vh-100" style="background: linear-gradient(135deg, #f8f9fa, #e0e7ff);">
            <div class="card shadow-lg rounded-4 border-0 p-4 px-4 py-5 animate__animated animate__fadeIn" style="max-width: 420px; width: 100%; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                
                <!-- BEGIN login-header -->
                <div class="text-center mb-4">
                    <img src="{{ asset('images/icon_ku_bg.png') }}" alt="University Logo" class="img-fluid mb-3" style="max-height: 70px;">
                    <h4 class="fw-bold mb-0 text-dark">Kenyatta University</h4>
                    <p class="text-muted small">Accommodation Services Occurrence Booking System</p>
                </div>
                <!-- END login-header -->

                <!-- BEGIN login-content -->
                <div class="login-content">
                    @if ($errors->has('google'))
                        <div class="alert alert-danger text-center">
                            {{ $errors->first('google') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <a href="{{ route('login.google') }}"
                           class="btn btn-danger w-100 btn-lg d-flex align-items-center justify-content-center gap-2 shadow-sm"
                           style="transition: all 0.2s ease-in-out;">
                            <i class="fab fa-google fa-lg"></i> <span>Continue with Google</span>
                        </a>
                    </div>

                    <div class="text-center text-muted small">
                        You will be redirected to the Google login page.
                    </div>


                    
                </div>
                <!-- END login-content -->

                <!-- BEGIN footer -->
                <div class="mt-4 pt-3 border-top text-center text-muted small">
                    <p class="mb-0">&copy; <span id="year">{{ $year }}</span> Kenyatta University. All rights reserved.</p>
                </div>
                <!-- END footer -->
            </div>
        </div>


        <!-- END login-container -->
    </div>
    <!-- END login -->
@endsection