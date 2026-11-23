@extends('layouts.app')

@section('title', 'Edit Posisi: ' . $position->position_name)

@section('content')
<div class="container max-w-3xl px-4 py-8 mx-auto fade-in">
    <!-- Form Card -->
    <div class="overflow-hidden bg-white border border-gray-100 shadow-xl backup-card rounded-2xl">
        <!-- Form Header -->
        <div class="px-8 py-6 bg-gradient-to-r from-blue-600 to-blue-700">
            <div class="flex items-center">
                <div class="flex items-center justify-center w-12 h-12 mr-4 rounded-full bg-white/20 backdrop-blur-sm">
                    <i class="text-xl text-white fas fa-edit"></i>
                </div>
                <div>
                    <h1 class="font-bold text-white text-1xl">Edit Posisi</h1>
                    <p class="text-yellow-100">Perbarui informasi posisi dalam struktur organisasi</p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form action="{{ route('organizational-structure.update', $position->id) }}" method="POST" class="p-8" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Position Name -->
                <div>
                    <label for="position_name" class="block mb-2 text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <i class="mr-2 text-blue-500 fas fa-id-badge"></i>
                            Nama Posisi
                        </span>
                    </label>
                    <input type="text"
                           id="position_name"
                           name="position_name"
                           value="{{ old('position_name', $position->position_name) }}"
                           placeholder="Contoh: Ketua Umum, Sekretaris, Koordinator Acara"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition-all @error('position_name') border-red-500 @enderror">
                    @error('position_name')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="mr-1 fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Position Level -->
                <div>
                    <label for="position_level" class="block mb-2 text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <i class="mr-2 text-blue-500 fas fa-layer-group"></i>
                            Level Posisi
                        </span>
                    </label>
                    <select id="position_level"
                            name="position_level"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition-all @error('position_level') border-red-500 @enderror">
                        <option value="">Pilih Level Posisi</option>
                        <option value="Ketua" {{ old('position_level', $position->position_level) == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                        <option value="Wakil Ketua" {{ old('position_level', $position->position_level) == 'Wakil Ketua' ? 'selected' : '' }}>Wakil Ketua</option>
                        <option value="Sekretaris" {{ old('position_level', $position->position_level) == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                        <option value="Bendahara" {{ old('position_level', $position->position_level) == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                        <option value="Koordinator" {{ old('position_level', $position->position_level) == 'Koordinator' ? 'selected' : '' }}>Koordinator</option>
                        <option value="Anggota" {{ old('position_level', $position->position_level) == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                        <option value="Staf" {{ old('position_level', $position->position_level) == 'Staf' ? 'selected' : '' }}>Staf</option>
                    </select>
                    @error('position_level')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="mr-1 fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block mb-2 text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <i class="mr-2 text-blue-500 fas fa-sort-numeric-up"></i>
                            Urutan Tampil
                        </span>
                    </label>
                    <input type="number"
                           id="order"
                           name="order"
                           value="{{ old('order', $position->order) }}"
                           min="0"
                           placeholder="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition-all @error('order') border-red-500 @enderror">
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="mr-1 fas fa-info-circle"></i>
                        Angka lebih kecil akan ditampilkan lebih awal
                    </p>
                </div>

                <!-- Parent Position -->
                <div>
                    <label for="parent_id" class="block mb-2 text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <i class="mr-2 text-blue-500 fas fa-sitemap"></i>
                            Atasan / Parent
                        </span>
                    </label>
                    <select id="parent_id"
                            name="parent_id"
                            class="w-full px-4 py-3 transition-all border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="">Tidak Ada (Posisi Utama)</option>
                        @foreach($parents as $parent)
                            @if($parent->id != $position->id) <!-- Hindari memilih diri sendiri -->
                            <option value="{{ $parent->id }}" {{ old('parent_id', $position->parent_id) == $parent->id ? 'selected' : '' }}>
                                {{ $parent->position_name }} ({{ $parent->position_level }})
                            </option>
                            @endif
                        @endforeach
                    </select>
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="mr-1 fas fa-info-circle"></i>
                        Kosongkan jika ini adalah posisi utama/top level
                    </p>
                </div>

                <!-- User Assignment -->
                <div class="p-5 border border-blue-200 bg-blue-50 rounded-xl">
                    <div class="flex items-center mb-4">
                        <i class="mr-2 text-blue-500 fas fa-user-check"></i>
                        <h3 class="font-semibold text-blue-700">Penanggung Jawab</h3>
                    </div>

                    <!-- Option 1: Select Existing User -->
                    <div class="mb-4">
                        <label for="user_id" class="block mb-2 text-sm font-medium text-gray-700">
                            Pilih dari Anggota
                        </label>
                        <select id="user_id"
                                name="user_id"
                                class="w-full px-4 py-3 transition-all border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Anggota (Opsional)</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $position->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Option 2: Custom Name -->
                    <div>
                        <label for="custom_name" class="block mb-2 text-sm font-medium text-gray-700">
                            Atau Masukkan Nama Kustom
                        </label>
                        <input type="text"
                               id="custom_name"
                               name="custom_name"
                               value="{{ old('custom_name', $position->custom_name) }}"
                               placeholder="Contoh: John Doe, Dr. Smith, dll."
                               class="w-full px-4 py-3 transition-all border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-2 text-sm text-gray-500">
                            <i class="mr-1 fas fa-info-circle"></i>
                            Gunakan jika penanggung jawab belum terdaftar sebagai anggota
                        </p>
                    </div>
                </div>

                <!-- Tambahkan setelah bagian "Custom Name" -->
                <div class="mt-6">
                    <label for="photo" class="block mb-2 text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <i class="mr-2 text-blue-500 fas fa-camera"></i>
                            Foto Profil
                        </span>
                    </label>

                    <!-- Photo Upload Area -->
                    <div class="mt-2">
                        <div class="flex items-center space-x-6">
                            <!-- Current Photo -->
                            <div class="relative">
                                <div id="photo-preview"
                                    class="w-32 h-32 overflow-hidden border-4 border-white rounded-full shadow-lg">
                                    <img id="photo-preview-image"
                                        src="{{ $position->photo_url }}"
                                        alt="{{ $position->display_name }}"
                                        class="object-cover w-full h-full">
                                    <div id="initials-placeholder" class="hidden w-full h-full bg-gradient-to-r {{ $position->level_color }} flex items-center justify-center">
                                        <span class="text-2xl font-bold text-white">{{ $position->initials }}</span>
                                    </div>
                                </div>
                                <div id="remove-photo"
                                    class="absolute flex items-center justify-center hidden w-6 h-6 text-white bg-red-500 rounded-full cursor-pointer -top-2 -right-2 hover:bg-red-600"
                                    title="Hapus foto">
                                    <i class="text-xs fas fa-times"></i>
                                </div>
                            </div>

                            <!-- Upload Button -->
                            <div class="flex-1">
                                <div class="p-6 text-center transition-colors border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:border-yellow-400"
                                    id="drop-area">
                                    <i class="mb-3 text-3xl text-gray-400 fas fa-cloud-upload-alt"></i>
                                    <p class="mb-2 text-gray-600">
                                        <span class="font-medium">Klik untuk upload</span> atau drag & drop
                                    </p>
                                    <p class="mb-4 text-sm text-gray-500">
                                        Format: JPG, PNG, GIF (Maks: 2MB)<br>
                                        <span class="text-blue-600">Foto saat ini akan diganti</span>
                                    </p>
                                    <input type="file"
                                        id="photo"
                                        name="photo"
                                        accept="image/jpeg,image/png,image/gif"
                                        class="hidden">
                                    <label for="photo"
                                        class="inline-flex items-center px-4 py-2 font-medium text-white transition-colors bg-blue-500 rounded-lg cursor-pointer hover:bg-blue-700">
                                        <i class="mr-2 fas fa-upload"></i>
                                        Ganti Foto
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden input for removing photo -->
                        <input type="hidden" name="remove_photo" id="remove_photo_input" value="0">

                        <!-- Error message for photo -->
                        <div id="photo-error" class="hidden mt-2 text-sm text-red-600">
                            <i class="mr-1 fas fa-exclamation-circle"></i>
                            <span id="photo-error-message"></span>
                        </div>

                        <!-- Current photo info -->
                        <p id="photo-info" class="mt-2 text-sm text-gray-500">
                            <i class="mr-1 fas fa-info-circle"></i>
                            @if($position->photo)
                                Foto khusus posisi ini
                            @elseif($position->user && $position->user->profile_photo)
                                Menggunakan foto profil {{ $position->user->name }}
                            @else
                                Belum ada foto
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block mb-2 text-sm font-semibold text-gray-700">
                        <span class="flex items-center">
                            <i class="mr-2 text-blue-500 fas fa-file-alt"></i>
                            Deskripsi Tugas
                        </span>
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              placeholder="Deskripsi tugas dan tanggung jawab posisi ini..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none transition-all @error('description') border-red-500 @enderror">{{ old('description', $position->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">
                            <i class="mr-1 fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Information Panel -->
                <div class="p-5 border border-gray-200 bg-gray-50 rounded-xl">
                    <div class="flex items-start">
                        <i class="mt-1 mr-3 text-gray-500 fas fa-info-circle"></i>
                        <div>
                            <h4 class="mb-2 font-semibold text-gray-700">Informasi Posisi</h4>
                            <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                                <div>
                                    <p class="text-gray-600">ID Posisi:</p>
                                    <p class="font-medium">{{ $position->id }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Dibuat Pada:</p>
                                    <p class="font-medium">{{ $position->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Terakhir Diubah:</p>
                                    <p class="font-medium">{{ $position->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Status:</p>
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                        <i class="mr-1 fas fa-check-circle"></i>
                                        Aktif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Card -->
                <div class="p-5 border border-blue-200 bg-blue-50 rounded-xl">
                    <div class="flex items-center mb-3">
                        <i class="mr-2 text-blue-500 fas fa-eye"></i>
                        <h3 class="font-semibold text-blue-700">Pratinjau Perubahan</h3>
                    </div>
                    <div id="preview-card" class="p-4 bg-white border border-blue-300 rounded-lg">
                        <div class="flex items-center mb-3">
                            <div id="preview-icon" class="w-10 h-10 rounded-full bg-gradient-to-r {{ $position->level_color }} flex items-center justify-center mr-3">
                                <i class="text-white fas fa-user"></i>
                            </div>
                            <div>
                                <h4 id="preview-position" class="text-lg font-bold text-gray-800">{{ $position->position_name }}</h4>
                                <p id="preview-name" class="text-sm text-gray-600">{{ $position->display_name }}</p>
                            </div>
                        </div>
                        <p id="preview-description" class="text-sm text-gray-500">{{ $position->description ?: 'Deskripsi akan muncul di sini...' }}</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col items-center justify-between pt-6 border-t border-gray-200 sm:flex-row">
                    <div class="mb-4 sm:mb-0">
                        <a href="{{ route('organizational-structure.index') }}"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="mr-2 fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('organizational-structure.index') }}"
                           class="flex items-center px-6 py-3 font-semibold text-gray-700 transition-colors bg-gray-100 rounded-lg hover:bg-gray-200">
                            <i class="mr-2 fas fa-times"></i>
                            Batal
                        </a>
                        <button type="submit"
                                class="flex items-center px-8 py-3 font-semibold text-white transition-all duration-300 rounded-lg shadow-lg bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 hover:shadow-xl">
                            <i class="mr-2 fas fa-save"></i>
                            Simpan Perubahan
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
    document.addEventListener('DOMContentLoaded', function() {
        const positionNameInput = document.getElementById('position_name');
        const positionLevelSelect = document.getElementById('position_level');
        const customNameInput = document.getElementById('custom_name');
        const userIdSelect = document.getElementById('user_id');
        const descriptionInput = document.getElementById('description');

        const previewPosition = document.getElementById('preview-position');
        const previewName = document.getElementById('preview-name');
        const previewDescription = document.getElementById('preview-description');
        const previewIcon = document.getElementById('preview-icon');

        // Warna berdasarkan level
        const levelColors = {
            'Ketua': 'from-red-500 to-red-600',
            'Wakil Ketua': 'from-orange-500 to-orange-600',
            'Sekretaris': 'from-blue-500 to-blue-600',
            'Bendahara': 'from-green-500 to-green-600',
            'Koordinator': 'from-purple-500 to-purple-600',
            'Anggota': 'from-gray-500 to-gray-600',
            'Staf': 'from-indigo-500 to-indigo-600'
        };

        // Update preview position name
        positionNameInput.addEventListener('input', function() {
            previewPosition.textContent = this.value || 'Nama Posisi';
        });

        // Update preview level color
        positionLevelSelect.addEventListener('change', function() {
            const color = levelColors[this.value] || 'from-yellow-500 to-amber-600';
            previewIcon.className = `w-10 h-10 rounded-full bg-gradient-to-r ${color} flex items-center justify-center mr-3`;
        });

        // Update preview name
        function updatePreviewName() {
            if (userIdSelect.value) {
                const selectedOption = userIdSelect.options[userIdSelect.selectedIndex];
                previewName.textContent = selectedOption.text.split('(')[0].trim();
            } else if (customNameInput.value) {
                previewName.textContent = customNameInput.value;
            } else {
                previewName.textContent = 'Belum ditentukan';
            }
        }

        userIdSelect.addEventListener('change', updatePreviewName);
        customNameInput.addEventListener('input', updatePreviewName);

        // Update preview description
        descriptionInput.addEventListener('input', function() {
            previewDescription.textContent = this.value || 'Deskripsi akan muncul di sini...';
        });

        // Auto fill custom name when user is selected
        userIdSelect.addEventListener('change', function() {
            if (this.value && !customNameInput.value) {
                const selectedOption = this.options[this.selectedIndex];
                const userName = selectedOption.text.split('(')[0].trim();
                customNameInput.value = userName;
                updatePreviewName();
            }
        });

        // Initialize preview with current data
        updatePreviewName();
    });

// ========== PHOTO UPLOAD FUNCTIONS ==========
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.getElementById('photo');
    const dropArea = document.getElementById('drop-area');
    const photoPreviewImage = document.getElementById('photo-preview-image');
    const initialsPlaceholder = document.getElementById('initials-placeholder');
    const removePhotoBtn = document.getElementById('remove-photo');
    const removePhotoInput = document.getElementById('remove_photo_input');
    const photoError = document.getElementById('photo-error');
    const photoErrorMessage = document.getElementById('photo-error-message');

    function showPhotoError(message) {
        photoErrorMessage.textContent = message;
        photoError.classList.remove('hidden');
    }

    function hidePhotoError() {
        photoError.classList.add('hidden');
    }

    function validateFile(file) {
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!allowedTypes.includes(file.type)) {
            showPhotoError('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
            return false;
        }

        if (file.size > maxSize) {
            showPhotoError('Ukuran file terlalu besar. Maksimal 2MB.');
            return false;
        }

        hidePhotoError();
        return true;
    }

    function previewPhoto(file) {
        if (!validateFile(file)) {
            photoInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            photoPreviewImage.src = e.target.result;
            photoPreviewImage.classList.remove('hidden');
            initialsPlaceholder.classList.add('hidden');
            removePhotoBtn.classList.remove('hidden');
            removePhotoInput.value = '0';
        };
        reader.readAsDataURL(file);
    }

    // Photo input change event
    photoInput.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            previewPhoto(this.files[0]);
        }
    });

    // Drag and drop handlers
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

    function highlight(e) {
        dropArea.classList.add('border-yellow-500', 'bg-yellow-50');
    }

    function unhighlight(e) {
        dropArea.classList.remove('border-yellow-500', 'bg-yellow-50');
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files && files[0]) {
            photoInput.files = files;
            previewPhoto(files[0]);
        }
    }

    // Remove photo button
    removePhotoBtn.addEventListener('click', function() {
        photoInput.value = '';
        photoPreviewImage.src = '';
        photoPreviewImage.classList.add('hidden');
        initialsPlaceholder.classList.remove('hidden');
        removePhotoBtn.classList.add('hidden');
        removePhotoInput.value = '1';
        hidePhotoError();
    });

    // Update initials placeholder
    function updateInitials() {
        const nameInput = document.getElementById('custom_name');
        const userIdSelect = document.getElementById('user_id');

        if (initialsPlaceholder && initialsPlaceholder.classList.contains('hidden')) {
            // Jika sedang menampilkan preview foto, jangan update
            return;
        }

        let name = '';

        if (userIdSelect && userIdSelect.value) {
            const selectedOption = userIdSelect.options[userIdSelect.selectedIndex];
            name = selectedOption.text.split('(')[0].trim();
        } else if (nameInput && nameInput.value) {
            name = nameInput.value;
        }

        if (name) {
            const words = name.split(' ');
            let initials = '';

            words.forEach(word => {
                if (word[0]) initials += word[0].toUpperCase();
            });

            initialsPlaceholder.textContent = initials.substring(0, 2);
        } else {
            initialsPlaceholder.textContent = '??';
        }
    }

    // Listen to name changes
    const customNameInput = document.getElementById('custom_name');
    const userIdSelect = document.getElementById('user_id');

    if (customNameInput) {
        customNameInput.addEventListener('input', updateInitials);
    }

    if (userIdSelect) {
        userIdSelect.addEventListener('change', updateInitials);
    }

    // Initialize initials
    updateInitials();
});


</script>
@endsection
