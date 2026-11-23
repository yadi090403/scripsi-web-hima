<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIMA NJ - @yield('title', 'Dashboard')</title>

        <!-- App & Kegiatan Styles (load from public/css) -->
    <link rel="stylesheet" href="{{ asset('css/kegiatan-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/kegiatan-form-override.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Dahsboard.css') }}">

    <!-- TAILWIND CSS VIA CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">

    <!-- Chart.js for modern charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Di head app.blade.php -->


    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'hima-blue': '#1e40af',
                        'hima-dark': '#0f172a',
                        'hima-sidebar': '#1e293b',
                        'hima-light': '#3b82f6',
                        'chart-1': '#3b82f6',
                        'chart-2': '#10b981',
                        'chart-3': '#f59e0b',
                        'chart-4': '#ef4444',
                    }
                }
            }
        }
    </script>

    <!-- Custom Styles -->
    <style>
        * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }
        body {
            overflow-x: hidden;
            width: 100%;
            background-color: #f9fafb;
        }
        html {
            width: 100%;
            overflow-x: hidden;
        }

        /* Enhanced transitions */
        .sidebar-link {
            transition: all 0.2s ease;
        }
        .sidebar-link.active {
            background-color: #334155;
            border-left: 4px solid #3b82f6;
        }
        .sidebar-link:hover:not(.active) {
            background-color: #334155;
            transform: translateX(4px);
        }
        .content-area {
            margin-left: 16rem;
            transition: margin-left 0.3s;
            width: calc(100% - 16rem);
            min-width: 0;
            box-sizing: border-box;
            background-color: #f9fafb;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .content-area {
                margin-left: 0;
                width: 100%;
            }
        }
        .dropdown-profile {
            transition: all 0.3s ease;
        }
        .dropdown-profile-content {
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }
        .dropdown-profile:hover .dropdown-profile-content,
        .dropdown-profile-content.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        main {
            width: 100%;
            display: block;
            overflow-x: hidden;


        main > * {
            width: 100%;
        }

        header {
            width: 100%;
            box-sizing: border-box;
        }

        .grid {
            width: 100%;
        }

        /* Animasi untuk kartu */
        .backup-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .backup-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }
        /* Efek shimmer pada hover */
        .backup-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.7s;
        }

        .backup-card:hover::before {
            left: 100%;
        }

        /* Enhanced fade in */
        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Button hover effects */
        button, .btn {
            transition: all 0.2s ease !important;
        }
        button:hover, .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        button:active, .btn:active {
            transform: translateY(0);
        }

        /* Table row hover */
        tr {
            transition: background-color 0.2s ease;
        }
        tr:hover {
            background-color: #f9fafb;
        }

        /* Form input focus */
        input:focus, select:focus, textarea:focus {
            transition: all 0.2s ease !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
        }

    </style>



    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50">
    @auth
    <!-- Mobile Menu Button -->
    <button id="mobileMenuButton" class="fixed z-50 p-2 text-white rounded-lg md:hidden top-4 left-4 bg-hima-blue">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 z-40 w-64 h-screen text-white sidebar bg-hima-sidebar bg-gradient-to-r from-blue-600 to-purple-600" style="width: 16rem !important;">
        <!-- Logo & Brand -->
        <div class="p-6 border-b border-white">
            <div class="flex items-center space-x-3">
                <img
                src="{{ asset('images/logo.png') }}"
                class="object-cover w-10 h-10 rounded-full">
                <div>
                    <h1 class="text-xl font-bold">HIMA NURJI</h1>
                    <p class="text-xs text-gray-400">Organisasi Mahasiswa</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-4 space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
            class="backup-card sidebar-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="w-5 fas fa-home"></i>
                <span>Dashboard</span>
            </a>

            <!-- Profile Saya -->
            <a href="{{ route('profile') }}"
            class="backup-card sidebar-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('profile*') ? 'active' : '' }}">
                <i class="w-5 fas fa-user-circle"></i>
                <span>Profile Saya</span>
            </a>
            <!-- Master User (Admin only) -->
            @if(auth()->user()->isAdmin())
            <div class="mt-6">
                <p class="px-3 mb-2 text-xs font-semibold text-yellow-600 uppercase">Master User</p>
                <!-- Hanya Manajemen Anggota saja -->
                <a href="{{ route('anggota.index') }}"
                class="backup-card sidebar-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('anggota.*') ? 'active' : '' }}">
                    <i class="w-5 fas fa-users"></i>
                    <span>Manajemen Data</span>
                </a>
                <!-- TAMBAH ANGGOTA DIHAPUS DARI SIDEBAR (tetap ada di dalam halaman Manajemen Anggota) -->
            </div>
            @endif

            <!-- Master Data (Admin & Pengurus) -->
            @if(auth()->user()->isAdmin() || auth()->user()->isPengurus())
            <div class="mt-6 space-y-2">
                <p class="px-3 mb-3 text-xs font-semibold text-yellow-600 uppercase">Master Data</p>
                <a href="{{ route('organizational-structure.index') }}" class="backup-card flex items-center p-3 space-x-3 rounded-lg sidebar-link {{ request()->routeIs('organizational-structure.*') ? 'active' : '' }}">
                    <i class="w-5 fas fa-sitemap"></i>
                    <span>Struktur Organisasi</span>
                </a>
                <a href="{{ route('kegiatan.index') }}" class="backup-card flex items-center p-3 space-x-3 rounded-lg sidebar-link {{ request()->routeIs('kegiatan.*') ? 'active' : '' }}">
                    <i class="w-5 fas fa-calendar-alt"></i>
                    <span>Kegiatan</span>
                </a>
                <a href="{{ route('announcements.index') }}" class="backup-card flex items-center p-3 space-x-3 rounded-lg sidebar-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                    <i class="w-5 fas fa-bullhorn"></i>
                    <span>Informasi</span>
                </a>
            </div>
            @endif


            <!-- Dokumen Menu -->
            <div class="mt-6">
                <p class="px-3 mb-2 text-xs font-semibold text-yellow-600 uppercase">Manajemen Dokumen</p>

                <a href="{{ route('dokumen.index') }}"
                class="backup-card sidebar-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('dokumen.index') ? 'active' : '' }}">
                    <i class="w-5 fas fa-folder-open"></i>
                    <span>Semua Dokumen</span>
                </a>

            <!-- Admin Panel (Admin only) -->
            @if(auth()->user()->isAdmin())
            <div class="mt-6">
                <p class="px-3 mb-2 text-xs font-semibold text-yellow-500 uppercase">Admin Panel</p>
                {{-- <a href="#" class="flex items-center p-3 space-x-3 rounded-lg sidebar-link">
                    <i class="w-5 fas fa-cog"></i>
                    <span>Pengaturan Sistem</span>
                </a>
                <a href="#" class="flex items-center p-3 space-x-3 rounded-lg sidebar-link">
                    <i class="w-5 fas fa-chart-bar"></i>
                    <span>Laporan & Statistik</span>
                </a> --}}
                <a href="{{ route('backup.index') }}"
                class="backup-card sidebar-link flex items-center space-x-3 p-3 rounded-lg {{ request()->routeIs('backup.*') ? 'active' : '' }}">
                    <i class="w-5 mr-3 fas fa-database"></i>
                    Backup Data
                </a>
            </div>
            @endif
        </nav>

        <!-- Logout Button DIHAPUS DARI SIDEBAR (dipindah ke dropdown header) -->
    </aside>

    <!-- Main Content Area -->
    <div class="min-h-screen content-area" style="margin-left: 16rem !important;">
        <!-- Top Header -->
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200">
            <div class="px-6 py-3.5">
                <div class="flex items-center justify-between">
                    <!-- Page Title -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">@yield('title', 'Dashboard')</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            @hasSection('subtitle')
                                @yield('subtitle')
                            @else
                                {{ now()->translatedFormat('l, d F Y') }}
                            @endif
                        </p>
                    </div>

                    <!-- Right Side Actions - Dipindahkan ke sini -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2 transition rounded-full hover:bg-gray-100">
                            <i class="text-gray-600 fas fa-bell"></i>
                            <span class="absolute w-2 h-2 bg-red-500 rounded-full top-1 right-1"></span>
                        </button>

                        <!-- Quick Actions Dropdown -->
                        <!-- Removed: Not needed for current functionality -->

                        <!-- Profile Dropdown (POSISI BARU: pojok kanan atas) -->
                        <div class="relative dropdown-profile">
                            <button id="profileDropdownButton" class="flex items-center p-2 space-x-3 transition rounded-lg hover:bg-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center justify-center w-8 h-8 font-bold text-white bg-blue-600 rounded-full">
                                        {{ auth()->user()->getInitials() ?? strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="hidden text-left md:block">
                                        <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                                        <p class="mt-1">
                                            <span class="px-2 py-1 rounded text-sm {{ auth()->user()->getRoleBadgeClass() }}">
                                                {{ auth()->user()->getRoleDisplay() }}
                                            </span>
                                        </p>
                                    </div>
                                    <i class="text-xs text-gray-500 fas fa-chevron-down"></i>
                                </div>
                            </button>

                            <div id="profileDropdown" class="absolute right-0 z-50 w-56 py-2 mt-2 bg-white border border-gray-200 rounded-lg shadow-xl dropdown-profile-content">
                                <!-- Header dropdown -->
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                    {{-- <p class="mt-1 text-xs font-medium text-blue-600">Admin HIMA NJ</p> --}}
                                </div>

                                <!-- Menu dropdown -->
                                <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="w-5 mr-3 text-gray-500 fas fa-user-circle"></i>
                                    <span>Profil Saya</span>
                                </a>

                                <div class="pt-2 mt-2 border-t border-gray-100">
                                    <!-- Logout form -->
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-red-600 hover:bg-red-50">
                                            <i class="w-5 mr-3 fas fa-sign-out-alt"></i>
                                            <span>Keluar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="w-full p-6">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="p-4 mb-6 border-l-4 border-green-500 rounded-r-lg bg-green-50">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="mt-1 text-green-500 fas fa-check-circle"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="p-4 mb-6 border-l-4 border-red-500 rounded-r-lg bg-red-50">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="mt-1 text-red-500 fas fa-exclamation-circle"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </main>
    </div>
    @endauth

    @guest
    <!-- Login Page (no sidebar) -->
    <main class="{{ request()->is('login') ? '' : 'flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-50 to-gray-100' }}">
        @yield('content')
    </main>

    @endguest

    <!-- JavaScript -->
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const sidebar = document.querySelector('.sidebar');

            if (mobileMenuButton && sidebar) {
                mobileMenuButton.addEventListener('click', function() {
                    sidebar.classList.toggle('open');

                    // Change icon
                    const icon = mobileMenuButton.querySelector('i');
                    if (sidebar.classList.contains('open')) {
                        icon.className = 'fas fa-times';
                    } else {
                        icon.className = 'fas fa-bars';
                    }
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(event) {
                    if (window.innerWidth < 768) {
                        if (!sidebar.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                            sidebar.classList.remove('open');
                            const icon = mobileMenuButton.querySelector('i');
                            icon.className = 'fas fa-bars';
                        }
                    }
                });
            }

            // Profile dropdown toggle
            const profileDropdownButton = document.getElementById('profileDropdownButton');
            const profileDropdown = document.getElementById('profileDropdown');

            if (profileDropdownButton && profileDropdown) {
                profileDropdownButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('show');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!profileDropdownButton.contains(event.target) && !profileDropdown.contains(event.target)) {
                        profileDropdown.classList.remove('show');
                    }
                });
            }

            // Set active sidebar link based on current URL
            const currentPath = window.location.pathname;
            document.querySelectorAll('.sidebar-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
