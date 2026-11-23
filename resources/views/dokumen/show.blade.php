<!-- resources/views/dokumen/show.blade.php -->
@extends('layouts.app')

@section('title', $dokumen->nama_file)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $dokumen->nama_file }}</h1>
                <div class="flex items-center space-x-3 mt-2">
                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800">
                        {{ $dokumen->kategori }}
                    </span>
                    @if(!$dokumen->is_public)
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-lock mr-1"></i>Private
                        </span>
                    @endif
                    <span class="text-sm text-gray-500">{{ $dokumen->uploaded_at }}</span>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('dokumen.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('dokumen.download', $dokumen->id) }}"
                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-download mr-2"></i>Download
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: File Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- File Preview -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Preview Dokumen</h3>
                <div class="border-2 border-gray-200 rounded-lg p-8 text-center">
                    @if($dokumen->tipe == 'pdf')
                        <!-- PDF Preview -->
                        <iframe src="{{ route('dokumen.preview', $dokumen->id) }}"
                                class="w-full h-96 rounded-lg border"
                                frameborder="0"></iframe>
                        <p class="text-sm text-gray-500 mt-4">PDF Document - {{ $dokumen->ukuran_format }}</p>
                    @elseif($dokumen->tipe == 'image')
                        <!-- Image Preview -->
                        <img src="{{ Storage::url($dokumen->path) }}"
                             alt="{{ $dokumen->nama_file }}"
                             class="max-w-full h-auto mx-auto rounded-lg">
                    @else
                        <!-- Generic File Preview -->
                        <div class="py-12">
                            <div class="w-24 h-24 {{ $dokumen->tipe == 'pdf' ? 'bg-red-100' : ($dokumen->tipe == 'doc' ? 'bg-blue-100' : ($dokumen->tipe == 'xls' ? 'bg-green-100' : 'bg-gray-100')) }} rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="{{ $dokumen->icon }} {{ $dokumen->tipe == 'pdf' ? 'text-red-600' : ($dokumen->tipe == 'doc' ? 'text-blue-600' : ($dokumen->tipe == 'xls' ? 'text-green-600' : 'text-gray-600')) }} text-3xl"></i>
                            </div>
                            <p class="text-lg font-medium text-gray-700">{{ $dokumen->nama_file }}</p>
                            <p class="text-gray-500">{{ strtoupper($dokumen->ekstensi) }} File - {{ $dokumen->ukuran_format }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-center space-x-4 mt-6">
                    <a href="{{ route('dokumen.download', $dokumen->id) }}"
                       class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-medium">
                        <i class="fas fa-download mr-2"></i>Download File
                    </a>

                    @if($dokumen->tipe == 'pdf')
                    <a href="{{ route('dokumen.preview', $dokumen->id) }}" target="_blank"
                       class="px-6 py-3 border border-blue-500 text-blue-500 rounded-lg hover:bg-blue-50 transition font-medium">
                        <i class="fas fa-external-link-alt mr-2"></i>Buka di Tab Baru
                    </a>
                    @endif
                </div>
            </div>

            <!-- Description -->
            @if($dokumen->deskripsi)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Deskripsi</h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-line">{{ $dokumen->deskripsi }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Details & Actions -->
        <div class="space-y-6">
            <!-- File Details -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail File</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Asli</span>
                        <span class="text-gray-800 font-medium">{{ $dokumen->nama_asli }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ukuran</span>
                        <span class="text-gray-800 font-medium">{{ $dokumen->ukuran_format }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tipe</span>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                            {{ strtoupper($dokumen->ekstensi) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Download</span>
                        <span class="text-gray-800 font-medium">{{ $dokumen->download_count }} kali</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Upload Date</span>
                        <span class="text-gray-800">{{ $dokumen->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Terakhir Diubah</span>
                        <span class="text-gray-800">{{ $dokumen->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Uploader Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Uploader</h3>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                        {{ substr($dokumen->uploader->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $dokumen->uploader->name }}</p>
                        <p class="text-sm text-gray-600">{{ $dokumen->uploader->email }}</p>
                        <p class="text-xs text-blue-600 font-medium mt-1">{{ ucfirst($dokumen->uploader->role) }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h3>
                <div class="space-y-3">
                    <a href="{{ route('dokumen.download', $dokumen->id) }}"
                       class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-download text-blue-600 mr-3"></i>
                            <span class="font-medium text-gray-800">Download File</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>

                    @if(auth()->id() == $dokumen->uploaded_by || auth()->user()->isAdmin())
                    <button onclick="copyShareLink()"
                       class="w-full flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <div class="flex items-center">
                            <i class="fas fa-share-alt text-green-600 mr-3"></i>
                            <span class="font-medium text-gray-800">Copy Share Link</span>
                        </div>
                        <i class="fas fa-copy text-gray-400"></i>
                    </button>

                    <div class="pt-4 border-t border-gray-200">
                        <form action="{{ route('dokumen.destroy', $dokumen->id) }}" method="POST"
                              onsubmit="return confirm('Hapus dokumen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-full flex items-center justify-center p-3 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition font-medium">
                                <i class="fas fa-trash mr-2"></i>Hapus Dokumen
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Share Status -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Berbagi</h3>
                <div class="space-y-2">
                    <div class="flex items-center">
                        @if($dokumen->is_public)
                            <i class="fas fa-globe text-green-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-800">Public</p>
                                <p class="text-sm text-gray-600">Dapat diakses semua anggota</p>
                            </div>
                        @else
                            <i class="fas fa-lock text-yellow-600 mr-3"></i>
                            <div>
                                <p class="font-medium text-gray-800">Private</p>
                                <p class="text-sm text-gray-600">Hanya uploader dan admin</p>
                            </div>
                        @endif
                    </div>

                    @if($dokumen->password)
                    <div class="flex items-center mt-3">
                        <i class="fas fa-key text-purple-600 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-800">Protected</p>
                            <p class="text-sm text-gray-600">Dilindungi password</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyShareLink() {
        const link = window.location.href;
        navigator.clipboard.writeText(link).then(() => {
            alert('Link berhasil disalin!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
</script>
@endpush
