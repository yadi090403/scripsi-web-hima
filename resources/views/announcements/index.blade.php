@extends('layouts.app')

@section('title', 'Daftar Pengumuman')

@section('content')
<div class="fade-in" style="max-width: 1400px; margin: 0 auto; padding: 0 24px;">
    <!-- Header Section -->
    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 32px; gap: 20px; flex-wrap: wrap;">
        <div>
            <h1 style="color: #1a1a1a; letter-spacing: -0.5px; font-size: 2rem; font-weight: 700; margin-bottom: 8px;">
                <i class="fas fa-bullhorn me-2" style="color: #0066cc;"></i>Pengumuman
            </h1>
            <p style="color: #666; font-size: 0.95rem; line-height: 1.5;">Informasi terkini dari organisasi</p>
        </div>

        @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
        <a href="{{ route('announcements.create') }}" class="backup-card btn btn-primary"
           style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); color: white; border: none; border-radius: 8px; padding: 12px 24px; font-weight: 600; font-size: 0.95rem; box-shadow: 0 4px 12px rgba(0, 102, 204, 0.25); transition: all 0.3s ease; white-space: nowrap; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus-circle"></i>Buat Pengumuman Baru
        </a>
        @endif
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div style="background: #d1e7dd; border-left: 4px solid #27ae60; padding: 16px; margin-bottom: 24px; border-radius: 0 8px 8px 0;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-check-circle" style="color: #27ae60; font-size: 1.1rem;"></i>
            <p style="color: #155724; font-weight: 500; margin: 0;">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
    <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 16px; margin-bottom: 24px; border-radius: 0 8px 8px 0;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fas fa-exclamation-circle" style="color: #dc3545; font-size: 1.1rem;"></i>
            <p style="color: #721c24; font-weight: 500; margin: 0;">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Announcements Table -->
    @if($announcements->count() > 0)
        <div class="backup-card" style="background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08); overflow: hidden; border: 1px solid #f0f0f0; margin-bottom: 32px;">
            <!-- Table Header -->
            <div style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); padding: 20px; border-bottom: 1px solid #f0f0f0;">
                <h2 style="color: white; font-size: 1.2rem; font-weight: 600; margin: 0;">Daftar Pengumuman</h2>
            </div>

            <!-- Announcements Grid with Images -->
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; padding: 24px;">
                @foreach($announcements as $announcement)
                <div class="backup-card" style="background: #f8f9fa; border-radius: 12px; overflow: hidden; border: 1px solid #e9ecef; transition: all 0.3s ease; display: flex; flex-direction: column;">
                    <!-- Image Container -->
                    <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                        @if($announcement->image_path && file_exists(public_path('storage/' . $announcement->image_path)))
                            <img src="{{ asset('storage/' . $announcement->image_path) }}"
                                 alt="{{ $announcement->title }}"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; text-align: center;">
                                <i class="fas fa-image" style="font-size: 3rem; margin-bottom: 10px; opacity: 0.6;"></i>
                                <p style="margin: 0; font-size: 0.9rem; opacity: 0.8;">Tidak ada gambar</p>
                            </div>
                        @endif
                    </div>

                    <!-- Content Container -->
                    <div style="padding: 16px; flex: 1; display: flex; flex-direction: column;">
                        <!-- Title and Author -->
                        <div style="margin-bottom: 12px;">
                            <h3 style="color: #1a1a1a; font-size: 1.1rem; font-weight: 600; margin: 0 0 8px 0; line-height: 1.3;">
                                {{ $announcement->title }}
                            </h3>
                            <div style="display: flex; align-items: center; gap: 8px; color: #666; font-size: 0.85rem;">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ $announcement->author->name }}</span>
                                <span style="color: #0066cc; font-weight: 600; margin-left: auto;">BARU</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div style="flex: 1;">
                            <p style="color: #666; margin: 0 0 12px 0; font-size: 0.9rem; line-height: 1.5; max-height: 54px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                {{ strip_tags($announcement->content) }}
                            </p>
                        </div>

                        <!-- Date -->
                        <div style="display: flex; align-items: center; color: #999; font-size: 0.8rem; margin-bottom: 12px; padding-top: 12px; border-top: 1px solid #e9ecef;">
                            <i class="far fa-clock me-2"></i>
                            <span>{{ $announcement->created_at->format('d M Y') }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">
                            <a href="{{ route('announcements.show', $announcement->id) }}"
                               style="color: #0066cc; text-decoration: none; font-weight: 500; font-size: 0.85rem; transition: all 0.3s ease; display: flex; align-items: center; gap: 4px; flex: 1;">
                                <span>Baca Selengkapnya</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>

                            @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
                            <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?');" style="display: flex; align-items: center;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #dc3545; cursor: pointer; font-size: 0.95rem; transition: all 0.3s ease; padding: 4px 8px; border-radius: 4px;"
                                        onmouseover="this.style.backgroundColor='#ffe5e5';"
                                        onmouseout="this.style.backgroundColor='transparent';"
                                        title="Hapus pengumuman">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="display: flex; justify-content: center; margin-top: 32px;">
            <div style="background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); padding: 16px;">
                {{ $announcements->links() }}
            </div>
        </div>
        </div>
    @else
        <!-- Empty State -->
        <div style="background: linear-gradient(135deg, #f5f7ff 0%, #f0f4ff 100%); border: 2px dashed #ccc; border-radius: 16px; padding: 48px 24px; text-align: center;">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; border-radius: 50%; background: #e3f2fd; margin-bottom: 24px;">
                <i class="fas fa-bullhorn" style="font-size: 2rem; color: #0066cc;"></i>
            </div>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #333; margin-bottom: 12px;">Belum Ada Pengumuman</h3>
            <p style="color: #666; font-size: 0.95rem; margin-bottom: 24px; max-width: 400px; margin-left: auto; margin-right: auto;">
                Saat ini belum ada pengumuman yang dibuat. Buat pengumuman pertama untuk membagikan informasi penting.
            </p>
            @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
            <a href="{{ route('announcements.create') }}"
               style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%); color: white; border: none; border-radius: 8px; padding: 12px 24px; font-weight: 600; font-size: 0.95rem; box-shadow: 0 4px 12px rgba(0, 102, 204, 0.25); transition: all 0.3s ease; white-space: nowrap; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i>Buat Pengumuman Pertama
            </a>
            @endif
        </div>
    @endif
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

    .d-inline {
        display: inline;
    }

    .me-2 {
        margin-right: 8px;
    }
</style>
@endsection
