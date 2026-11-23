@extends('layouts.app')
@section('title', 'Manajemen Kegiatan')

@section('content')
<div class="fade-in">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 24px;">
    <!-- Header Section with Improved Design -->
    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 32px; gap: 20px;">
        <!-- Left Content -->
        <div style="flex: 1;">
            <!-- Title and Description -->
            <div>
                <h1 class="mb-2" style="color: #1a1a1a; letter-spacing: -0.5px; font-size: 2rem; font-weight: 700; display: flex; align-items: center; gap: 12px;">
                    <i class="fas fa-calendar-alt" style="color: #0066cc; font-size: 1.6rem;"></i>Kelola Kegiatan
                </h1>
                <p class="mb-0" style="color: #666; font-size: 0.95rem; line-height: 1.5; margin-left: 36px;">
                    Kelola semua kegiatan organisasi Anda dengan mudah dan efisien
                </p>
            </div>
        </div>

        <!-- Right Content - Button -->
        <div style="display: flex; align-items: center; padding-top: 8px;">
            <a href="{{ route('kegiatan.create') }}" class="backup-card btn btn-primary" style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); border: none; border-radius: 8px; padding: 12px 24px; font-weight: 600; font-size: 0.95rem; box-shadow: 0 4px 12px rgba(0, 102, 204, 0.25); transition: all 0.3s ease; white-space: nowrap; color: white; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus-circle"></i>Buat Kegiatan Baru
            </a>
        </div>
    </div>

    <!-- Filter Section - Improved Design -->
    <div class="mb-4 backup-card card" style="border: none; border-radius: 14px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08); background: white; overflow: hidden;">
        <div class="p-0 card-body">

            <!-- Header -->
            <div style="background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%); padding: 20px; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-filter" style="color: #0066cc;"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">Filter Pencarian</h6>
                    <small style="color:#999">Cari dan filter kegiatan</small>
                </div>
            </div>

            <!-- Form Horizontal -->
            <div style="padding: 24px;">
                <form action="{{ route('kegiatan.index') }}" method="GET"
                    style="display: flex; gap: 14px; align-items: flex-end; flex-wrap: nowrap;">

                    <!-- Status -->
                    <div style="width: 395px;">
                        <label class="form-label fw-600" style="font-size: 0.85rem;">Status</label>
                        <select name="status" class="form-select"
                            style="border-radius: 8px; border: 1.5px solid #e0e0e0;">
                            <option value="">-- Semua Status --</option>
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kategori -->
                    <div style="width: 395px;">
                        <label class="form-label fw-600" style="font-size: 0.85rem;">Kategori</label>
                        <select name="kategori" class="form-select"
                            style="border-radius: 8px; border: 1.5px solid #e0e0e0;">
                            <option value="">-- Semua Kategori --</option>
                            @foreach($kategories as $key => $label)
                                <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search -->
                    <div style="width: 395px;">
                        <label class="form-label fw-600" style="font-size: 0.85rem;">Pencarian</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="Cari kegiatan..."
                            value="{{ request('search') }}"
                            style="border-radius: 8px; border: 1.5px solid #e0e0e0;">
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; gap: 8px;">
                        <button type="submit" class="btn fw-600"
                            style="background: linear-gradient(135deg,#0066cc,#0052a3); color:white; border-radius:8px; padding:10px 28px;">
                            <i class="fas fa-search"></i>
                        </button>

                        @if(request('status') || request('kategori') || request('search'))
                        <a href="{{ route('kegiatan.index') }}" class="btn fw-600"
                            style="background:#e0e0e0; border-radius:8px; padding:10px 14px;">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>



    <!-- Activities Table -->
    <div class="backup-card card" style="border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); overflow: hidden;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); border: none; padding: 20px;">
            <h5 class="mb-0" style="color: white; font-weight: 600;">Daftar Kegiatan</h5>
        </div>
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table mb-0 transition hover:bg-gray-300 table-hover" style="border: none; width: 100%;">
                    <thead style="background-color: #f8f9fa; border-bottom: 2px solid #e0e0e0;">
                        <tr>
                            <th width="80px" class="text-center fw-600" style="color: #333; padding: 16px; font-size: 0.95rem;">Foto</th>
                            <th class="fw-600" style="color: #333; padding: 16px; font-size: 0.95rem; min-width: 250px;">Nama Kegiatan</th>
                            <th width="140px" class="fw-600" style="color: #333; padding: 16px; font-size: 0.95rem;">Tanggal</th>
                            <th width="110px" class="fw-600" style="color: #333; padding: 16px; font-size: 0.95rem;">Kategori</th>
                            <th width="110px" class="fw-600" style="color: #333; padding: 16px; font-size: 0.95rem;">Status</th>
                            <th width="90px" class="text-center fw-600" style="color: #333; padding: 16px; font-size: 0.95rem;">Peserta</th>
                            <th width="200px" class="text-center fw-600" style="color: #333; padding: 16px; font-size: 0.95rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatan as $item)
                        <tr style="border-bottom: 1px solid #f0f0f0; transition: background-color 0.3s ease;">
                            <td class="text-center align-middle" style="padding: 16px;">
                                @if($item->foto_utama)
                                    <img src="{{ Storage::url($item->foto_utama) }}"
                                         alt="{{ $item->nama_kegiatan }}"
                                         class="rounded"
                                         style="width: 60px; height: 60px; object-fit: cover; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center"
                                         style="width: 60px; height: 60px; background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);">
                                        <i class="fas fa-calendar-alt text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 16px;">
                                <strong style="color: #1a1a1a; font-size: 0.95rem; display: block; margin-bottom: 4px;">{{ $item->nama_kegiatan }}</strong>
                                <p class="mb-2 text-muted" style="font-size: 0.85rem;">
                                    {{ Str::limit($item->deskripsi, 80) }}
                                </p>
                                <small class="text-muted" style="font-size: 0.8rem;">
                                    <i class="fas fa-user me-1" style="color: #0066cc;"></i>{{ $item->creator->name }}
                                </small>
                            </td>
                            <td style="padding: 16px;">
                                <div style="color: #1a1a1a; font-weight: 500; margin-bottom: 4px;">{{ $item->tanggal_mulai->format('d M Y') }}</div>
                                <small class="text-muted" style="font-size: 0.85rem;">
                                    {{ $item->tanggal_mulai->format('H:i') }} -
                                    {{ $item->tanggal_selesai->format('H:i') }}
                                </small>
                            </td>
                            <td style="padding: 16px;">
                                @php
                                    $kategoriLabels = [
                                        'seminar' => ['#e3f2fd', '#0066cc', 'Seminar'],
                                        'pelatihan' => ['#e8f5e9', '#27ae60', 'Pelatihan'],
                                        'lomba' => ['#fff3e0', '#ff9800', 'Lomba'],
                                        'rapat' => ['#f3e5f5', '#9c27b0', 'Rapat'],
                                        'sosial' => ['#fce4ec', '#e91e63', 'Sosial'],
                                        'internal' => ['#eceff1', '#455a64', 'Internal'],
                                        'eksternal' => ['#f3e5f5', '#6f42c1', 'Eksternal'],
                                        'lainnya' => ['#e0e0e0', '#757575', 'Lainnya']
                                    ];
                                    $kategori = $kategoriLabels[$item->kategori] ?? ['#e0e0e0', '#757575', 'Lainnya'];
                                @endphp
                                <span style="background-color: {{ $kategori[0] }}; color: {{ $kategori[1] }}; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 500; display: inline-block;">
                                    {{ $kategori[2] }}
                                </span>
                                <br>
                                <small class="mt-1 text-muted" style="font-size: 0.8rem; display: inline-block; margin-top: 6px;">
                                    <i class="fas fa-{{ $item->jenis == 'online' ? 'video' : 'map-marker-alt' }} me-1" style="color: #0066cc;"></i>
                                    {{ ucfirst($item->jenis) }}
                                </small>
                            </td>
                            <td style="padding: 16px;">
                                @php
                                    $statusLabels = [
                                        'rencana' => ['#fff3cd', '#664d03', 'Rencana'],
                                        'berlangsung' => ['#cfe2ff', '#084298', 'Berlangsung'],
                                        'selesai' => ['#d1e7dd', '#0f5132', 'Selesai'],
                                        'dibatalkan' => ['#f8d7da', '#842029', 'Dibatalkan'],
                                        'arsip' => ['#e2e3e5', '#41464b', 'Arsip']
                                    ];
                                    $status = $statusLabels[$item->status] ?? ['#e2e3e5', '#41464b', 'Unknown'];
                                @endphp
                                <span style="background-color: {{ $status[0] }}; color: {{ $status[1] }}; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 500;">
                                    {{ $status[2] }}
                                </span>
                            </td>
                            <td class="text-center align-middle" style="padding: 16px;">
                                <div class="text-center">
                                    <div style="color: #0066cc; font-weight: 600; font-size: 1.1rem;">{{ $item->peserta->where('status_pendaftaran', 'diterima')->count() }}</div>
                                    <small class="text-muted" style="font-size: 0.8rem;">
                                        @if($item->kuota > 0)
                                            / {{ $item->kuota }}
                                        @else
                                            Unlimited
                                        @endif
                                    </small>
                                </div>
                            </td>
                            <td class="text-center align-middle" style="padding: 16px;">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('kegiatan.show', $item->id) }}"
                                       class="btn btn-sm" style="background-color: #e3f2fd; color: #0066cc; border: none; border-radius: 6px; padding: 8px 12px; transition: all 0.3s ease; font-size: 0.9rem;" title="Detail">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                    <a href="{{ route('kegiatan.edit', $item->id) }}"
                                       class="btn btn-sm" style="background-color: #fff3e0; color: #ff9800; border: none; border-radius: 6px; padding: 8px 12px; margin: 0 2px; transition: all 0.3s ease; font-size: 0.9rem;" title="Edit">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('kegiatan.destroy', $item->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color: #ffebee; color: #c62828; border: none; border-radius: 6px; padding: 8px 12px; transition: all 0.3s ease; font-size: 0.9rem;" title="Hapus">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-5 text-center">
                                <div style="color: #999;">
                                    <i class="mb-3 fas fa-calendar-times fa-3x" style="display: block; margin-bottom: 12px; color: #ddd;"></i>
                                    <p class="mb-0 fw-500" style="font-size: 1.1rem; color: #666;">Belum ada kegiatan</p>
                                    <p class="mt-2 text-muted" style="font-size: 0.9rem;">Mulai buat kegiatan baru untuk menampilkan di sini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer" style="background-color: #f8f9fa; border-top: 1px solid #f0f0f0; padding: 16px; border-radius: 0 0 12px 12px;">
            {{ $kegiatan->links() }}
        </div>
    </div>
    </div>
</div>

@push('styles')
<style>
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #0066cc;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #0052a3;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa !important;
        transition: background-color 0.3s ease;
    }

    .btn-group .btn {
        transition: all 0.3s ease;
    }

    .btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-select:focus, .form-control:focus {
        border-color: #0066cc !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.25) !important;
    }

    .form-select, .form-control {
        transition: all 0.3s ease;
    }

    #fotoInput {
        cursor: pointer;
    }

    #fotoInput:hover {
        border-color: #0066cc !important;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .table {
        font-size: 0.95rem;
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
@endsection
