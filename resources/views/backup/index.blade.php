@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manajemen Backup Data</h1>
        <p class="text-gray-600 mt-2">Buat dan kelola backup data sistem Anda</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-gray-500 text-sm font-semibold">Total Backup</div>
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_backups'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-gray-500 text-sm font-semibold">Ukuran Total</div>
            <div class="text-2xl font-bold text-purple-600">{{ round($stats['total_size'], 2) }} MB</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-gray-500 text-sm font-semibold">Total User</div>
            <div class="text-2xl font-bold text-green-600">{{ $stats['total_users'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="text-gray-500 text-sm font-semibold">Total Dokumen</div>
            <div class="text-2xl font-bold text-orange-600">{{ $stats['total_dokumen'] }}</div>
        </div>
    </div>

    <!-- Create Backup Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Buat Backup Baru</h2>

        <form action="{{ route('backup.store') }}" method="POST" id="backupForm" class="space-y-6">
            @csrf

            <!-- Backup Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Jenis Backup</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="backup_type" value="full" checked class="backup-type-radio">
                        <span class="ml-3">Full Backup (Semua Data)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="backup_type" value="custom" class="backup-type-radio">
                        <span class="ml-3">Custom Backup (Pilih Data)</span>
                    </label>
                </div>
            </div>

            <!-- Custom Options -->
            <div id="customOptions" class="hidden space-y-3">
                <label class="block">
                    <input type="checkbox" name="include_users" class="rounded">
                    <span class="ml-2">User & Pengurus</span>
                </label>
                <label class="block">
                    <input type="checkbox" name="include_organizations" class="rounded">
                    <span class="ml-2">Struktur Organisasi</span>
                </label>
                <label class="block">
                    <input type="checkbox" name="include_activities" class="rounded">
                    <span class="ml-2">Kegiatan & Peserta</span>
                </label>
                <label class="block">
                    <input type="checkbox" name="include_announcements" class="rounded">
                    <span class="ml-2">Pengumuman</span>
                </label>
                <label class="block">
                    <input type="checkbox" name="include_documents" class="rounded">
                    <span class="ml-2">Dokumen & File</span>
                </label>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                <input type="text" name="description" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Backup bulanan Februari 2026">
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span id="submitText">Mulai Backup</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Backup List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Riwayat Backup</h2>
        </div>

        @if ($backups->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama File</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ukuran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dibuat Oleh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($backups as $backup)
                        <tr class="backup-row" data-backup-id="{{ $backup->id }}">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $backup->filename }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs">
                                    {{ ucfirst($backup->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <span class="backup-size">{{ $backup->size ? round($backup->size, 2) . ' MB' : '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($backup->status === 'completed')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">Selesai</span>
                                @elseif ($backup->status === 'processing')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">Diproses</span>
                                @elseif ($backup->status === 'failed')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">Gagal</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="backup-progress h-2 rounded-full bg-blue-600" style="width: {{ $backup->progress ?? 0 }}%"></div>
                                </div>
                                <span class="backup-progress-text text-xs text-gray-600">{{ $backup->progress ?? 0 }}%</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $backup->user->name ?? 'System' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $backup->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                @if ($backup->status === 'completed' && $backup->filepath)
                                    <a href="{{ route('backup.download', $backup->id) }}" class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                                        Download
                                    </a>
                                @endif
                                <form action="{{ route('backup.destroy', $backup->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus backup ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-xs hover:bg-red-700">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $backups->links() }}
            </div>
        @else
            <div class="px-6 py-8 text-center text-gray-500">
                Belum ada backup. Buat backup pertama Anda sekarang.
            </div>
        @endif
    </div>
</div>

<script>
    // Toggle custom options
    document.querySelectorAll('.backup-type-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const customOptions = document.getElementById('customOptions');
            if (this.value === 'custom') {
                customOptions.classList.remove('hidden');
            } else {
                customOptions.classList.add('hidden');
            }
        });
    });

    // Form submission handling
    document.getElementById('backupForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span>Membuat Backup...</span>';

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(new FormData(this))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Backup sedang diproses. Data akan terlihat di daftar dalam beberapa saat.');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                alert('Error: ' + data.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Mulai Backup</span>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Mulai Backup</span>';
        });
    });

    // Polling for backup status
    function updateBackupStatus() {
        const backupRows = document.querySelectorAll('.backup-row[data-backup-id]');

        backupRows.forEach(row => {
            const backupId = row.dataset.backupId;

            // Jika status tidak 'completed', polling
            fetch(`/backup/status/${backupId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'completed') {
                        // Update progress
                        const progressBar = row.querySelector('.backup-progress');
                        const progressText = row.querySelector('.backup-progress-text');

                        if (progressBar) {
                            progressBar.style.width = data.progress + '%';
                            progressText.textContent = data.progress + '%';
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }

    // Update status setiap 2 detik
    setInterval(updateBackupStatus, 2000);
</script>
@endsection
