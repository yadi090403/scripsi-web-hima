@extends('layouts.app')

@section('title', 'Login')

@section('content')

<style>
/* === MULTI-SLIDE SCROLLING BACKGROUND === */
.login-bg {
    min-height: 100vh;
    position: relative;
    overflow: hidden;
}

.bg-slider {
    position: absolute;
    inset: 0;
    z-index: 0;
}

.bg-slide {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: 0% 50%;
    opacity: 0;
    will-change: opacity, background-position;
    filter: brightness(1.08);
    animation: pan 40s linear infinite, slideShow 36s linear infinite;
}

.bg-slide:nth-child(1) { background-image: url('/images/library11-bg.jpg'); animation-delay: 0s, 0s; }
.bg-slide:nth-child(2) { background-image: url('/images/library12-bg.jpg'); animation-delay: 0s, 12s; }
.bg-slide:nth-child(3) { background-image: url('/images/library13-bg.jpg'); animation-delay: 0s, 24s; }

@keyframes pan {
    from { background-position: 0% 50%; }
    to   { background-position: 100% 50%; }
}

@keyframes slideShow {
    0%   { opacity: 0; }
    8%   { opacity: 1; }
    33%  { opacity: 1; }
    41%  { opacity: 0; }
    100% { opacity: 0; }
}

/* Overlay */
.bg-overlay {
    background: linear-gradient(to bottom right, rgba(0, 0, 0, 0.4), rgba(0,0,0,0.6));
    backdrop-filter: blur(2px);
}

/* === GLASS LOGIN CARD === */
.login-card {
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    box-shadow:
        0 8px 32px rgba(0, 0, 0, 0.35),
        inset 0 0 0 1px rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    transition: all 0.4s ease;
}

.login-card:hover {
    transform: translateY(-6px);
    box-shadow:
        0 25px 50px rgba(0, 0, 0, 0.45),
        0 0 45px rgba(139, 92, 246, 0.35);
}

/* Title */
.login-card h2 {
    color: white;
    letter-spacing: 2px;
}

/* Inputs */
.login-card input {
    color: white;
    border-bottom: 1px solid rgba(255,255,255,0.4);
    transition: all 0.3s ease;
}

.login-card input:focus {
    border-bottom: 1px solid white;
    box-shadow: 0 6px 18px rgba(255,255,255,0.25);
}

.login-card input::placeholder {
    color: rgba(255,255,255,0.7);
}

/* Icons */
.login-card i {
    transition: all 0.3s ease;
}

.login-card i:hover {
    transform: scale(1.2);
    color: #8b5cf6;
}

/* Reset button */
.login-card button[type="reset"] {
    color: rgba(255,255,255,0.8);
    transition: 0.3s;
}

.login-card button[type="reset"]:hover {
    color: white;
}

/* Social text */
.login-card .social-text {
    color: rgba(255,255,255,0.8);
}
</style>

<div class="relative flex items-center justify-center overflow-hidden login-bg">

    <!-- BACKGROUND SLIDER -->
    <div class="bg-slider" aria-hidden="true">
        <div class="bg-slide"></div>
        <div class="bg-slide"></div>
        <div class="bg-slide"></div>
    </div>

    <!-- OVERLAY -->
    <div class="absolute inset-0 bg-overlay"></div>

    <!-- LOGIN CARD -->
    <div class="relative z-20 w-full max-w-lg p-12 text-white login-card">

        <div class="flex justify-center mb-6">
            <i class="text-5xl text-white fas fa-book-reader"></i>
        </div>

        <h2 class="mb-8 text-2xl font-semibold text-center">
            LOGIN ADMIN
        </h2>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            @if($errors->any())
                <div class="p-3 text-sm text-red-200 rounded-lg bg-red-500/30">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center text-white">
                    <i class="fas fa-user"></i>
                </span>
                <input
                    type="email"
                    name="email"
                    required
                    class="w-full py-2 pl-8 placeholder-white bg-transparent border-b focus:outline-none"
                    placeholder="Username or Email">
            </div>

            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center text-white">
                    <i class="fas fa-lock"></i>
                </span>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full py-2 pl-8 placeholder-white bg-transparent border-b focus:outline-none"
                    placeholder="Password">
            </div>

            <button
                type="submit"
                class="w-full py-3 mt-4 font-semibold text-white transition rounded-xl bg-gradient-to-r from-purple-600 to-blue-600 hover:opacity-90">
                Login
            </button>

            <button
                type="reset"
                class="w-full py-2 text-sm">
                Reset
            </button>

            <div class="text-sm text-center social-text">
                Or Sign in with social platforms
            </div>

            <div class="flex justify-center space-x-6 text-white">
                <i class="fab fa-facebook-f"></i>
                <i class="fab fa-twitter"></i>
                <i class="fab fa-google"></i>
                <i class="fab fa-linkedin-in"></i>
            </div>
        </form>
    </div>
</div>

@endsection
