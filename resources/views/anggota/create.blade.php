@extends('layouts.app')

@section('title', 'Tambah Anggota Baru')

@section('content')
<div class="max-w-4xl mx-auto fade-in">

    <div class="backup-card">
        <div style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); padding: 20px; border-top-left-radius:1rem; border-top-right-radius:1rem; border-bottom: 1px solid #f0f0f0;">
            <h2 style="color: white; font-size: 1.2rem; font-weight: 600; margin: 0;">Data Peribadi</h2>
        </div>
        <!-- Form -->
        <div class="p-6 bg-white shadow rounded-xl">
            <form action="{{ route('anggota.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Personal Information -->
                <div class="mb-8">

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
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
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
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
                            <input type="text" id="nim" name="nim" value="{{ old('nim') }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            @error('nim')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-700">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            @error('password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="jenis_kelamin" class="block mb-2 text-sm font-medium text-gray-700">
                                Jenis Kelamin
                            </label>
                            <select id="jenis_kelamin" name="jenis_kelamin"
                                    class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="tanggal_lahir" class="block mb-2 text-sm font-medium text-gray-700">
                                Tanggal Lahir
                            </label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('tanggal_lahir')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-700">
                                Nomor HP
                            </label>
                            <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp') }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="081234567890">
                            @error('no_hp')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Religion -->
                        <div>
                            <label for="agama" class="block mb-2 text-sm font-medium text-gray-700">
                                Agama
                            </label>
                            <select id="agama" name="agama"
                                    class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Agama</option>
                                <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                            @error('agama')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Photo -->
                        <div>
                            <label for="photo" class="block mb-2 text-sm font-medium text-gray-700">
                                Foto Profil
                            </label>
                            <input type="file" id="photo" name="photo"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                accept="image/*">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF (Max: 2MB)</p>
                            @error('photo')
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
                                <option value="Fakultas Teknik" {{ old('fakultas') == 'Fakultas Teknik' ? 'selected' : '' }}>Fakultas Teknik</option>
                                <option value="Fakultas Ilmu Komputer" {{ old('fakultas') == 'Fakultas Ilmu Komputer' ? 'selected' : '' }}>Fakultas Ilmu Komputer</option>
                                <option value="Fakultas Ekonomi" {{ old('fakultas') == 'Fakultas Ekonomi' ? 'selected' : '' }}>Fakultas Ekonomi</option>
                                <option value="Fakultas Hukum" {{ old('fakultas') == 'Fakultas Hukum' ? 'selected' : '' }}>Fakultas Hukum</option>
                                <option value="Fakultas Kedokteran" {{ old('fakultas') == 'Fakultas Kedokteran' ? 'selected' : '' }}>Fakultas Kedokteran</option>
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
                            <input type="text" id="jurusan" name="jurusan" value="{{ old('jurusan') }}"
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
                            <input type="text" id="angkatan" name="angkatan" value="{{ old('angkatan') }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="2023" maxlength="4">
                            @error('angkatan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- HIMA Information -->
                <div class="mb-8">
                    <h2 class="pb-2 mb-4 text-lg font-semibold text-gray-800 border-b">Informasi Keanggotaan HIMA</h2>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Role -->
                        <div>
                            <label for="role" class="block mb-2 text-sm font-medium text-gray-700">
                                Role
                            </label>
                            <select id="role" name="role"
                                    class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Role (Opsional)</option>
                                <option value="pengurus" {{ old('role') == 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                                <option value="anggota" {{ old('role') == 'anggota' ? 'selected' : '' }}>Anggota</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Divisi -->
                        <div>
                            <label for="divisi" class="block mb-2 text-sm font-medium text-gray-700">
                                Divisi
                            </label>
                            <input type="text" id="divisi" name="divisi" value="{{ old('divisi') }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Divisi Teknologi Informasi">
                            @error('divisi')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label for="jabatan" class="block mb-2 text-sm font-medium text-gray-700">
                                Jabatan
                            </label>
                            <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ketua Divisi">
                            @error('jabatan')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Periode -->
                        <div>
                            <label for="periode" class="block mb-2 text-sm font-medium text-gray-700">
                                Periode
                            </label>
                            <input type="text" id="periode" name="periode" value="{{ old('periode') }}"
                                class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="2024-2025">
                            @error('periode')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status & Address -->
                <div class="mb-8">
                    <h2 class="pb-2 mb-4 text-lg font-semibold text-gray-800 border-b">Status & Alamat</h2>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Status -->
                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Akun Aktif</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Jika dicentang, anggota bisa login ke sistem</p>
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="alamat" class="block mb-2 text-sm font-medium text-gray-700">
                                Alamat Lengkap
                            </label>
                            <textarea id="alamat" name="alamat" rows="3"
                                    class="w-full px-4 py-3 transition border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t">
                    <a href="{{ route('anggota.index') }}"
                    class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="mr-2 fas fa-arrow-left"></i> Kembali
                    </a>

                    <div class="flex space-x-3">
                        <button type="reset"
                                class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="mr-2 fas fa-redo"></i> Reset Form
                        </button>

                        <button type="submit"
                                class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="mr-2 fas fa-save"></i> Simpan Anggota
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

<!-- JavaScript for Form Validation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.createElement('div');
        passwordStrength.className = 'text-xs mt-1';
        passwordInput.parentNode.appendChild(passwordStrength);

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let message = '';
            let color = 'text-red-500';

            if (password.length >= 6) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            switch(strength) {
                case 0:
                    message = 'Password sangat lemah';
                    break;
                case 1:
                    message = 'Password lemah';
                    break;
                case 2:
                    message = 'Password cukup';
                    color = 'text-yellow-500';
                    break;
                case 3:
                    message = 'Password kuat';
                    color = 'text-green-500';
                    break;
                case 4:
                    message = 'Password sangat kuat';
                    color = 'text-green-600';
                    break;
            }

            passwordStrength.textContent = message;
            passwordStrength.className = `text-xs mt-1 ${color}`;
        });
    });
</script>
@endsection
