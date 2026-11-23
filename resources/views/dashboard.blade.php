@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang di Sistem Informasi Organisasi Mahasiswa')

@section('content')
<!-- Admin Mode Badge -->
@if(auth()->user()->isAdmin())
<div class="p-4 mb-8 border-l-4 border-yellow-500 rounded-r-lg fade-in backup-card bg-yellow-50">
    <div class="flex items-center">
        <i class="mr-3 text-yellow-500 fas fa-crown"></i>
        <div>
            <p class="font-bold text-yellow-800">Mode Administrator Aktif</p>
            <p class="text-yellow-700">Anda memiliki akses penuh ke semua fitur sistem</p>
            <p class="text-lg font-bold text-yellow-800">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
        </div>
    </div>
</div>
@endif

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-6 mb-10 fade-in md:grid-cols-2 lg:grid-cols-4">
    <!-- Total Pengurus -->
    <div class="p-6 transition-shadow bg-blue-800 border border-gray-200 rounded-lg shadow-sm backup-card hover:shadow-md">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="mb-2 text-sm font-medium text-gray-900">Total Pengurus</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalPengurus ?? 0 }}</p>
                <p class="mt-2 text-xs text-gray-900">Aktif di organisasi</p>
            </div>
            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg">
                <i class="text-lg text-blue-600 fas fa-users-cog"></i>
            </div>
        </div>
    </div>

    <!-- Total Anggota -->
    <div class="p-6 transition-shadow bg-blue-700 border border-gray-200 rounded-lg shadow-sm backup-card hover:shadow-md">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="mb-2 text-sm font-medium text-gray-900">Total Anggota</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalAnggota ?? 0 }}</p>
                <p class="mt-2 text-xs text-gray-900">Terdaftar dalam sistem</p>
            </div>
            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg">
                <i class="text-lg text-green-600 fas fa-user-graduate"></i>
            </div>
        </div>
    </div>

    <!-- Anggota Aktif -->
    <div class="p-6 transition-shadow bg-blue-600 border border-gray-200 rounded-lg shadow-sm backup-card hover:shadow-md">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="mb-2 text-sm font-medium text-gray-900">Anggota Aktif</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalAktif ?? 0 }}</p>
                <p class="mt-2 text-xs text-gray-900">Sedang aktif</p>
            </div>
            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg">
                <i class="text-lg text-purple-600 fas fa-user-check"></i>
            </div>
        </div>
    </div>

    <!-- Total Dokumen -->
    <div class="p-6 transition-shadow bg-blue-500 border border-gray-200 rounded-lg shadow-sm backup-card hover:shadow-md">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="mb-2 text-sm font-medium text-gray-900">Total Dokumen</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalDokumen ?? 5 }}</p>
                <p class="mt-2 text-xs text-gray-900">Tersimpan di sistem</p>
            </div>
            <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 bg-red-100 rounded-lg">
                <i class="text-lg text-red-600 fas fa-file-alt"></i>
            </div>
        </div>
    </div>
</div>

<!-- Two Columns Layout -->
<div class="grid grid-cols-1 gap-6 fade-in lg:grid-cols-2">
    <!-- Anggota Terbaru -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm backup-card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">Anggota Terbaru</h2>
            <a href="{{ route('anggota.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                Lihat semua →
            </a>
        </div>

        <div class="space-y-3">
            @forelse($anggotaTerbaru as $anggota)
            <div class="flex items-center justify-between p-3 transition rounded-lg hover:bg-gray-200">
                <div class="flex items-center flex-1 min-w-0">
                    <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-sm font-semibold text-blue-600 bg-blue-100 rounded-full">
                        {{ strtoupper(substr($anggota->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 ml-3">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $anggota->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $anggota->email }}</p>
                    </div>
                </div>
                <span class="ml-2 px-2.5 py-0.5 text-xs font-medium rounded-full flex-shrink-0 whitespace-nowrap
                    {{ $anggota->role == 'admin' ? 'bg-red-100 text-red-700' :
                       ($anggota->role == 'pengurus' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                    {{ ucfirst($anggota->role) }}
                </span>
            </div>
            @empty
            <div class="py-8 text-center text-gray-500">
                <i class="block mb-2 text-2xl text-gray-300 fas fa-users"></i>
                <p class="text-sm">Belum ada anggota</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Dokumen Terbaru -->
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm backup-card">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900">Dokumen Terbaru</h2>
            <a href="{{ route('dokumen.index') }}"
            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                <i class="mr-1 text-xs fas fa-upload"></i> Upload
            </a>
        </div>

        <div class="space-y-3">
            @forelse($dokumenTerbaru as $dokumen)
                <div class="flex items-center justify-between p-3 transition rounded-lg hover:bg-gray-200">
                    <div class="flex items-center flex-1 min-w-0">
                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-blue-600 bg-blue-100 rounded-lg">
                            <i class="text-sm fas fa-file-pdf"></i>
                        </div>
                        <div class="min-w-0 ml-3">
                            <p class="text-sm font-semibold text-gray-900 truncate">
                                {{ Str::limit($dokumen->nama_asli, 20) }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $dokumen->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('dokumen.preview', $dokumen->id) }}" target="_blank"
                        class="px-3 py-1 text-blue-600 transition rounded hover:text-blue-800 hover:bg-purple-50">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            @empty
                <div class="py-8 text-center text-gray-500">
                    <i class="block mb-2 text-2xl text-gray-300 fas fa-file"></i>
                    <p class="text-sm">Belum ada dokumen</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

    <div class="p-6 mt-10 bg-white border border-gray-200 rounded shadow-sm backup-card fade-in">
        <h2 class="mb-2 text-lg font-bold text-gray-900">Statistik Kegiatan</h2>
        <div class="relative w-full h-64">
            <canvas id="kegiatanChart"></canvas>
        </div>
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
document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded. Please check if the CDN link is working.');
        return;
    }

    // Check if canvas element exists
    const canvas = document.getElementById('kegiatanChart');
    if (!canvas) {
        console.error('Canvas element with id "kegiatanChart" not found.');
        return;
    }

    try {
        // Initialize Chart.js for Kegiatan Chart
        const ctx = canvas.getContext('2d');
        const kegiatanChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels ?? []),
                datasets: [{
                    label: 'Jumlah Kegiatan',
                    data: @json($chartData ?? []),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: '#1d4ed8',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            title: function(context) {
                                return 'Bulan: ' + context[0].label;
                            },
                            label: function(context) {
                                return 'Kegiatan: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            borderDash: [5, 5]
                        },
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 12
                            }
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Kegiatan',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        title: {
                            display: true,
                            text: 'Bulan',
                            font: {
                                size: 14,
                                weight: 'bold'
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        hoverRadius: 8
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Add gradient background
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');
        kegiatanChart.data.datasets[0].backgroundColor = gradient;
        kegiatanChart.update();

        console.log('Chart initialized successfully');
    } catch (error) {
        console.error('Error initializing chart:', error);
    }
});
</script>
@endpush
