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
        background:transparent;
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
  .university-logo {
        max-height: 90px;
        width: auto;
        margin-bottom: 1rem;
    }

    .university-info {
        margin-bottom: 1.1rem;
    }

    .university-name {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1b2a40;
        margin-bottom: 0.25rem;
        letter-spacing: 0.4px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .system-name {
        font-size: 1rem;
        color: #5a6a7a;
        font-weight: 600;
        letter-spacing: 0.4px;
        text-shadow: 0 1px 1px rgba(0, 0, 0, 0.07);
    }

    .auth-icon {
        margin: 1.8rem 0 1rem;
        color: #2874d4;
        font-size: 2.2rem;
        transition: color 0.3s ease;
        filter: drop-shadow(0 0 1px rgba(0,0,0,0.1));
    }

    .auth-icon:hover {
        color: #1b4fa0;
    }

    .social-login-wrapper {
        margin-top: 1.2rem;
    }

   .google-login-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 0.85rem 1.25rem;
    background: #007bff; /* Solid blue */
    color: #fff;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 14px rgba(0, 123, 255, 0.4);
    border: none;
    letter-spacing: 0.6px;
}

.google-login-btn:hover {
    background: #0069d9; /* Darker blue on hover */
    transform: scale(1.03);
    box-shadow: 0 6px 22px rgba(0, 123, 255, 0.55);
}

.google-login-btn:active {
    transform: scale(0.98);
}


.google-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 5px;
        padding: 5px;
        margin-right: 10px;
        width: 28px;
        height: 28px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);
    }

    .google-icon svg {
        width: 18px;
        height: 18px;
    }

    .btn-text {
        letter-spacing: 0.45px;
    }

    /* Mobile responsiveness */
    @media (max-width: 576px) {
        .login-container {
            padding: 2rem 1.25rem;
        }

        .university-name {
            font-size: 1.5rem;
        }

        .system-name {
            font-size: 0.95rem;
        }

        .auth-icon {
            font-size: 1.8rem;
        }

        .google-login-btn {
            font-size: 0.95rem;
        }
    }

</style>
@endpush

@section('content')
    <!-- BEGIN login -->
    <div class="login login-v2 fw-bold">
        <!-- BEGIN login-cover -->
        <div class="login-cover">
            <div class="login-cover-img" style="background-color: rgb(12, 66, 111);"></div>
           {{-- <div class="login-cover-bg"></div>--}}
        </div>
        <!-- END login-cover -->




        <!-- BEGIN login-container -->
        @php
            $year = date('Y');
        @endphp

        <div class="d-flex justify-content-center align-items-center vh-100" 
     style="background: #f8f6be">
     
            <div class="card shadow-lg rounded-4 border-0 p-4 px-4 py-5 animate__animated animate__fadeIn"
                 style="max-width: 420px; width: 100%; 
                        background: #f8f6be;
                        backdrop-filter: blur(12px); 
                        border: 1px solid rgba(255, 223, 0, 0.6);
                        box-shadow: 0 8px 25px rgba(218, 165, 32, 0.6);
                        color: #3b2f0c;">

                          <!-- 🔴 Error Messages at the Top -->
                    @if ($errors->has('google_login'))
                        <div class="alert alert-danger text-center mb-3">
                            {{ $errors->first('google_login') }}
                        </div>
                    @endif

                    @if ($errors->has('google'))
                        <div class="alert alert-danger text-center mb-3">
                            {{ $errors->first('google') }}
                        </div>
                    @endif
                    <!-- 🔴 End Errors -->


                                
                <!-- BEGIN login-header -->
                <div class="text-center mb-4">
                    <img src="{{ asset('images/icon_ku_bg.png') }}" alt="University Logo" class="img-fluid mb-3" style="max-height: 70px;">
                    <h4 class="fw-bold mb-0 text-dark">Kenyatta University</h4>
                    <p class="text-muted small">Accommodation Services: Occurrence Booking System</p>
                </div>
                <!-- END login-header -->

                <!-- BEGIN login-content -->
                <div class="login-content">
                  







                <div class="mb-3">
                        <a href="{{ route('login.google') }}"
                           class="btn btn-primary w-100 btn-lg d-flex align-items-center justify-content-center gap-2 shadow-sm"
                           style="transition: all 0.2s ease-in-out;">

                           <span style=" display: flex;
                            align-items: center;
                            justify-content: center;
                            background: #fff;
                            border-radius: 5px;
                            padding: 5px;
                            margin-right: 10px;
                            width: 28px;
                            height: 28px;
                            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 533.5 544.3" width="18" height="18">
                                    <path fill="#4285F4" d="M533.5 278.4c0-17.4-1.6-34.1-4.7-50.4H272v95.3h147.5c-6.4 34.6-25.7 63.9-54.8 83.5l88.4 68.6c51.6-47.6 80.4-117.9 80.4-197z"></path>
                                    <path fill="#34A853" d="M272 544.3c73.7 0 135.6-24.4 180.8-66.2l-88.4-68.6c-24.5 16.4-55.7 26-92.4 26-71.1 0-131.4-47.9-152.9-112.1l-91.2 70.3c45.8 90.8 139.4 150.6 244.1 150.6z"></path>
                                    <path fill="#FBBC05" d="M119.1 323.4c-10.9-32.6-10.9-67.8 0-100.4l-91.2-70.3c-40.2 79.6-40.2 172 0 251.6l91.2-70.9z"></path>
                                    <path fill="#EA4335" d="M272 107.7c39.9 0 75.7 13.8 103.9 40.8l77.7-77.7C407.6 24.4 345.7 0 272 0 167.3 0 73.7 59.8 27.9 150.6l91.2 70.3C140.6 155.6 200.9 107.7 272 107.7z"></path>
                                </svg>
                            </span>
                         
                                


                            </i> <span>Continue with Google</span>
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