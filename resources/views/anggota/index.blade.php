@extends('layouts.app')

@section('title', 'Manajemen Data')

@section('content')
<div class="mx-auto fade-in max-w-7xl">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Data</h1>
                <p class="mt-2 text-gray-600">Kelola data anggota dan pengurus HIMA NJ</p>
            </div>
            <a href="{{ route('anggota.create') }}"
               class="inline-flex items-center px-5 py-3 space-x-2 text-white transition rounded-lg backup-card bg-hima-blue hover:bg-blue-800">
                <i class="fas fa-user-plus"></i>
                <span>Tambah Anggota</span>
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="p-6 mb-6 shadow backup-card bg-hima-blue hover:bg-blue-800 rounded-xl">
        <form action="{{ route('anggota.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="text-gray-400 fas fa-search"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama, email, NIM, atau jurusan..."
                               class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Role Filter -->
                <div>
                    <select name="role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Role</option>
                        <option value="pengurus" {{ request('role') == 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                        <option value="anggota" {{ request('role') == 'anggota' ? 'selected' : '' }}>Anggota</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between">
                <div class="text-sm text-white">
                    Total: {{ $anggota->total() }} anggota
                </div>
                <div class="flex space-x-3">
                    <button type="reset"
                            onclick="window.location='{{ route('anggota.index') }}'"
                            class="px-4 py-2 text-white transition bg-blue-600 border border-gray-300 rounded-lg hover:bg-blue-700">
                        Reset Filter
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-white transition bg-blue-600 border border-gray-300 rounded-lg hover:bg-blue-700">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Members Table -->
    <div class="overflow-hidden bg-white shadow backup-card rounded-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Anggota
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Informasi
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Role & Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($anggota as $member)
                    <tr class="transition hover:bg-gray-200">
                        <!-- Profile -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    @if($member->foto_profil)
                                        <img class="object-cover w-10 h-10 rounded-full"
                                             src="{{ asset('storage/' . $member->foto_profil) }}"
                                             alt="{{ $member->name }}">
                                    @else
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600">
                                            <span class="text-sm font-bold text-white">
                                                {{ $member->getInitials() }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Information -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $member->nim }}</div>
                            <div class="text-sm text-gray-500">{{ $member->jurusan ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $member->angkatan ?? '-' }}</div>
                        </td>

                        <!-- Role & Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="space-y-2">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $member->role == 'pengurus' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $member->getRoleDisplay() }}
                                </span>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $member->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $member->getStatusDisplay() }}
                                </span>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <!-- View -->
                                <a href="{{ route('anggota.show', $member) }}"
                                   class="text-blue-600 hover:text-blue-900"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Edit -->
                                <a href="{{ route('anggota.edit', $member) }}"
                                   class="text-yellow-600 hover:text-yellow-900"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Toggle Status -->
                                <form action="{{ route('anggota.toggle-status', $member) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Apakah yakin ingin {{ $member->is_active ? 'menonaktifkan' : 'mengaktifkan' }} anggota ini?')">
                                    @csrf
                                    <button type="submit"
                                            class="text-{{ $member->is_active ? 'green' : 'orange' }}-600 hover:text-{{ $member->is_active ? 'green' : 'orange' }}-900"
                                            title="{{ $member->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="fas {{ $member->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form action="{{ route('anggota.destroy', $member) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Apakah yakin ingin menghapus anggota ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="mb-4 text-4xl fas fa-users"></i>
                                <p class="text-lg">Tidak ada data anggota</p>
                                <p class="mt-2">Mulai dengan menambahkan anggota baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($anggota->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $anggota->withQueryString()->links() }}
        </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 gap-6 mt-6 md:grid-cols-3">
        <div class="p-6 text-white shadow backup-card bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Pengurus</p>
                    <p class="mt-2 text-3xl font-bold">{{ \App\Models\User::where('role', 'pengurus')->count() }}</p>
                </div>
                <i class="text-3xl fas fa-users-cog opacity-80"></i>
            </div>
        </div>

        <div class="p-6 text-white shadow backup-card bg-gradient-to-r from-green-500 to-green-600 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Total Anggota</p>
                    <p class="mt-2 text-3xl font-bold">{{ \App\Models\User::where('role', 'anggota')->count() }}</p>
                </div>
                <i class="text-3xl fas fa-user-graduate opacity-80"></i>
            </div>
        </div>

        <div class="p-6 text-white shadow backup-card bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90">Anggota Aktif</p>
                    <p class="mt-2 text-3xl font-bold">{{ \App\Models\User::where('is_active', true)->where('role', '!=', 'admin')->count() }}</p>
                </div>
                <i class="text-3xl fas fa-user-check opacity-80"></i>
            </div>
        </div>
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

<!-- JavaScript for Bulk Actions -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all checkbox
        const selectAll = document.getElementById('selectAll');
        const memberCheckboxes = document.querySelectorAll('.member-checkbox');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                memberCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            });
        }

        // Update select all status
        memberCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(memberCheckboxes).every(cb => cb.checked);
                if (selectAll) {
                    selectAll.checked = allChecked;
                }
            });
        });
    });
</script>
@endsection
