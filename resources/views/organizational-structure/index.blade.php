@extends('layouts.app')

@section('title', 'Struktur Organisasi')

@section('content')
<div class="container px-4 py-8 mx-auto fade-in">
    <!-- Header -->
    <div class="flex flex-col items-start justify-between mb-8 md:flex-row md:items-center">
        <div>
            <h1 class="mb-2 text-3xl font-bold text-gray-800">Struktur Organisasi</h1>
            <p class="text-gray-600">Hierarki dan susunan kepengurusan organisasi</p>
        </div>

        @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
        <a href="{{ route('organizational-structure.create') }}"
           class="flex items-center px-6 py-3 mt-4 font-semibold text-white transition-all duration-300 transform rounded-lg shadow-lg backup-card md:mt-0 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:blue-700 hover:shadow-xl hover:-translate-y-1">
            <i class="mr-2 fas fa-plus-circle"></i>
            Tambah Posisi
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="p-4 mb-6 border-l-4 border-green-500 rounded-r-lg backup-card fade-in bg-green-50">
        <div class="flex items-center">
            <i class="mr-3 text-green-500 fas fa-check-circle"></i>
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 mb-6 border-l-4 border-red-500 rounded-r-lg fade-in bg-red-50">
        <div class="flex items-center">
            <i class="mr-3 text-red-500 fas fa-exclamation-circle"></i>
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <div class="backup-card">
        <div style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); padding: 20px; border-top-left-radius:1rem; border-top-right-radius:1rem; border-bottom: 1px solid #f0f0f0;">
            <h2 style="color: white; font-size: 1.2rem; font-weight: 600; margin: 0;">Struktur Kepengurusan</h2>
        </div>
        <!-- Organizational Chart -->
        <div class="p-6 mb-8 bg-white fade-in">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-800"></h2>
                <div class="text-sm text-gray-500">
                    <i class="mr-1 fas fa-info-circle"></i>
                    Total: {{ $positions->count() }} posisi
                </div>
            </div>

            @if($positions->count() > 0)
                <!-- Organizational Tree -->
                <div class="relative">
                    <!-- Top Level (Ketua) -->
                    @foreach($positions as $position)
                        @if($position->position_level == 'Ketua')
                        <div class="flex justify-center mb-12">
                            <div class="text-center">
                                <!-- Position Card untuk Ketua -->
                                <div class="bg-gradient-to-r {{ $position->level_color }} text-white rounded-xl p-6 w-64 mx-auto shadow-lg transform hover:scale-105 transition-transform duration-300">
                                    <div class="text-center">
                                        <!-- Foto Profil -->
                                        <div class="w-20 h-20 mx-auto mb-3 overflow-hidden bg-white border-4 rounded-full border-white/30">
                                            @if($position->photo)
                                                <img src="{{ $position->photo_url }}"
                                                    alt="{{ $position->display_name }}"
                                                    class="object-cover w-full h-full">
                                            @else
                                                <div class="flex items-center justify-center w-full h-full bg-gradient-to-r from-red-500 to-red-600">
                                                    <span class="text-2xl font-bold text-white">
                                                        {{ $position->initials }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <h3 class="text-lg font-bold">{{ $position->position_name }}</h3>
                                        <p class="mb-2 text-sm text-white/90">{{ $position->display_name }}</p>
                                        @if($position->description)
                                        <p class="mt-2 text-xs italic text-white/80">{{ Str::limit($position->description, 60) }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Connection Line -->
                                @if($position->children->count() > 0)
                                <div class="h-6 w-0.5 bg-gray-300 mx-auto mt-2"></div>
                                @endif
                            </div>
                        </div>

                        <!-- Children of Ketua -->
                        @if($position->children->count() > 0)
                        <div class="flex justify-center mb-8">
                            <div class="grid grid-cols-1 md:grid-cols-{{ min($position->children->count(), 3) }} gap-6">
                                @foreach($position->children as $child)
                                <div class="text-center">
                                    <!-- Connection Line -->
                                    <div class="h-6 w-0.5 bg-gray-300 mx-auto mb-2"></div>

                                    <!-- Child Card -->
                                    <div class="w-56 p-5 transition-shadow duration-300 bg-white border border-gray-200 shadow-md rounded-xl hover:shadow-lg">
                                        <div class="text-center">
                                            <!-- Foto Profil Child -->
                                            <div class="w-16 h-16 mx-auto mb-3 overflow-hidden bg-gray-100 border-2 border-gray-300 rounded-full">
                                                @if($child->photo)
                                                    <img src="{{ $child->photo_url }}"
                                                        alt="{{ $child->display_name }}"
                                                        class="object-cover w-full h-full">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-r {{ $child->level_color }} flex items-center justify-center">
                                                        <span class="text-sm font-bold text-white">
                                                            {{ $child->initials }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-gray-800">{{ $child->position_name }}</h4>
                                            <p class="mb-2 text-sm text-gray-600">{{ $child->display_name }}</p>

                                            @if($child->description)
                                            <p class="mt-2 text-xs text-gray-500">{{ Str::limit($child->description, 50) }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Grandchildren -->
                                    @if($child->children->count() > 0)
                                    <div class="h-6 w-0.5 bg-gray-300 mx-auto mt-2"></div>
                                    <div class="mt-4 grid grid-cols-{{ min($child->children->count(), 4) }} gap-3">
                                        @foreach($child->children as $grandchild)
                                        <div class="text-center">
                                            <div class="p-3 border border-gray-100 rounded-lg bg-gray-50">
                                                <h5 class="text-sm font-medium text-gray-700">{{ $grandchild->position_name }}</h5>
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
                    <div class="mt-12">
                        <h3 class="mb-6 text-lg font-bold text-center text-gray-700">Divisi & Bidang</h3>
                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
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
                <div class="py-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 mb-6 bg-gray-100 rounded-full">
                        <i class="text-3xl text-gray-400 fas fa-sitemap"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-600">Belum Ada Struktur Organisasi</h3>
                    <p class="max-w-md mx-auto mb-6 text-gray-500">
                        Mulai dengan membuat struktur organisasi untuk menampilkan hierarki kepengurusan.
                    </p>
                    @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
                    <a href="{{ route('organizational-structure.create') }}"
                    class="inline-flex items-center px-6 py-3 font-semibold text-white transition-all duration-300 rounded-lg shadow-lg bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700">
                        <i class="mr-2 fas fa-plus"></i>
                        Buat Struktur Pertama
                    </a>
                    @endif
                </div>
            @endif
        </div>
    </div>


    <!-- Table View (for management) -->
    @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
    <div class="p-6 bg-white border border-gray-200 shadow-lg backup-card rounded-2xl">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Manajemen Posisi</h2>
            <div class="text-sm text-gray-500">
                <i class="mr-1 fas fa-edit"></i>
                Mode pengelolaan
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-400">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Posisi</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Level</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Penanggung Jawab</th>
                        <th class="py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase px6">Parent</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($positions as $position)
                    <tr class="transition-colors hover:bg-gray-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r {{ $position->level_color }} flex items-center justify-center mr-3">
                                    <i class="text-xs text-white fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $position->position_name }}</div>
                                    @if($position->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($position->description, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($position->position_level == 'Ketua') bg-red-100 text-red-800
                                @elseif($position->position_level == 'Wakil Ketua') bg-orange-100 text-orange-800
                                @elseif($position->position_level == 'Sekretaris' || $position->position_level == 'Bendahara') bg-blue-100 text-blue-800
                                @elseif($position->position_level == 'Koordinator') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $position->position_level }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($position->user)
                                <div class="flex items-center justify-center w-8 h-8 mr-2 bg-gray-200 rounded-full">
                                    <i class="text-gray-600 fas fa-user"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="font-medium">{{ $position->display_name }}</div>
                                    @if($position->user)
                                    <div class="text-sm text-gray-500">{{ $position->user->email }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($position->parent)
                            <span class="text-gray-600">{{ $position->parent->position_name }}</span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <div class="flex space-x-2">
                                <a href="{{ route('organizational-structure.edit', $position->id) }}"
                                   class="p-2 text-yellow-600 transition-colors rounded hover:text-yellow-900 hover:bg-yellow-50"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('organizational-structure.destroy', $position->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Hapus posisi ini?')"
                                            class="p-2 text-red-600 transition-colors rounded hover:text-red-900 hover:bg-red-50"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
<style>
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
    /* Efek fade in untuk konten */
    .fade-in {
        animation: fadeIn 0.6s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

</style>
@endsection
