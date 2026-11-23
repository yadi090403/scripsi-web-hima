@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
<div class="max-w-4xl mx-auto fade-in">
    <!-- Header Profile -->
    <div class="p-6 mb-6 bg-white shadow backup-card rounded-xl">
        <div class="flex flex-col items-center space-y-6 md:flex-row md:items-start md:space-y-0 md:space-x-6">
            <!-- Profile Photo -->
            <div class="relative">
                <div class="w-32 h-32 overflow-hidden border-4 border-white rounded-full shadow-lg">
                    @if(auth()->user()->foto_profil)
                        <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}"
                             alt="{{ auth()->user()->name }}"
                             class="object-cover w-full h-full">
                    @else
                        <div class="flex items-center justify-center w-full h-full bg-gradient-to-r from-blue-500 to-purple-600">
                            <span class="text-4xl font-bold text-white">
                                {{ auth()->user()->getInitials() }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Upload Photo Form -->
                <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data"
                      class="mt-4 text-center">
                    @csrf
                    <div class="relative">
                        <input type="file" name="photo" id="photo"
                               class="hidden"
                               onchange="this.form.submit()"
                               accept="image/*">
                        <label for="photo"
                               class="inline-flex items-center px-4 py-2 space-x-2 text-sm text-white transition rounded-lg cursor-pointer bg-hima-blue hover:bg-blue-800">
                            <i class="fas fa-camera"></i>
                            <span>Ubah Foto</span>
                        </label>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Max: 2MB (JPG, PNG, GIF)</p>
                </form>
            </div>

            <!-- User Info -->
            <div class="flex-1 text-center md:text-left">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ auth()->user()->name }}</h1>
                        <p class="text-gray-600">{{ auth()->user()->email }}</p>
                        <div class="flex items-center justify-center mt-2 space-x-3 md:justify-start">
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ auth()->user()->getRoleBadgeClass() }}">
                                {{ auth()->user()->getRoleDisplay() }}
                            </span>
                            <span class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                {{ auth()->user()->getStatusDisplay() }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center space-x-2 font-medium text-hima-blue hover:text-blue-800">
                        <i class="fas fa-edit"></i>
                        <span>Edit Profile</span>
                    </a>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-2 gap-4 mt-6 md:grid-cols-4">
                    <div class="p-3 text-center rounded-lg bg-gray-50">
                        <p class="text-sm text-gray-500">NIM</p>
                        <p class="font-bold text-gray-800">{{ auth()->user()->nim }}</p>
                    </div>
                    <div class="p-3 text-center rounded-lg bg-gray-50">
                        <p class="text-sm text-gray-500">Fakultas</p>
                        <p class="font-bold text-gray-800">{{ auth()->user()->fakultas ?? '-' }}</p>
                    </div>
                    <div class="p-3 text-center rounded-lg bg-gray-50">
                        <p class="text-sm text-gray-500">Jurusan</p>
                        <p class="font-bold text-gray-800">{{ auth()->user()->jurusan ?? '-' }}</p>
                    </div>
                    <div class="p-3 text-center rounded-lg bg-gray-50">
                        <p class="text-sm text-gray-500">Angkatan</p>
                        <p class="font-bold text-gray-800">{{ auth()->user()->angkatan ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Columns Layout -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Personal Information -->
        <div class="backup-card lg:col-span-2">
            <div class="p-6 bg-white shadow rounded-xl">
                <h2 class="pb-3 mb-2 text-xl font-bold text-gray-800 border-b">Informasi Pribadi</h2>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <p class="mt-1 text-gray-900">{{ auth()->user()->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-gray-900">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                            <p class="mt-1 text-gray-900">{{ auth()->user()->no_hp ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <p class="mt-1">
                                <span class="px-2 py-1 rounded text-sm {{ auth()->user()->getRoleBadgeClass() }}">
                                    {{ auth()->user()->getRoleDisplay() }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <p class="mt-1 text-gray-900">{{ auth()->user()->alamat ?? 'Belum diisi' }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fakultas</label>
                            <p class="mt-1 text-gray-900">{{ auth()->user()->fakultas ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jurusan</label>
                            <p class="mt-1 text-gray-900">{{ auth()->user()->jurusan ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Angkatan</label>
                            <p class="mt-1 text-gray-900">{{ auth()->user()->angkatan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6 mt-8 border-t">
                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center space-x-2 bg-hima-blue text-white px-5 py-2.5 rounded-lg hover:bg-blue-800 transition">
                        <i class="fas fa-edit"></i>
                        <span>Edit Informasi Pribadi</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Account & Security -->
        <div class="space-y-6">
            <!-- Account Security -->
            <div class="p-6 bg-white shadow backup-card rounded-xl">
                <h2 class="mb-4 text-xl font-bold text-gray-800">Keamanan Akun</h2>

                <div class="space-y-4">
                    <div class="p-3 rounded-lg bg-blue-50">
                        <p class="text-sm text-gray-700">
                            <i class="mr-2 text-blue-500 fas fa-shield-alt"></i>
                            Terakhir login:
                            <span class="font-medium">
                                {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->diffForHumans() : 'Belum ada' }}
                            </span>
                        </p>
                    </div>

                    <!-- Change Password Form -->
                    <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                        @csrf
                        <h3 class="font-medium text-gray-800">Ubah Password</h3>

                        <div>
                            <label for="current_password" class="block mb-1 text-sm font-medium text-gray-700">
                                Password Saat Ini
                            </label>
                            <input type="password" id="current_password" name="current_password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block mb-1 text-sm font-medium text-gray-700">
                                Password Baru
                            </label>
                            <input type="password" id="password" name="password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block mb-1 text-sm font-medium text-gray-700">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>

                        <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 rounded-lg transition">
                            <i class="mr-2 fas fa-key"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Account Status -->
            <div class="p-6 bg-white shadow backup-card rounded-xl">
                <h2 class="mb-4 text-xl font-bold text-gray-800">Status Akun</h2>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">Status Akun</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ auth()->user()->getStatusBadgeClass() }}">
                            {{ auth()->user()->getStatusDisplay() }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">Tanggal Bergabung</span>
                        <span class="font-medium">{{ auth()->user()->created_at->format('d M Y') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">Member ID</span>
                        <span class="font-mono font-medium">{{ auth()->user()->nim }}</span>
                    </div>
                </div>

                <div class="pt-4 mt-6 border-t">
                    <button class="flex items-center justify-center w-full py-2 space-x-2 font-medium text-red-600 hover:text-red-800">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Minta Penonaktifan Akun</span>
                    </button>
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

<!-- JavaScript untuk Preview Photo -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const photoInput = document.getElementById('photo');
        const photoPreview = document.querySelector('.w-32.h-32 img');
        const photoInitials = document.querySelector('.w-32.h-32 span');

        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        // Show preview
                        if (photoPreview) {
                            photoPreview.src = e.target.result;
                        }
                        // Hide initials
                        if (photoInitials) {
                            photoInitials.style.display = 'none';
                        }
                    }

                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endsection
