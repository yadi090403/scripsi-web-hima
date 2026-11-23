<!-- resources/views/home.blade.php -->
@extends('welcome')

@section('title', 'Beranda - ' . config('app.name'))

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-[#030b16] py-20 text-white md:py-28">
        <div class="absolute inset-0 bg-cover bg-center opacity-20" style="background-image: url('{{ asset('images/hero-bg.jpg') }}');"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(17,110,226,0.45),_transparent_30%),radial-gradient(circle_at_75%_20%,_rgba(245,198,75,0.28),_transparent_25%)]"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#030b16] via-[#0a1d34] to-[#0d5ed7]"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="text-left">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-[#f5c64b]/35 bg-white/5 px-4 py-2 text-sm font-semibold tracking-wide text-[#eaf4ff] shadow-lg shadow-[#0b2244]/40 backdrop-blur-sm">
                        <i class="fas fa-shield-alt text-[#f5c64b]"></i>
                        Platform Organisasi Mahasiswa
                    </div>

                    <h1 class="mb-6 text-4xl font-black leading-tight tracking-tight md:text-6xl">
                        Selamat Datang di
                        <span class="mt-2 block text-[#f5c64b]">Himpunan Mahasiswa Alumni Nurul Jihad</span>
                    </h1>

                    <p class="max-w-2xl text-lg text-[#dfeeff] md:text-2xl">
                        Platform terintegrasi untuk mengoptimalkan kegiatan organisasi mahasiswa.
                        Dari manajemen anggota, kegiatan, hingga dokumentasi lengkap.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        @if (Route::has('register'))
                            @auth
                            @else
                                <a href="{{ route('login') }}"
                                    class="rounded-xl bg-[#f5c64b] px-6 py-3.5 text-base font-bold text-[#071a2b] shadow-lg shadow-[#f5c64b]/20 transition duration-200 hover:-translate-y-0.5 hover:bg-[#f7d76a] sm:px-8 sm:py-4 sm:text-lg">
                                    Daftar Gratis
                                </a>
                            @endauth
                        @else
                            @auth
                                <a href="{{ route('dashboard') }}"
                                    class="rounded-xl border border-white/25 bg-white/10 px-6 py-3.5 text-base font-semibold text-white transition duration-200 hover:-translate-y-0.5 hover:bg-white hover:text-[#0d5ed7] sm:px-8 sm:py-4 sm:text-lg backdrop-blur-sm">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="rounded-xl border border-white/25 bg-white/10 px-6 py-3.5 text-base font-semibold text-white transition duration-200 hover:-translate-y-0.5 hover:bg-white hover:text-[#0d5ed7] sm:px-8 sm:py-4 sm:text-lg backdrop-blur-sm">
                                    Login
                                </a>
                            @endauth
                        @endif

                        <a href="#features"
                            class="rounded-xl border border-white/25 bg-transparent px-6 py-3.5 text-base font-semibold text-white transition duration-200 hover:-translate-y-0.5 hover:bg-white/10 sm:px-8 sm:py-4 sm:text-lg">
                            <i class="mr-2 fas fa-play-circle"></i> Lihat Demo
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <div class="rounded-[2rem] border border-white/10 bg-white/5 p-4 shadow-[0_25px_80px_rgba(37,99,235,0.35)] backdrop-blur-xl">
                        <div class="rounded-[1.6rem] bg-white p-5 text-slate-900 shadow-2xl">
                            <div class="mb-4 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Ringkasan</p>
                                    <h2 class="mt-1 text-2xl font-black">Aktivitas Organisasi</h2>
                                </div>
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                                    <i class="fas fa-chart-line text-xl"></i>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <div class="flex items-center justify-between text-sm text-slate-500">
                                        <span>Anggota aktif</span>
                                        <span class="font-semibold text-emerald-600">{{ $anggotaAktif ?? 0 }}</span>
                                    </div>
                                    <div class="mt-3 h-2.5 w-full overflow-hidden rounded-full bg-slate-200">
                                        <div class="h-full w-[78%] rounded-full bg-gradient-to-r from-emerald-500 to-green-400"></div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="rounded-2xl bg-blue-50 p-4">
                                        <p class="text-sm text-slate-500">Pengurus</p>
                                        <p class="mt-2 text-3xl font-black text-blue-600">{{ $totalPengurus ?? 0 }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-violet-50 p-4">
                                        <p class="text-sm text-slate-500">Anggota</p>
                                        <p class="mt-2 text-3xl font-black text-violet-600">{{ $totalAnggota ?? 0 }}</p>
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 p-4 text-white">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-blue-100">Dokumen</p>
                                            <p class="mt-1 text-2xl font-black">{{ $totalDokumen ?? 0 }}</p>
                                        </div>
                                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    @php
        $totalPengurus = \App\Models\User::where('role', 'pengurus')->count();
        $totalAnggota = \App\Models\User::where('role', 'anggota')->count();
        $anggotaAktif = \App\Models\User::where('is_active', true)->count();
        $totalDokumen = \App\Models\Dokumen::count();
    @endphp
    <section class="bg-gradient-to-b from-[#96aabb] via-[#f9fbff] to-[#96aabb] py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-5 md:grid-cols-4">
                <div class="rounded-2xl border border-[#dbeafe] bg-white p-6 text-center shadow-[0_18px_40px_rgba(13,94,215,0.08)] transition-transform duration-300 hover:-translate-y-1">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#dfeeff] text-[#0d5ed7]">
                        <i class="fas fa-user-tie text-xl"></i>
                    </div>
                    <div class="mb-2 text-4xl font-black text-[#0d5ed7]">{{ $totalPengurus }}</div>
                    <div class="text-sm font-medium text-slate-600">Total Pengurus</div>
                </div>

                <div class="rounded-2xl border border-[#d8f4df] bg-white p-6 text-center shadow-[0_18px_40px_rgba(11,31,58,0.06)] transition-transform duration-300 hover:-translate-y-1">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#eafaf1] text-[#0d5ed7]">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="mb-2 text-4xl font-black text-[#0d5ed7]">{{ $totalAnggota }}</div>
                    <div class="text-sm font-medium text-slate-600">Total Anggota</div>
                </div>

                <div class="rounded-2xl border border-[#efe7ff] bg-white p-6 text-center shadow-[0_18px_40px_rgba(30,64,175,0.06)] transition-transform duration-300 hover:-translate-y-1">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#edf1ff] text-[#0d5ed7]">
                        <i class="fas fa-user-check text-xl"></i>
                    </div>
                    <div class="mb-2 text-4xl font-black text-[#0d5ed7]">{{ $anggotaAktif }}</div>
                    <div class="text-sm font-medium text-slate-600">Anggota Aktif</div>
                </div>

                <div class="rounded-2xl border border-[#fff1c7] bg-white p-6 text-center shadow-[0_18px_40px_rgba(245,198,75,0.1)] transition-transform duration-300 hover:-translate-y-1">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#fff4c7] text-[#d4a10a]">
                        <i class="fas fa-file-alt text-xl"></i>
                    </div>
                    <div class="mb-2 text-4xl font-black text-[#d4a10a]">{{ $totalDokumen }}</div>
                    <div class="text-sm font-medium text-slate-600">Total Dokumen</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-gradient-to-br from-[#96aabb] via-[#dee0e2] to-[#96aabb] fade-in px-6 py-16 md:py-20">
        <div class="mx-auto max-w-7xl">
            <div class="mb-12 text-center">
                <span class="inline-flex items-center rounded-full bg-[#dfeeff] px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-[#0d5ed7]">
                    Information
                </span>
                <h2 class="mt-5 text-4xl font-black text-[#323130] md:text-5xl">
                    Berita & Pengumuman
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 xl:grid-cols-4">
                @foreach($announcements as $announcement)
                    <div class="group flex h-full justify-center">
                        <div class="flex h-full w-full max-w-sm flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_18px_45px_rgba(15,23,42,0.08)] transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(37,99,235,0.12)]">
                            <div class="relative h-52 overflow-hidden bg-gradient-to-br from-blue-700 to-sky-500">
                                @if($announcement->image_path && file_exists(public_path('storage/' . $announcement->image_path)))
                                    <img src="{{ asset('storage/' . $announcement->image_path) }}"
                                        alt="{{ $announcement->title }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="flex h-full flex-col items-center justify-center text-white">
                                        <i class="fas fa-image text-4xl opacity-70"></i>
                                        <p class="mt-3 text-sm opacity-80">Tidak ada gambar</p>
                                    </div>
                                @endif
                                <span class="absolute right-4 top-4 rounded-full bg-white/90 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.2em] text-blue-700">Baru</span>
                            </div>

                            <div class="flex flex-1 flex-col p-5">
                                <h3 class="line-clamp-2 text-lg font-bold text-slate-800">
                                    {{ $announcement->title }}
                                </h3>

                                <div class="mt-3 flex items-center gap-2 text-sm text-slate-500">
                                    <i class="fas fa-user-circle text-blue-500"></i>
                                    <span>{{ $announcement->author->name }}</span>
                                </div>

                                <p class="mt-4 line-clamp-4 flex-1 text-sm leading-relaxed text-slate-600">
                                    {{ Str::limit(strip_tags($announcement->content), 180) }}
                                </p>

                                <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                                    <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                                        <i class="far fa-clock"></i>
                                        <span>{{ $announcement->created_at->format('d M Y') }}</span>
                                    </div>

                                    <button
                                        onclick="showAnnouncementModal({{ $announcement->id }}, '{{ htmlspecialchars($announcement->title, ENT_QUOTES) }}', '{{ htmlspecialchars($announcement->content, ENT_QUOTES) }}', '{{ htmlspecialchars($announcement->author->name, ENT_QUOTES) }}', '{{ $announcement->created_at->format('d M Y') }}', '{{ $announcement->image_path ? asset('storage/' . $announcement->image_path) : '' }}')"
                                        class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 transition-all duration-300 hover:text-blue-700">
                                        Selengkapnya
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="pricing" class="py-20 bg-gradient-to-br from-[#96aabb] via-[#dee0e2] to-[#0d5ed7] fade-in">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-16 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 mb-8 rounded-full shadow-lg shadow-[#f5c64b]/20 bg-gradient-to-r from-[#0d5ed7] to-[#f5c64b]">
                    <i class="text-2xl text-white fas fa-sitemap"></i>
                </div>
                <h2 class="mb-4 text-5xl font-black text-[#323130] md:text-6xl">
                    Struktur Organisasi
                </h2>
                <p class="max-w-2xl mx-auto text-lg text-[#323130]">
                    Hierarki dan susunan kepengurusan organisasi mahasiswa yang modern dan terstruktur
                </p>
            </div>

            @if($positions->count() > 0)
                <!-- Organizational Chart -->
                <div class="relative">
                    <!-- Top Level (Ketua) -->
                    @foreach($positions as $position)
                        @if($position->position_level == 'Ketua')
                        <div class="flex justify-center mb-16">
                            <div class="text-center">
                                <!-- Position Card untuk Ketua -->
                                <div class="org-card bg-gradient-to-r {{ $position->level_color }} text-white rounded-2xl p-8 w-80 mx-auto shadow-2xl transform hover:scale-105 hover:-translate-y-2 transition-all duration-500 group">
                                    <div class="relative text-center">
                                        <!-- Decorative elements -->
                                        <div class="absolute flex items-center justify-center w-8 h-8 rounded-full -top-4 -right-4 bg-white/20">
                                            <i class="text-sm text-yellow-300 fas fa-crown"></i>
                                        </div>

                                        <!-- Foto Profil -->
                                        <div class="w-24 h-24 mx-auto mb-4 overflow-hidden transition-shadow duration-300 border-4 rounded-full shadow-xl bg-white/20 backdrop-blur-sm border-white/30 group-hover:shadow-2xl">
                                            @if($position->photo)
                                                <img src="{{ $position->photo_url }}"
                                                    alt="{{ $position->display_name }}"
                                                    class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-110">
                                            @else
                                                <div class="flex items-center justify-center w-full h-full bg-gradient-to-r from-white/30 to-white/10">
                                                    <span class="text-3xl font-bold text-white drop-shadow-lg">
                                                        {{ $position->initials }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <h3 class="mb-2 text-xl font-bold">{{ $position->position_name }}</h3>
                                        <p class="mb-3 text-lg font-medium text-white/95">{{ $position->display_name }}</p>
                                        @if($position->description)
                                        <p class="p-3 mt-3 text-sm italic rounded-lg text-white/85 bg-white/10 backdrop-blur-sm">
                                            {{ Str::limit($position->description, 80) }}
                                        </p>
                                        @endif

                                        <!-- Status badge -->
                                        <div class="inline-flex items-center px-3 py-1 mt-4 text-xs font-semibold text-white rounded-full bg-white/20 backdrop-blur-sm">
                                            <i class="mr-1 fas fa-star"></i> Ketua Utama
                                        </div>
                                    </div>

                                    <!-- Glow effect -->
                                    <div class="absolute inset-0 transition-opacity duration-500 opacity-0 rounded-2xl bg-gradient-to-r from-white/0 via-white/5 to-white/0 group-hover:opacity-100"></div>
                                </div>

                                <!-- Connection Line -->
                                @if($position->children->count() > 0)
                                <div class="w-1 h-8 mx-auto mt-4 rounded-full shadow-lg bg-gradient-to-b from-blue-400 via-purple-400 to-pink-400 animate-pulse"></div>
                                @endif
                            </div>
                        </div>

                        <!-- Children of Ketua -->
                        @if($position->children->count() > 0)
                        <div class="flex justify-center mb-12">
                            <div class="grid grid-cols-1 md:grid-cols-{{ min($position->children->count(), 3) }} gap-8">
                                @foreach($position->children as $child)
                                <div class="text-center">
                                    <!-- Connection Line -->
                                    <div class="w-1 h-8 mx-auto mb-4 rounded-full bg-gradient-to-b from-gray-300 to-gray-400"></div>

                                    <!-- Child Card -->
                                    <div class="w-64 p-6 transition-all duration-500 transform bg-white border border-gray-100 shadow-xl org-card rounded-2xl hover:shadow-2xl hover:-translate-y-3 group backdrop-blur-sm bg-white/80">
                                        <div class="text-center">
                                            <!-- Foto Profil Child -->
                                            <div class="w-20 h-20 mx-auto mb-4 overflow-hidden bg-gradient-to-r {{ $child->level_color }} border-3 border-white rounded-full shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                                @if($child->photo)
                                                    <img src="{{ $child->photo_url }}"
                                                        alt="{{ $child->display_name }}"
                                                        class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-110">
                                                @else
                                                    <div class="flex items-center justify-center w-full h-full">
                                                        <span class="text-lg font-bold text-white drop-shadow-md">
                                                            {{ $child->initials }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <h4 class="mb-2 text-lg font-bold text-gray-800">{{ $child->position_name }}</h4>
                                            <p class="mb-3 font-medium text-gray-600">{{ $child->display_name }}</p>

                                            @if($child->description)
                                            <p class="p-2 mt-3 text-sm text-gray-500 rounded-lg bg-gray-50">
                                                {{ Str::limit($child->description, 60) }}
                                            </p>
                                            @endif

                                            <!-- Role indicator -->
                                            <div class="inline-flex items-center px-2 py-1 mt-3 text-xs font-medium text-blue-700 rounded-full bg-blue-50">
                                                <i class="mr-1 fas fa-user-tie"></i> {{ $child->position_level }}
                                            </div>
                                        </div>

                                        <!-- Hover effect overlay -->
                                        <div class="absolute inset-0 transition-opacity duration-300 opacity-0 rounded-2xl bg-gradient-to-r from-blue-500/5 to-purple-500/5 group-hover:opacity-100"></div>
                                    </div>

                                    <!-- Grandchildren -->
                                    @if($child->children->count() > 0)
                                    <div class="w-1 h-6 mx-auto mt-4 rounded-full bg-gradient-to-b from-blue-200 to-blue-300"></div>
                                    <div class="mt-6 grid grid-cols-{{ min($child->children->count(), 4) }} gap-4">
                                        @foreach($child->children as $grandchild)
                                        <div class="text-center">
                                            <div class="p-4 transition-all duration-300 transform border border-gray-200 shadow-md rounded-xl bg-gradient-to-br from-white to-gray-50 hover:shadow-lg hover:-translate-y-1 group">
                                                <div class="w-12 h-12 mx-auto mb-2 bg-gradient-to-r {{ $grandchild->level_color }} rounded-full flex items-center justify-center shadow-md">
                                                    <span class="text-xs font-bold text-white">{{ $grandchild->initials }}</span>
                                                </div>
                                                <h5 class="text-sm font-semibold text-gray-700">{{ $grandchild->position_name }}</h5>
                                                <p class="text-xs text-gray-500">{{ $grandchild->display_name }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @endif
                    @endforeach

                    <!-- Other Top Level Positions -->
                    @if($positions->where('position_level', '!=', 'Ketua')->count() > 0)
                    <div class="mt-16 fade-in">
                        <h3 class="flex items-center justify-center mb-8 text-2xl font-bold text-center text-[#323130]">
                            <i class="mr-3 text-blue-500 fas fa-users-cog"></i>
                            Divisi & Bidang
                        </h3>
                        <div class="grid grid-cols-1 gap-8 fade-in md:grid-cols-2 lg:grid-cols-3">
                            @foreach($positions->where('position_level', '!=', 'Ketua') as $position)
                                @if($position->parent_id === null)
                                <div class="p-6 transition-all duration-500 transform bg-white border border-gray-100 shadow-xl org-card rounded-2xl hover:shadow-2xl hover:-translate-y-2 group backdrop-blur-sm bg-white/90">
                                    <div class="flex items-start">
                                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-r {{ $position->level_color }} flex items-center justify-center mr-5 shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                                            <i class="text-xl text-white fas fa-users"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="mb-2 text-xl font-bold text-gray-800">{{ $position->position_name }}</h4>
                                            <p class="mb-3 font-medium text-gray-600">{{ $position->display_name }}</p>
                                            @if($position->description)
                                            <p class="p-3 mb-4 text-sm text-gray-500 rounded-lg bg-gray-50">
                                                {{ Str::limit($position->description, 100) }}
                                            </p>
                                            @endif

                                            <!-- Members under this position -->
                                            @if($position->children->count() > 0)
                                            <div class="mt-4">
                                                <p class="flex items-center mb-3 text-xs font-semibold text-gray-500">
                                                    <i class="mr-1 fas fa-user-friends"></i>
                                                    Anggota ({{ $position->children->count() }})
                                                </p>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($position->children as $child)
                                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-gray-700 transition-colors duration-200 rounded-full bg-gradient-to-r from-gray-100 to-gray-200 hover:from-blue-50 hover:to-blue-100">
                                                        <div class="w-4 h-4 rounded-full bg-gradient-to-r {{ $child->level_color }} mr-2"></div>
                                                        {{ $child->display_name }}
                                                    </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Hover effect overlay -->
                                    <div class="absolute inset-0 transition-opacity duration-300 opacity-0 rounded-2xl bg-gradient-to-r from-blue-500/5 to-purple-500/5 group-hover:opacity-100"></div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            @else
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 mb-8 rounded-full shadow-lg bg-gradient-to-r from-gray-100 to-gray-200">
                        <i class="text-4xl text-gray-400 fas fa-sitemap"></i>
                    </div>
                    <h3 class="mb-4 text-2xl font-bold text-gray-600">Belum Ada Struktur Organisasi</h3>
                    <p class="max-w-lg mx-auto mb-8 leading-relaxed text-gray-500">
                        Struktur organisasi akan ditampilkan di sini setelah dibuat oleh pengurus.
                        Sistem ini akan menampilkan hierarki kepengurusan secara otomatis.
                    </p>
                    <div class="inline-flex items-center px-6 py-3 font-semibold text-white transition-shadow duration-300 shadow-lg bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl hover:shadow-xl">
                        <i class="mr-2 fas fa-plus-circle"></i>
                        Struktur Akan Muncul Otomatis
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gradient-to-r from-[#071a2b] via-[#0b2244] to-[#0d5ed7] py-20 text-white">
        <div class="mx-auto max-w-5xl px-4 text-center sm:px-6 lg:px-8">
            <div class="inline-flex items-center justify-center rounded-full border border-[#f5c64b]/30 bg-white/10 px-4 py-2 text-sm font-medium text-[#fff1bf] backdrop-blur-sm">
                <i class="fas fa-rocket mr-2 text-[#f5c64b]"></i>
                Siap dimulai?
            </div>

            <h2 class="mt-6 text-3xl font-black text-white md:text-5xl">Siap Mengubah Cara Anda Mengelola Organisasi?</h2>
            <p class="mx-auto mt-5 max-w-3xl text-lg text-sky-100">
                “Sekarang bukan lagi tentang siapa yang paling kuat untuk bertahan, tetapi tentang siapa yang paling mampu beradaptasi di era Globalisasi.”
            </p>
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                @if (Route::has('register'))
                    @auth
                    @else
                        <a href="{{ route('login') }}" class="rounded-xl bg-[#f5c64b] px-8 py-4 text-lg font-bold text-[#071a2b] shadow-lg shadow-[#f5c64b]/20 transition duration-200 hover:-translate-y-0.5 hover:bg-[#f7d76a]">
                            Daftar Gratis
                        </a>
                    @endauth
                @else
                    @auth
                        <a href="{{ url('dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-lg font-semibold text-[#0d5ed7] transition hover:-translate-y-0.5 hover:bg-[#edf5ff]">
                            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-xl bg-white px-8 py-4 text-lg font-semibold text-[#0d5ed7] transition duration-200 hover:-translate-y-0.5 hover:bg-[#edf5ff]">
                            Login
                        </a>
                    @endauth
                @endif
                <a href="konsultasi.html"
                   class="rounded-xl border-2 border-white/40 bg-transparent px-8 py-4 text-lg font-semibold text-white transition duration-200 hover:bg-white/10">
                    <i class="mr-2 fas fa-comment-alt"></i> Konsultasi Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- Announcement Modal -->
    <div id="announcementModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[95vh] sm:max-h-[90vh] overflow-hidden mx-2 sm:mx-0">
                <!-- Modal Header -->
                <div class="px-8 py-6 bg-gradient-to-r from-blue-500 to-purple-600">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 id="modalTitle" class="mb-3 text-3xl font-bold text-white">Judul Pengumuman</h1>
                            <div class="flex items-center space-x-6">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-10 h-10 mr-3 rounded-full bg-white/20 backdrop-blur-sm">
                                        <i class="text-white fas fa-user"></i>
                                    </div>
                                    <div>
                                        <p id="modalAuthor" class="font-medium text-blue-100">Nama Author</p>
                                        <p class="text-sm text-blue-200">Pembuat Pengumuman</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="mr-2 text-lg text-blue-200 far fa-clock"></i>
                                    <div>
                                        <p id="modalDate" class="font-medium text-blue-100">Tanggal</p>
                                        <p class="text-sm text-blue-200">Waktu</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button onclick="closeAnnouncementModal()" class="text-white transition hover:text-gray-200">
                            <i class="text-2xl fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-4 max-h-[75vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 sm:p-8 sm:max-h-[70vh]">
                    <!-- Image (if exists) -->
                    <div id="modalImageContainer" class="hidden mb-4 sm:mb-6">
                        <img id="modalImage" src="" alt="Announcement Image" class="object-cover w-full h-48 shadow-lg rounded-xl sm:h-64">
                    </div>

                    <!-- Content -->
                    <div class="prose-sm prose prose-lg max-w-none sm:prose-lg">
                        <div class="p-4 mb-6 border-l-4 border-blue-500 shadow-sm bg-gray-50 rounded-xl sm:p-6 sm:mb-8">
                            <div id="modalContent" class="leading-relaxed text-gray-700 whitespace-pre-line min-h-[200px] sm:min-h-[300px]">
                                Konten pengumuman akan muncul di sini...
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="sticky bottom-0 px-4 pt-4 pb-4 mt-6 -mx-4 -mb-4 bg-white border-t border-gray-200 rounded-b-2xl sm:px-8 sm:pt-6 sm:pb-6 sm:mt-8 sm:-mx-8 sm:-mb-8">
                        <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                            <div class="flex items-center">
                                <div class="flex items-center text-gray-500">
                                    <i class="mr-2 fas fa-eye"></i>
                                    <span class="text-sm">Dibaca oleh 125 anggota</span>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <button onclick="closeAnnouncementModal()" class="flex items-center px-4 py-2 text-sm font-semibold text-gray-700 transition-colors bg-gray-100 rounded-lg shadow-sm hover:bg-gray-200 sm:px-6 sm:py-3 sm:text-base">
                                    <i class="mr-2 fas fa-times"></i>
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .backup-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            position: relative;
            overflow: hidden;
            border-radius: 0px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        .backup-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }
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

        .fade-in {
            animation: fadeIn 0.6s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-4 {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 640px) {
            .stats-number {
                font-size: 2rem !important;
            }
            .stats-grid {
                gap: 1rem !important;
            }
            .org-card {
                margin: 0 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 2rem !important;
                line-height: 1.2 !important;
            }
            .hero-subtitle {
                font-size: 1rem !important;
            }
        }
    </style>

    <script>
        function showAnnouncementModal(id, title, content, author, date, imageUrl) {
            // Set modal content
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalAuthor').textContent = author;
            document.getElementById('modalDate').textContent = date;
            document.getElementById('modalContent').textContent = content;

            // Handle image
            const imageContainer = document.getElementById('modalImageContainer');
            const imageElement = document.getElementById('modalImage');

            if (imageUrl) {
                imageElement.src = imageUrl;
                imageContainer.classList.remove('hidden');
            } else {
                imageContainer.classList.add('hidden');
            }

            // Show modal
            document.getElementById('announcementModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAnnouncementModal() {
            document.getElementById('announcementModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('announcementModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAnnouncementModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('announcementModal').classList.contains('hidden')) {
                closeAnnouncementModal();
            }
        });
    </script>


@endsection
