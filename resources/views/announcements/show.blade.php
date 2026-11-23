@extends('layouts.app')

@section('title', $announcement->title)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Back Button -->
    <a href="{{ route('announcements.index') }}"
       class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6 group">
        <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
        Kembali ke Daftar Pengumuman
    </a>

    <!-- Announcement Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <!-- Card Header with Gradient -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-8 py-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-3">{{ $announcement->title }}</h1>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mr-3">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <p class="text-blue-100 font-medium">{{ $announcement->author->name }}</p>
                                <p class="text-blue-200 text-sm">Pembuat Pengumuman</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="far fa-clock text-blue-200 mr-2 text-lg"></i>
                            <div>
                                <p class="text-blue-100 font-medium">{{ $announcement->created_at->format('d M Y') }}</p>
                                <p class="text-blue-200 text-sm">{{ $announcement->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2">
                    <span class="text-white font-semibold">PENGUMUMAN</span>
                </div>
            </div>
        </div>

        <!-- Card Content -->
        <div class="p-8">
            <div class="prose prose-lg max-w-none">
                <div class="bg-gray-50 rounded-xl p-6 mb-8 border-l-4 border-blue-500">
                    <div class="text-gray-700 whitespace-pre-line leading-relaxed">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-6 mt-8 border-t border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center text-gray-500">
                        <i class="fas fa-eye mr-2"></i>
                        <span>Dibaca oleh 125 anggota</span>
                    </div>
                </div>

                @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
                <div class="flex space-x-3">
                    <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')"
                                class="bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 font-semibold py-2 px-5 rounded-lg transition-colors flex items-center">
                            <i class="fas fa-trash mr-2"></i>
                            Hapus Pengumuman
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Announcements -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Pengumuman Lainnya</h2>
        @php
            $related = \App\Models\Announcement::where('id', '!=', $announcement->id)
                ->latest()
                ->limit(3)
                ->get();
        @endphp

        @if($related->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($related as $item)
            <a href="{{ route('announcements.show', $item->id) }}"
               class="bg-white rounded-xl shadow-md hover:shadow-xl border border-gray-100 p-5 transition-all duration-300 hover:-translate-y-1">
                <h3 class="font-bold text-gray-800 mb-2 line-clamp-2">{{ $item->title }}</h3>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ strip_tags($item->content) }}</p>
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span>{{ $item->author->name }}</span>
                    <span>{{ $item->created_at->format('d M') }}</span>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-8">Belum ada pengumuman lainnya.</p>
        @endif
    </div>
</div>

<style>
    .prose-lg {
        font-size: 1.125rem;
        line-height: 1.75;
    }

    .prose p {
        margin-bottom: 1.5em;
    }
</style>
@endsection
