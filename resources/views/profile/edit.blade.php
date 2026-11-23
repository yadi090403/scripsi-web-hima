@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-3xl mx-auto fade-in">
    <!-- Header -->


    <div class="backup-card">
        <div style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); padding: 20px; border-top-left-radius:1rem; border-top-right-radius:1rem; border-bottom: 1px solid #f0f0f0;">
            <h2 style="color: white; font-size: 1.2rem; font-weight: 600; margin: 0;">Informasi Pribadi</h2>
        </div>
        <!-- Edit Form -->
        <div class="p-6 bg-white shadow rounded-xl">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                @if($errors->any())
                <div class="p-4 mb-6 border-l-4 border-red-500 bg-red-50">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="mt-1 text-red-500 fas fa-exclamation-circle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">Terdapat kesalahan dalam pengisian form.</p>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Personal Information -->
                <div class="mb-8">

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            @error('name')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            @error('email')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIM -->
                        <div>
                            <label for="nim" class="block mb-2 text-sm font-medium text-gray-700">
                                NIM <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nim" name="nim" value="{{ old('nim', $user->nim) }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            @error('nim')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-700">
                                Nomor HP
                            </label>
                            <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="081234567890">
                            @error('no_hp')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="mb-8">
                    <h2 class="pb-2 mb-4 text-lg font-semibold text-gray-800 border-b">Informasi Akademik</h2>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Fakultas -->
                        <div>
                            <label for="fakultas" class="block mb-2 text-sm font-medium text-gray-700">
                                Fakultas
                            </label>
                            <select id="fakultas" name="fakultas"
                                    class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Fakultas</option>
                                <option value="Fakultas Teknik" {{ old('fakultas', $user->fakultas) == 'Fakultas Teknik' ? 'selected' : '' }}>Fakultas Teknik</option>
                                <option value="Fakultas Ilmu Komputer" {{ old('fakultas', $user->fakultas) == 'Fakultas Ilmu Komputer' ? 'selected' : '' }}>Fakultas Ilmu Komputer</option>
                                <option value="Fakultas Ekonomi" {{ old('fakultas', $user->fakultas) == 'Fakultas Ekonomi' ? 'selected' : '' }}>Fakultas Ekonomi</option>
                                <option value="Fakultas Hukum" {{ old('fakultas', $user->fakultas) == 'Fakultas Hukum' ? 'selected' : '' }}>Fakultas Hukum</option>
                                <option value="Fakultas Kedokteran" {{ old('fakultas', $user->fakultas) == 'Fakultas Kedokteran' ? 'selected' : '' }}>Fakultas Kedokteran</option>
                            </select>
                            @error('fakultas')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jurusan -->
                        <div>
                            <label for="jurusan" class="block mb-2 text-sm font-medium text-gray-700">
                                Jurusan
                            </label>
                            <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan', $user->jurusan) }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Teknik Informatika">
                            @error('jurusan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Angkatan -->
                        <div>
                            <label for="angkatan" class="block mb-2 text-sm font-medium text-gray-700">
                                Angkatan
                            </label>
                            <input type="text" id="angkatan" name="angkatan" value="{{ old('angkatan', $user->angkatan) }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="2023" maxlength="4">
                            @error('angkatan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="mb-8">
                    <h2 class="pb-2 mb-4 text-lg font-semibold text-gray-800 border-b">Alamat</h2>

                    <div>
                        <label for="alamat" class="block mb-2 text-sm font-medium text-gray-700">
                            Alamat Lengkap
                        </label>
                        <textarea id="alamat" name="alamat" rows="3"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('alamat', $user->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t">
                    <a href="{{ route('profile') }}"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="mr-2 fas fa-arrow-left"></i> Kembali
                    </a>

                    <div class="flex space-x-3">
                        <button type="reset"
                                class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="mr-2 fas fa-redo"></i> Reset
                        </button>

                        <button type="submit"
                                class="px-5 py-2.5 bg-hima-blue text-white rounded-lg hover:bg-blue-800 transition">
                            <i class="mr-2 fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
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
