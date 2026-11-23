@extends('layouts.app')

@section('title', 'Manajemen Dokumen')

@section('content')
<div class="mb-6 fade-in">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Dokumen</h1>
            <p class="text-gray-600">Upload, kelola, dan download dokumen organisasi</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('dokumen.stats') }}" class="px-4 py-2 text-white transition bg-purple-500 rounded-lg backup-card hover:bg-purple-600">
                <i class="mr-2 fas fa-chart-bar"></i>Statistik
            </a>
            <a href="{{ route('dokumen.create') }}" class="px-4 py-2 text-white transition bg-blue-500 rounded-lg backup-card hover:bg-blue-600">
                <i class="mr-2 fas fa-upload"></i>Upload Dokumen
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="p-4 mb-6 bg-white shadow-sm fade-in rounded-xl">
    <div class="kegiatan-card-header-icon">
    <div style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); padding: 20px; border-top-left-radius:1rem; border-top-right-radius:1rem; border-bottom: 1px solid #f0f0f0;">
        <h2 style="color: white; font-size: 1.2rem; font-weight: 600; margin: 0;">Data Dokumen</h2>
    </div>
    <form action="{{ route('dokumen.index') }}" method="GET" class="flex flex-col gap-4 md:flex-row">
        <div class="flex-1">
            <input type="text" name="search" placeholder="Cari dokumen..."
                   value="{{ request('search') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg md:w-48 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kategori)
                    <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>
                        {{ $kategori }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <button type="submit" class="w-full px-6 py-2 text-white transition bg-blue-500 rounded-lg md:w-auto hover:bg-blue-600">
                <i class="mr-2 fas fa-search"></i>Cari
            </button>
        </div>
    </form>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-4">
    <div class="p-6 bg-white shadow-sm backup-card rounded-xl">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 mr-4 bg-blue-100 rounded-lg">
                <i class="text-xl text-blue-600 fas fa-file"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Dokumen</p>
                <p class="text-2xl font-bold text-gray-800">{{ $dokumens->total() }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white shadow-sm backup-card rounded-xl">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 mr-4 bg-green-100 rounded-lg">
                <i class="text-xl text-green-600 fas fa-download"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Download</p>
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Dokumen::sum('download_count') }}</p>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white shadow-sm backup-card rounded-xl">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 mr-4 bg-purple-100 rounded-lg">
                <i class="text-xl text-purple-600 fas fa-hdd"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">Penyimpanan</p>
                @php
                    $totalSize = \App\Models\Dokumen::sum('ukuran') / 1024;
                @endphp
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalSize, 2) }} MB</p>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white shadow-sm backup-card rounded-xl">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-12 h-12 mr-4 bg-orange-100 rounded-lg">
                <i class="text-xl text-orange-600 fas fa-file-pdf"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">File PDF</p>
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Dokumen::where('tipe', 'pdf')->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Dokumen List -->
<div class="overflow-hidden bg-white shadow-sm backup-card rounded-xl">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Dokumen</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Info</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Uploader</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($dokumens as $dokumen)
                <tr class="transition hover:bg-gray-200">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg {{ $dokumen->tipe == 'pdf' ? 'bg-red-100' : ($dokumen->tipe == 'doc' ? 'bg-blue-100' : ($dokumen->tipe == 'xls' ? 'bg-green-100' : 'bg-gray-100')) }} flex items-center justify-center mr-3">
                                <i class="{{ $dokumen->icon }} {{ $dokumen->tipe == 'pdf' ? 'text-red-600' : ($dokumen->tipe == 'doc' ? 'text-blue-600' : ($dokumen->tipe == 'xls' ? 'text-green-600' : 'text-gray-600')) }}"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $dokumen->nama_file }}</p>
                                <p class="text-sm text-gray-500">{{ $dokumen->nama_asli }}</p>
                                @if(!$dokumen->is_public)
                                    <span class="inline-block px-2 py-1 mt-1 text-xs text-yellow-800 bg-yellow-100 rounded-full">
                                        <i class="mr-1 fas fa-lock"></i>Private
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">
                            {{ $dokumen->kategori }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="space-y-1">
                            <p class="text-sm text-gray-500">
                                <i class="mr-2 fas fa-weight-hanging"></i>{{ $dokumen->ukuran_format }}
                            </p>
                            <p class="text-sm text-gray-500">
                                <i class="mr-2 fas fa-download"></i>{{ $dokumen->download_count }}x
                            </p>
                            <p class="text-sm text-gray-500">{{ $dokumen->uploaded_at }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-8 h-8 mr-2 font-bold text-white bg-blue-600 rounded-full">
                                {{ substr($dokumen->uploader->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $dokumen->uploader->name }}</p>
                                <p class="text-xs text-gray-500">{{ $dokumen->uploader->role }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('dokumen.download', $dokumen->id) }}"
                               class="px-3 py-1 text-blue-600 transition rounded hover:text-blue-800 hover:bg-blue-50">
                                <i class="fas fa-download"></i>
                            </a>

                            @if($dokumen->tipe == 'pdf')
                            <a href="{{ route('dokumen.preview', $dokumen->id) }}" target="_blank"
                               class="px-3 py-1 text-purple-600 transition rounded hover:text-purple-800 hover:bg-purple-50">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif

                            @if(auth()->id() == $dokumen->uploaded_by || auth()->user()->isAdmin())
                            <form action="{{ route('dokumen.destroy', $dokumen->id) }}" method="POST"
                                  class="inline" onsubmit="return confirm('Hapus dokumen ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-red-600 transition rounded hover:text-red-800 hover:bg-red-50">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="mb-4">
                            <i class="text-4xl text-gray-300 fas fa-folder-open"></i>
                        </div>
                        <p class="text-lg font-medium">Belum ada dokumen</p>
                        <p class="mt-2">Upload dokumen pertama Anda!</p>
                        <a href="{{ route('dokumen.create') }}" class="inline-block px-6 py-2 mt-4 text-white transition bg-blue-500 rounded-lg hover:bg-blue-600">
                            <i class="mr-2 fas fa-upload"></i>Upload Dokumen
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($dokumens->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $dokumens->links() }}
    </div>
    @endif
</div>

<!-- Quick Upload -->
<div class="p-6 mt-8 bg-white shadow-sm rounded-xl">
    <h3 class="mb-4 text-lg font-semibold text-gray-800">Upload Cepat</h3>
    <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Nama File</label>
                <input type="text" name="nama_file" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Contoh: Proposal Kegiatan HUT">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                <select name="kategori" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Kategori</option>
                    <option value="Proposal">Proposal</option>
                    <option value="Laporan">Laporan</option>
                    <option value="SK">Surat Keputusan</option>
                    <option value="Sertifikat">Sertifikat</option>
                    <option value="Materi">Materi Presentasi</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="deskripsi" rows="2"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Deskripsi singkat dokumen"></textarea>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700">Pilih File</label>
                <div class="p-4 text-center transition border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:border-blue-400"
                     onclick="document.getElementById('fileInput').click()">
                    <i class="mb-2 text-3xl text-gray-400 fas fa-cloud-upload-alt"></i>
                    <p class="text-sm text-gray-600">Klik untuk memilih file</p>
                    <p class="mt-1 text-xs text-gray-500">Maksimal 10MB (PDF, Word, Excel, dll)</p>
                    <input type="file" name="dokumen" id="fileInput" class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip" required>
                </div>
                <div id="fileName" class="mt-2 text-sm text-gray-600"></div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_public" value="1" checked class="text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Dokumen publik (bisa diakses semua anggota)</span>
                    </label>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Password (opsional)</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Password untuk proteksi dokumen">
                </div>

                <button type="submit" class="w-full py-3 font-medium text-white transition bg-blue-500 rounded-lg hover:bg-blue-600">
                    <i class="mr-2 fas fa-upload"></i>Upload Dokumen
                </button>
            </div>
        </div>
    </form>
</div>

    <style>
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
@endsection

@push('scripts')
<script>
    // Show selected file name
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const fileName = document.getElementById('fileName');
        if (this.files.length > 0) {
            fileName.textContent = 'File terpilih: ' + this.files[0].name;
            fileName.className = 'mt-2 text-sm text-green-600 font-medium';
        } else {
            fileName.textContent = '';
        }
    });

    // Drag and drop functionality
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
        dropArea.classList.add('border-blue-400', 'bg-blue-50');
    }

    function unhighlight() {
        dropArea.classList.remove('border-blue-400', 'bg-blue-50');
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        const fileInput = document.getElementById('fileInput');
        fileInput.files = files;

        // Trigger change event
        const event = new Event('change');
        fileInput.dispatchEvent(event);
    }
</script>
@endpush
