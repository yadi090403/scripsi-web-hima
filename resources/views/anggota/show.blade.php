@extends('layouts.app')

@section('title', 'Detail Anggota')

@section('content')
<div class="max-w-4xl mx-auto fade-in">
    <!-- Profile Card -->
    <div class="overflow-hidden bg-white shadow backup-card rounded-xl">
        <!-- Profile Header -->
        <div class="p-6 bg-gradient-to-r from-blue-500 to-blue-600">
            <div class="flex flex-col items-center space-y-6 md:flex-row md:items-start md:space-y-0 md:space-x-6">
                <!-- Profile Photo -->
                <div class="relative">
                    <div class="w-32 h-32 overflow-hidden border-4 border-white rounded-full shadow-lg">
                        @if($user->foto_profil)
                            <img src="{{ asset('storage/' . $user->foto_profil) }}"
                                 alt="{{ $user->name }}"
                                 class="object-cover w-full h-full">
                        @else
                            <div class="flex items-center justify-center w-full h-full bg-gradient-to-r from-purple-500 to-pink-600">
                                <span class="text-4xl font-bold text-white">
                                    {{ $user->getInitials() }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Status Badge -->
                    <div class="absolute -bottom-2 -right-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            {{ $user->is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                            {{ $user->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                        </span>
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-blue-100">{{ $user->email }}</p>

                    <div class="flex flex-wrap justify-center gap-2 mt-4 md:justify-start">
                        <span class="px-3 py-1 text-sm bg-white rounded-full bg-opacity-20">
                            <i class="mr-1 fas fa-id-card"></i> {{ $user->nim }}
                        </span>
                        <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm
                            {{ $user->role == 'admin' ? 'bg-red-500' :
                               ($user->role == 'pengurus' ? 'bg-blue-500' : 'bg-gray-500') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->divisi)
                        <span class="px-3 py-1 text-sm bg-white rounded-full bg-opacity-20">
                            {{ $user->divisi }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <!-- Personal Information -->
                <div>
                    <h3 class="pb-2 mb-4 text-lg font-semibold text-gray-800 border-b">Informasi Pribadi</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-500">Jenis Kelamin</label>
                            <p class="font-medium">{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500">Tanggal Lahir</label>
                            <p class="font-medium">{{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') : '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500">Agama</label>
                            <p class="font-medium">{{ $user->agama ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500">Nomor HP</label>
                            <p class="font-medium">{{ $user->no_hp ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500">Alamat</label>
                            <p class="font-medium">{{ $user->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Academic & HIMA Information -->
                <div>
                    <h3 class="pb-2 mb-4 text-lg font-semibold text-gray-800 border-b">Informasi Akademik & HIMA</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-500">Fakultas</label>
                            <p class="font-medium">{{ $user->fakultas ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500">Jurusan</label>
                            <p class="font-medium">{{ $user->jurusan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500">Angkatan</label>
                            <p class="font-medium">{{ $user->angkatan ?? '-' }}</p>
                        </div>
                        @if($user->divisi)
                        <div>
                            <label class="block text-sm text-gray-500">Divisi</label>
                            <p class="font-medium">{{ $user->divisi }}</p>
                        </div>
                        @endif
                        @if($user->jabatan)
                        <div>
                            <label class="block text-sm text-gray-500">Jabatan</label>
                            <p class="font-medium">{{ $user->jabatan }}</p>
                        </div>
                        @endif
                        @if($user->periode)
                        <div>
                            <label class="block text-sm text-gray-500">Periode</label>
                            <p class="font-medium">{{ $user->periode }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="pt-6 mt-8 border-t">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Informasi Akun</h3>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="p-4 rounded-lg bg-gray-50">
                        <label class="block text-sm text-gray-500">Tanggal Bergabung</label>
                        <p class="font-medium">{{ $user->created_at->format('d F Y') }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50">
                        <label class="block text-sm text-gray-500">Terakhir Login</label>
                        <p class="font-medium">{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Belum login' }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-gray-50">
                        <label class="block text-sm text-gray-500">Status Akun</label>
                        <p class="font-medium">
                            <span class="px-2 py-1 rounded text-sm {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Footer -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    ID: {{ $user->id }} • Terakhir update: {{ $user->updated_at->diffForHumans() }}
                </div>
                <div class="flex space-x-3">
                    <!-- Toggle Status Form -->
                    <form action="{{ route('anggota.toggle-status', $user) }}"
                          method="POST"
                          onsubmit="return confirm('Apakah yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} anggota ini?')">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 rounded-lg font-medium
                                    {{ $user->is_active ? 'bg-orange-100 text-orange-800 hover:bg-orange-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                            <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }} mr-2"></i>
                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <div class="flex space-x-3">
                        <a href="{{ route('anggota.edit', $user) }}"
                        class="inline-flex items-center px-4 py-2 space-x-2 text-white bg-yellow-600 rounded-lg hover:bg-yellow-700">
                            <i class="fas fa-edit"></i>
                            <span>Edit</span>
                        </a>
                        <a href="{{ route('anggota.index') }}"
                        class="inline-flex items-center px-4 py-2 space-x-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
