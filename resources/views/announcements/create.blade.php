@extends('layouts.app')

@section('title', 'Buat Pengumuman')

@section('content')
<div class="container max-w-3xl px-4 py-8 mx-auto fade-in">
    <!-- Form Card -->
    <div class="overflow-hidden bg-white border border-gray-100 shadow-xl backup-card rounded-2xl">
        <!-- Form Header -->
        <div class="px-8 py-6 bg-gradient-to-r from-blue-500 to-blue-600">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 mr-4 rounded-full bg-white/20 backdrop-blur-sm">
                    <i class="text-xl text-white fas fa-bullhorn"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Buat Pengumuman Baru</h1>
                    <p class="text-blue-100">Bagikan informasi penting kepada anggota organisasi</p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <div class="space-y-6">
                <!-- Title Input -->
                <div>
                    <label for="title" class="block mb-2 text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <i class="mr-2 text-blue-500 fas fa-heading"></i>
                            Judul Pengumuman
                        </span>
                    </label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title') }}"
                           placeholder="Masukkan judul pengumuman"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="mr-1 fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Content Input -->
                <div>
                    <label for="content" class="block mb-2 text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <i class="mr-2 text-blue-500 fas fa-align-left"></i>
                            Isi Pengumuman
                        </span>
                    </label>
                    <textarea id="content"
                              name="content"
                              rows="10"
                              placeholder="Tulis isi pengumuman di sini..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="mr-1 fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="mr-1 fas fa-info-circle"></i>
                        Gunakan format yang jelas dan mudah dipahami
                    </p>
                </div>

                <!-- Image Upload Input -->
                <div>
                    <label for="image" class="block mb-2 text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <i class="mr-2 text-blue-500 fas fa-image"></i>
                            Gambar Pengumuman (Opsional)
                        </span>
                    </label>
                    <div class="flex flex-col gap-3">
                        <div class="relative p-6 transition-colors border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:border-blue-500 bg-gray-50 hover:bg-blue-50">
                            <input type="file"
                                   id="image"
                                   name="image"
                                   accept="image/*"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   onchange="previewImage(event)">
                            <div class="text-center">
                                <i class="block mb-2 text-3xl text-gray-400 fas fa-cloud-upload-alt"></i>
                                <p class="font-medium text-gray-600">Klik atau seret gambar ke sini</p>
                                <p class="mt-1 text-sm text-gray-500">Maksimal 5MB (PNG, JPG, JPEG)</p>
                            </div>
                        </div>

                        <!-- Image Preview -->
                        <div id="image-preview-container" style="display: none;">
                            <div class="overflow-hidden border border-gray-300 rounded-lg">
                                <img id="image-preview" alt="Preview gambar" class="object-cover w-full h-auto max-h-64">
                            </div>
                            <button type="button" onclick="clearImage()" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                <i class="mr-1 fas fa-times"></i>
                                Hapus gambar
                            </button>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="mr-1 fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Preview Card -->
                <div class="p-5 border border-blue-200 bg-blue-50 rounded-xl">
                    <div class="flex items-center mb-3">
                        <i class="mr-2 text-blue-500 fas fa-eye"></i>
                        <h3 class="font-semibold text-blue-700">Pratinjau Pengumuman</h3>
                    </div>
                    <div id="preview-title" class="font-bold text-gray-800 text-lg mb-2 min-h-[28px]">
                        {{ old('title', 'Judul akan muncul di sini') }}
                    </div>
                    <div id="preview-content" class="text-gray-600 whitespace-pre-line min-h-[60px]">
                        {{ old('content', 'Isi pengumuman akan muncul di sini...') }}
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col items-center justify-between pt-6 border-t border-gray-200 sm:flex-row">
                    <div class="mb-4 sm:mb-0">
                        <div class="flex items-center text-gray-600">
                            <div class="flex items-center justify-center w-8 h-8 mr-3 bg-blue-100 rounded-full">
                                <i class="text-blue-500 fas fa-user"></i>
                            </div>
                            <div>
                                <p class="font-medium">{{ auth()->user()->name }}</p>
                                <p class="text-sm">Sebagai {{ ucfirst(auth()->user()->role) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('announcements.index') }}"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="mr-2 fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit"
                                class="flex items-center px-8 py-3 font-semibold text-white transition-all duration-300 rounded-lg shadow-lg bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 hover:shadow-xl">
                            <i class="mr-2 fas fa-paper-plane"></i>
                            Publikasikan Pengumuman
                        </button>
                    </div>
                </div>
            </div>
        </form>
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

<script>
    // Live preview untuk title dan content
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const contentInput = document.getElementById('content');
        const previewTitle = document.getElementById('preview-title');
        const previewContent = document.getElementById('preview-content');

        // Update preview title
        titleInput.addEventListener('input', function() {
            previewTitle.textContent = this.value || 'Judul akan muncul di sini';
        });

        // Update preview content
        contentInput.addEventListener('input', function() {
            previewContent.textContent = this.value || 'Isi pengumuman akan muncul di sini...';
        });
    });

    // Image preview functionality
    function previewImage(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('image-preview-container');
        const previewImg = document.getElementById('image-preview');

        if (file) {
            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran gambar tidak boleh lebih dari 5MB');
                event.target.value = '';
                previewContainer.style.display = 'none';
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar');
                event.target.value = '';
                previewContainer.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    }

    // Clear image
    function clearImage() {
        document.getElementById('image').value = '';
        document.getElementById('image-preview-container').style.display = 'none';
    }
</script>

<style>
    /* Smooth transitions */
    input, textarea {
        transition: all 0.2s ease;
    }

    /* Focus styles */
    input:focus, textarea:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>
@endsection
