@extends('layouts.app')

@section('title', 'Upload Dokumen')

@section('content')
<div class="max-w-4xl mx-auto backup-card fade-in">
    <div style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); padding: 20px; border-top-left-radius:1rem; border-top-right-radius:1rem; border-bottom: 1px solid #f0f0f0;">
        <h2 style="color: white; font-size: 1.2rem; font-weight: 600; margin: 0;">Upload Dokumen Baru</h2>
        <p class="text-gray-300">Upload dokumen untuk disimpan di sistem HIMA NJ</p>
    </div>
    <div class="p-6 shadow-sm bg-white- rounded-xl">
        <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- File Upload -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Pilih File *</label>
                <div class="p-8 text-center transition border-2 border-gray-300 border-dashed cursor-pointer rounded-xl hover:border-blue-400"
                     onclick="document.getElementById('fileInput').click()">
                    <i class="mb-4 text-4xl text-gray-400 fas fa-cloud-upload-alt"></i>
                    <p class="text-lg font-medium text-gray-700">Drop file di sini atau klik untuk memilih</p>
                    <p class="mt-2 text-sm text-gray-500">Maksimal ukuran file: 10MB</p>
                    <p class="mt-1 text-xs text-gray-400">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, ZIP</p>
                    <input type="file" name="dokumen" id="fileInput" class="hidden" required>
                </div>
                <div id="fileInfo" class="hidden p-3 mt-3 rounded-lg bg-blue-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="mr-3 text-blue-600 fas fa-file"></i>
                            <div>
                                <p id="fileName" class="font-medium text-gray-800"></p>
                                <p id="fileSize" class="text-sm text-gray-600"></p>
                            </div>
                        </div>
                        <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @error('dokumen')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Details -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Nama File *</label>
                    <input type="text" name="nama_file" value="{{ old('nama_file') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Contoh: Proposal Kegiatan HUT HIMA NJ">
                    @error('nama_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Kategori *</label>
                    <select name="kategori" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriList as $key => $value)
                            <option value="{{ $key }}" {{ old('kategori') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Deskripsi singkat tentang dokumen ini...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Settings -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="space-y-4">
                    <h3 class="text-sm font-medium text-gray-700">Pengaturan</h3>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}
                               id="is_public" class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_public" class="ml-2 text-sm text-gray-700">
                            Dokumen publik (dapat diakses semua anggota)
                        </label>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Password (opsional)</label>
                        <input type="password" name="password" value="{{ old('password') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Password untuk proteksi dokumen">
                        <p class="mt-1 text-xs text-gray-500">Dokumen akan meminta password saat di-download</p>
                    </div>
                </div>

                <div class="p-4 rounded-lg bg-blue-50">
                    <h3 class="mb-2 text-sm font-medium text-blue-800">Informasi</h3>
                    <ul class="space-y-1 text-sm text-blue-700">
                        <li class="flex items-start">
                            <i class="mt-1 mr-2 fas fa-info-circle"></i>
                            <span>File akan disimpan secara aman di server</span>
                        </li>
                        <li class="flex items-start">
                            <i class="mt-1 mr-2 fas fa-shield-alt"></i>
                            <span>Dokumen private hanya bisa diakses oleh uploader dan admin</span>
                        </li>
                        <li class="flex items-start">
                            <i class="mt-1 mr-2 fas fa-history"></i>
                            <span>Riwayat download akan dicatat secara otomatis</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end pt-6 space-x-4 border-t border-gray-200">
                <a href="{{ route('dokumen.index') }}" class="px-6 py-3 text-gray-700 transition border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 font-medium text-white transition bg-blue-500 rounded-lg hover:bg-blue-600">
                    <i class="mr-2 fas fa-upload"></i>Upload Dokumen
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .border-dashed:hover {
        border-color: #3b82f6;
        background-color: #f8fafc;
    }

    /* Animasi untuk kartu */
    .backup-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        position: relative;
        overflow: hidden;
        /* background: white; */
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    .backup-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
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
@endpush

@push('scripts')
<script>
    const fileInput = document.getElementById('fileInput');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    fileInput.addEventListener('change', function(e) {
        if (this.files.length > 0) {
            const file = this.files[0];
            fileName.textContent = file.name;

            // Format file size
            const size = file.size;
            if (size < 1024) {
                fileSize.textContent = size + ' bytes';
            } else if (size < 1048576) {
                fileSize.textContent = (size / 1024).toFixed(2) + ' KB';
            } else {
                fileSize.textContent = (size / 1048576).toFixed(2) + ' MB';
            }

            fileInfo.classList.remove('hidden');

            // Auto-fill nama file if empty
            const namaFileInput = document.querySelector('input[name="nama_file"]');
            if (!namaFileInput.value) {
                // Remove extension from filename
                const nameWithoutExt = file.name.replace(/\.[^/.]+$/, "");
                namaFileInput.value = nameWithoutExt;
            }
        }
    });

    function clearFile() {
        fileInput.value = '';
        fileInfo.classList.add('hidden');
    }

    // Drag and drop
    const dropArea = document.querySelector('.border-dashed');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropArea.style.borderColor = '#3b82f6';
        dropArea.style.backgroundColor = '#f0f9ff';
    }

    function unhighlight() {
        dropArea.style.borderColor = '';
        dropArea.style.backgroundColor = '';
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;

        // Trigger change event
        const event = new Event('change');
        fileInput.dispatchEvent(event);
    }
</script>
@endpush
