@extends('layouts.app')

@section('content')
<div class="px-4 mx-auto max-w-7xl">
    <!-- HEADER -->
    <div class="mb-6">
        <div class="relative p-6 overflow-hidden text-white shadow-lg rounded-2xl bg-gradient-to-r from-indigo-500 to-purple-600">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <!-- Title -->
                <div>
                    <h1 class="flex items-center gap-2 text-2xl font-semibold">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $kegiatan->nama_kegiatan }}
                    </h1>
                    <nav class="mt-1 text-sm text-white/80">
                        <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                        <span class="mx-1">/</span>
                        <a href="{{ route('kegiatan.index') }}" class="hover:underline">Kegiatan</a>
                        <span class="mx-1">/</span>
                        <span class="font-medium">Detail</span>
                    </nav>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('kegiatan.index') }}"
                       class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-lg hover:bg-gray-100">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>

                    <a href="{{ route('kegiatan.edit', $kegiatan->id) }}"
                       class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <button data-bs-toggle="modal" data-bs-target="#statusModal"
                            class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-white rounded-lg bg-sky-500 hover:bg-sky-600">
                        <i class="fas fa-sync-alt"></i> Status
                    </button>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-4 mt-6 text-center md:grid-cols-4">
                <div>
                    <div class="text-2xl font-bold">
                        {{ $kegiatan->peserta->where('status_pendaftaran','diterima')->count() }}
                    </div>
                    <span class="text-sm text-white/80">Peserta</span>
                </div>

                <div>
                    <div class="text-2xl font-bold">{{ $kegiatan->fotos->count() }}</div>
                    <span class="text-sm text-white/80">Foto</span>
                </div>

                <div>
                    <div class="text-2xl font-bold">
                        @if($kegiatan->kuota > 0)
                            {{ $kegiatan->sisaKuota() }}
                        @else
                            ∞
                        @endif
                    </div>
                    <span class="text-sm text-white/80">Sisa Kuota</span>
                </div>

                <div>
                    @php
                        $statusLabels = [
                            'rencana' => 'bg-yellow-400',
                            'berlangsung' => 'bg-blue-500',
                            'selesai' => 'bg-green-500',
                            'dibatalkan' => 'bg-red-500',
                            'arsip' => 'bg-gray-500'
                        ];
                    @endphp
                    <span class="inline-block px-3 py-1 text-sm font-medium text-white rounded-full {{ $statusLabels[$kegiatan->status] ?? 'bg-gray-400' }}">
                        {{ ucfirst($kegiatan->status) }}
                    </span>
                    <div class="mt-1 text-sm text-white/80">Status</div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT GRID -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        <!-- MAIN CONTENT -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Card -->
            <div class="p-6 bg-white shadow rounded-xl">
                <h2 class="mb-3 text-lg font-semibold">Deskripsi Kegiatan</h2>
                <p class="leading-relaxed text-gray-600">
                    {{ $kegiatan->deskripsi }}
                </p>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="space-y-6">
            <div class="p-6 bg-white shadow rounded-xl">
                <h3 class="mb-4 text-sm font-semibold text-gray-700">Informasi Singkat</h3>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Dibuat oleh</span>
                        <span class="font-medium">{{ $kegiatan->creator->name }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal</span>
                        <span>{{ $kegiatan->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
