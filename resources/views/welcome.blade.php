<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HIMA NURJI') }} - @yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS (atau Anda bisa pakai Bootstrap) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --primary: #3b82f6;
            --secondary: #1e40af;
            --accent: #10b981;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white shadow-lg">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center space-x-4">
                    <img
                        src="{{ asset('images/logo.png') }}"
                        class="object-cover w-10 h-10 rounded-full">
                    <span class="text-xl font-bold text-gray-800">
                        HIMA NUJI
                    </span>
                </a>

                <!-- Desktop Navigation -->
                <div class="items-center hidden space-x-8 md:flex">
                    <a href="{{ url('/') }}" class="font-medium text-gray-700 hover:text-blue-600">Beranda</a>
                    <a href="#features" class="font-medium text-gray-700 hover:text-blue-600">Information</a>
                    <a href="#pricing" class="font-medium text-gray-700 hover:text-blue-600">Struktur Organisasi</a>
                    <a href="#contact" class="font-medium text-gray-700 hover:text-blue-600">Kontak</a>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-6 py-2 text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
                                Dashboard
                            </a>
                        @else
                            {{-- <a href="{{ route('login') }}" class="px-8 py-4 text-lg font-semibold text-white transition duration-300 bg-transparent border-2 border-white rounded-lg hover:bg-white hover:text-blue-600">Login</a> --}}
                            {{-- Hanya tampilkan jika route register ada --}}
                            @if (Route::has('register'))
                                @auth
                                    {{-- Sudah login, tidak tampilkan --}}
                                @else
                                    <a href="{{ route('login') }}" class="px-6 py-2 text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Daftar Gratis
                                    </a>
                                @endauth
                            @else
                                {{-- Jika register tidak ada, tampilkan login saja --}}
                                @auth
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-center text-white bg-blue-600 rounded-lg">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="px-6 py-2 text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Login
                                    </a>
                                @endauth
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700">
                        <i class="text-xl fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden bg-white border-t md:hidden">
            <div class="px-4 py-3 space-y-3">
                <a href="{{ url('/') }}" class="block text-gray-700 hover:text-blue-600">Beranda</a>
                <a href="#features" class="block text-gray-700 hover:text-blue-600">Informatiaon</a>
                <a href="#pricing" class="block text-gray-700 hover:text-blue-600">Struktur Organisasi</a>
                <a href="#contact" class="block text-gray-700 hover:text-blue-600">Kontak</a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-center text-white bg-blue-600 rounded-lg">
                            Dashboard
                        </a>
                    @else
                        {{-- <a href="{{ route('login') }}" class="px-8 py-4 text-lg font-semibold text-white transition duration-300 bg-transparent border-2 border-white rounded-lg hover:bg-white hover:text-blue-600">Masuk</a> --}}
                        {{-- Hanya tampilkan jika route register ada --}}
                        @if (Route::has('register'))
                            @auth
                                {{-- Sudah login, tidak tampilkan --}}
                            @else
                                <a href="{{ route('login') }}" class="px-4 py-2 text-center text-white bg-blue-600 rounded-lg">
                                    Daftar Gratis
                                </a>
                            @endauth
                        @else
                            {{-- Jika register tidak ada, tampilkan login saja --}}
                            @auth
                                <a href="{{ route('dashboard') }}" class="...">
                                    Dashboard
                                </a>
                            @else
                                {{-- <a href="{{ route('login') }}" class="px-8 py-4 text-lg font-semibold text-white transition duration-300 bg-transparent border-2 border-white rounded-lg hover:bg-white hover:text-blue-600">
                                    Masuk
                                </a> --}}
                            @endauth
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer id="contact" class="py-12 text-white bg-gray-900">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                <!-- Company Info -->
                <div>
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <img
                            src="{{ asset('images/logo.png') }}"
                            class="object-cover w-10 h-10 rounded-full"
                        >
                    </a>
                    <p class="text-gray-400 text-ss">
                        Solusi terbaik untuk kebutuhan bisnis Anda. Simple, cepat, dan efisien.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold">Tautan Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 transition hover:text-white">Beranda</a></li>
                        <li><a href="#features" class="text-gray-400 transition hover:text-white">Information</a></li>
                        <li><a href="#pricing" class="text-gray-400 transition hover:text-white">Struktur Organisasi</a></li>
                        <li><a href="#about" class="text-gray-400 transition hover:text-white">Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold">Legal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 transition hover:text-white">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-gray-400 transition hover:text-white">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-gray-400 transition hover:text-white">Cookie Policy</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold">Hubungi Kami</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center space-x-2 text-gray-400">
                            <i class="fas fa-envelope"></i>
                            <span>support@myapp.com</span>
                        </li>
                        <li class="flex items-center space-x-2 text-gray-400">
                            <i class="fas fa-phone"></i>
                            <span>+62 812 3456 7890</span>
                        </li>
                        <li class="flex items-center space-x-2 text-gray-400">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jakarta, Indonesia</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 mt-8 text-sm text-center text-gray-400 border-t border-gray-800">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'MyApp') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = document.getElementById('mobile-menu-button');

            if (!menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if(targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
