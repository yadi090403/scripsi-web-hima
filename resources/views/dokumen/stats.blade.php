@extends('layouts.app')

@section('title', 'Statistik Dokumen')

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Statistik Dokumen</h1>
            <p class="text-gray-600">Analisis data dan penggunaan dokumen</p>
        </div>
        <a href="{{ route('dokumen.index') }}" class="text-blue-500 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-file text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Dokumen</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalDokumen }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-hdd text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Penyimpanan</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalUkuran, 2) }} MB</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-download text-purple-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Download</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalDownload }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-user text-orange-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Rata-rata Download</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $totalDokumen > 0 ? round($totalDownload / $totalDokumen, 1) : 0 }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Dokumen per Kategori -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Dokumen per Kategori</h3>
        <div class="space-y-4">
            @foreach($kategoriStats as $stat)
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm text-gray-600">{{ $stat->kategori }}</span>
                    <span class="text-sm font-semibold text-gray-800">{{ $stat->total }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $percentage = $totalDokumen > 0 ? ($stat->total / $totalDokumen) * 100 : 0;
                        $colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-orange-500', 'bg-red-500', 'bg-pink-500'];
                        $colorIndex = $loop->index % count($colors);
                    @endphp
                    <div class="{{ $colors[$colorIndex] }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Top 5 Dokumen Terbanyak Didownload -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 5 Dokumen Terpopuler</h3>
        <div class="space-y-3">
            @php
                $topDokumen = \App\Models\Dokumen::orderBy('download_count', 'desc')->limit(5)->get();
            @endphp
            @foreach($topDokumen as $dokumen)
            <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition">
                <div class="flex items-center">
                    <div class="w-8 h-8 {{ $dokumen->tipe == 'pdf' ? 'bg-red-100' : 'bg-blue-100' }} rounded-lg flex items-center justify-center mr-3">
                        <i class="{{ $dokumen->icon }} {{ $dokumen->tipe == 'pdf' ? 'text-red-600' : 'text-blue-600' }} text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 truncate max-w-xs">{{ $dokumen->nama_file }}</p>
                        <p class="text-xs text-gray-500">{{ $dokumen->kategori }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-800">{{ $dokumen->download_count }}</p>
                    <p class="text-xs text-gray-500">download</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
    </div>
    <div class="divide-y divide-gray-200">
        @php
            $recentUploads = \App\Models\Dokumen::with('uploader')->latest()->limit(10)->get();
        @endphp
        @foreach($recentUploads as $dokumen)
        <div class="px-6 py-4 hover:bg-gray-50 transition">
            <div class="flex items-center">
                <div class="w-10 h-10 {{ $dokumen->tipe == 'pdf' ? 'bg-red-100' : 'bg-blue-100' }} rounded-lg flex items-center justify-center mr-4">
                    <i class="{{ $dokumen->icon }} {{ $dokumen->tipe == 'pdf' ? 'text-red-600' : 'text-blue-600' }}"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-gray-800">{{ $dokumen->nama_file }}</p>
                    <div class="flex items-center text-sm text-gray-500 mt-1">
                        <span class="flex items-center mr-4">
                            <i class="fas fa-user mr-1"></i>
                            {{ $dokumen->uploader->name }}
                        </span>
                        <span class="flex items-center mr-4">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $dokumen->created_at->diffForHumans() }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-download mr-1"></i>
                            {{ $dokumen->download_count }} download
                        </span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('dokumen.download', $dokumen->id) }}"
                       class="text-blue-600 hover:text-blue-800 px-3 py-1 rounded hover:bg-blue-50 transition">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
