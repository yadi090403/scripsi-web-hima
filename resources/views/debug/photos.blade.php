<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug - Foto Struktur Organisasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .photo-preview {
            width: 120px;
            height: 120px;
            border-radius: 8px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid #ddd;
        }
        .photo-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        .code-block {
            background: #1e293b;
            color: #e2e8f0;
            padding: 12px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
            word-break: break-all;
            line-height: 1.4;
        }
    </style>
</head>
<body class="bg-gray-100">
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <div class="flex items-center mb-4">
                <i class="fas fa-bug text-2xl text-blue-600 mr-3"></i>
                <h1 class="text-4xl font-bold text-gray-800">Debug: Struktur Organisasi - Foto</h1>
            </div>
            <p class="text-gray-600">Halaman ini untuk diagnosa masalah menampilkan foto di struktur organisasi</p>
        </div>

        <!-- Information Panel -->
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                Informasi Sistem
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600"><strong>App URL:</strong></p>
                    <p class="text-gray-800 bg-white px-3 py-2 rounded">{{ $stats['appUrl'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600"><strong>Storage Root:</strong></p>
                    <p class="text-gray-800 bg-white px-3 py-2 rounded text-xs font-mono break-all">
                        {{ $stats['storageRoot'] }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <i class="fas fa-sitemap text-4xl text-blue-500 mb-3"></i>
                    <p class="text-gray-600 text-sm">Total Posisi</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['totalPositions'] }}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <i class="fas fa-image text-4xl text-green-500 mb-3"></i>
                    <p class="text-gray-600 text-sm">Posisi dengan Foto</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['positionsWithPhoto'] }}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <i class="fas fa-folder text-4xl text-orange-500 mb-3"></i>
                    <p class="text-gray-600 text-sm">File di Storage</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['filesOnDisk'] }}</p>
                </div>
            </div>
        </div>

        <!-- Positions with Photos -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-list mr-3 text-purple-600"></i>
                Posisi dengan Foto ({{ $positions->count() }})
            </h2>

            @if($positions->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Tidak ada posisi dengan foto di database</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($positions as $position)
                        @php
                            $photoDb = $position->photo;
                            $photoUrl = $position->photo_url;
                            $fileExists = false;

                            // Check if file exists
                            if (strpos($photoDb, '/') !== false) {
                                $fileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($photoDb);
                            } else {
                                $fileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists('organization-photos/' . $photoDb);
                            }
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <!-- Photo Preview -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-600 mb-2">PREVIEW</p>
                                    <div class="photo-preview">
                                        @if($fileExists)
                                            <img src="{{ $photoUrl }}" alt="{{ $position->display_name }}" loading="lazy">
                                        @else
                                            <div class="text-center text-red-500">
                                                <i class="fas fa-times text-3xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Information -->
                                <div class="md:col-span-3">
                                    <!-- Position Info -->
                                    <div class="mb-4">
                                        <h3 class="text-lg font-bold text-gray-800">{{ $position->position_name }}</h3>
                                        <p class="text-gray-600">{{ $position->display_name }}</p>
                                        <div class="mt-2 flex gap-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                ID: {{ $position->id }}
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Level: {{ $position->position_level }}
                                            </span>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $fileExists ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                <i class="fas {{ $fileExists ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                                {{ $fileExists ? 'File exists' : 'File missing' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Database Field -->
                                    <div class="mb-4">
                                        <p class="text-xs font-semibold text-gray-600 mb-2">FIELD FOTO DI DATABASE</p>
                                        <div class="code-block">
                                            "{{ $photoDb }}"
                                        </div>
                                    </div>

                                    <!-- Generated URL -->
                                    <div class="mb-4">
                                        <p class="text-xs font-semibold text-gray-600 mb-2">URL YANG DIHASILKAN ACCESSOR</p>
                                        <div class="code-block">
                                            {{ $photoUrl }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Files on Disk -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-folder-open mr-3 text-orange-600"></i>
                File Fisik di Storage ({{ count($filesOnDisk) }})
            </h2>

            @if(empty($filesOnDisk))
                <div class="text-center py-12">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Tidak ada file di folder organization-photos</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($filesOnDisk as $file)
                        <div class="text-center">
                            <p class="text-xs bg-gray-100 px-2 py-1 rounded break-all text-gray-600">
                                {{ basename($file) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Quick Checks -->
        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-check-circle mr-2 text-green-600"></i>
                Checklist
            </h2>
            <ul class="space-y-2">
                <li class="flex items-center">
                    <i class="fas fa-{{ count($filesOnDisk) > 0 ? 'check text-green-600' : 'times text-red-600' }} mr-3 w-5"></i>
                    <span class="text-gray-700">
                        Folder organization-photos ada dan {{ count($filesOnDisk) }} file ditemukan
                    </span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-{{ $stats['positionsWithPhoto'] > 0 ? 'check text-green-600' : 'times text-red-600' }} mr-3 w-5"></i>
                    <span class="text-gray-700">
                        {{ $stats['positionsWithPhoto'] }} posisi memiliki data foto di database
                    </span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-{{ $stats['positionsWithPhoto'] === count($filesOnDisk) ? 'check text-green-600' : 'exclamation text-yellow-600' }} mr-3 w-5"></i>
                    <span class="text-gray-700">
                        Jumlah foto database {{ $stats['positionsWithPhoto'] === count($filesOnDisk) ? '✓ sesuai' : '⚠ tidak sesuai' }} dengan file fisik
                    </span>
                </li>
            </ul>
        </div>

        <!-- How to Test -->
        <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-lightbulb mr-2 text-yellow-600"></i>
                Cara Mengecek di Browser
            </h2>
            <ol class="space-y-2 text-gray-700 list-decimal list-inside">
                <li>Buka halaman <a href="{{ route('organizational-structure.index') }}" class="text-blue-600 hover:underline">struktur organisasi</a></li>
                <li>Jika foto tidak tampil, tekan F12 untuk buka Developer Tools</li>
                <li>Pergi ke tab "Network" dan refresh halaman</li>
                <li>Cari request yang berisi "storage/organization-photos"</li>
                <li>Lihat apakah statusnya 200 (sukses) atau 404 (tidak ditemukan)</li>
                <li>Jika 404, ada masalah dengan path atau symlink</li>
            </ol>
        </div>
    </div>
</div>
</body>
</html>
