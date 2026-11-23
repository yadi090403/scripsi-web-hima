<?php

namespace App\Http\Controllers;

use App\Models\Backup;
use App\Models\User;
use App\Models\Announcement;
use App\Models\Kegiatan;
use App\Models\OrganizationalStructure;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use ZipArchive;

class BackupController extends Controller
{
    // Tampilkan halaman backup
    public function index()
    {
        $backups = Backup::orderBy('created_at', 'desc')
            ->with('user')
            ->paginate(10);

        $stats = [
            'total_backups' => Backup::count(),
            'total_size' => Backup::where('status', 'completed')->sum('size'),
            'last_backup' => Backup::where('status', 'completed')->latest()->first(),
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'total_announcements' => Announcement::count(),
            'total_kegiatan' => Kegiatan::count(),
            'total_organizational_structures' => OrganizationalStructure::count(),
            'total_dokumen' => Dokumen::count(),
            'total_dokumen_size' => Dokumen::sum('ukuran') ? Dokumen::sum('ukuran') / 1024 : 0, // Convert to MB
        ];

        return view('backup.index', compact('backups', 'stats'));
    }

    // Proses membuat backup baru
    public function store(Request $request)
    {
        $request->validate([
            'backup_type' => 'required|in:full,custom',
            'description' => 'nullable|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            // Tentukan tipe backup berdasarkan pilihan
            $backupType = $request->backup_type === 'full' ? 'full' : 'custom';

            // Konversi checkbox input ke boolean
            $includedData = [
                'users' => $request->backup_type === 'full' ? true : ($request->has('include_users') ? true : false),
                'organizations' => $request->backup_type === 'full' ? true : ($request->has('include_organizations') ? true : false),
                'activities' => $request->backup_type === 'full' ? true : ($request->has('include_activities') ? true : false),
                'announcements' => $request->backup_type === 'full' ? true : ($request->has('include_announcements') ? true : false),
                'documents' => $request->backup_type === 'full' ? true : ($request->has('include_documents') ? true : false),
            ];

            // Buat record backup - JANGAN kirim filepath, biarkan NULL
            $backup = Backup::create([
                'filename' => 'backup_' . now()->format('Ymd_His') . '.zip',
                // filepath tidak dikirim, akan NULL (pending)
                'type' => $backupType,
                'description' => $request->description ?? 'Backup manual',
                'encrypted' => false,
                'status' => 'processing',
                'included_data' => $includedData,
                'user_id' => Auth::id(),
                'progress' => 10
            ]);

            DB::commit();

            // Langsung jalankan backup job secara synchronous (tidak menggunakan queue)
            $backupJob = new \App\Jobs\CreateBackupJob($backup);
            $backupJob->handle();

            // Return JSON response untuk AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Backup berhasil dibuat',
                    'backup_id' => $backup->id,
                    'redirect' => route('backup.index')
                ]);
            }

            return redirect()->route('backup.index')
                ->with('success', 'Backup berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Backup creation error: ' . $e->getMessage());

            // Update status backup menjadi failed
            if (isset($backup)) {
                $backup->update([
                    'status' => 'failed',
                    'description' => 'Backup Error: ' . $e->getMessage(),
                    'progress' => 0
                ]);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat backup: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal membuat backup: ' . $e->getMessage());
        }
    }

    // Download backup file
    public function download($id)
    {
        $backup = Backup::findOrFail($id);

        if ($backup->status !== 'completed') {
            return redirect()->back()->with('error', 'Backup belum selesai atau gagal.');
        }

        $filePath = storage_path('app/' . $backup->filepath);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File backup tidak ditemukan.');
        }

        return Response::download($filePath, $backup->filename, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $backup->filename . '"'
        ]);
    }

    // Hapus backup
    public function destroy($id)
    {
        $backup = Backup::findOrFail($id);

        // Hapus file fisik
        if ($backup->filepath && file_exists(storage_path('app/' . $backup->filepath))) {
            @unlink(storage_path('app/' . $backup->filepath));
        }

        $backup->delete();

        return redirect()->route('backup.index')
            ->with('success', 'Backup berhasil dihapus.');
    }

    // Get backup status (untuk AJAX/polling)
    public function status($id)
    {
        $backup = Backup::findOrFail($id);

        $response = [
            'id' => $backup->id,
            'status' => $backup->status,
            'progress' => $backup->progress ?? 0,
            'size' => $backup->size ? round($backup->size, 2) . ' MB' : '-',
            'created_at' => $backup->created_at->format('d M Y, H:i'),
            'can_download' => $backup->status === 'completed' && $backup->filepath,
            'download_url' => $backup->status === 'completed' ? route('backup.download', $backup->id) : null
        ];

        return response()->json($response);
    }

    // Get backup history (untuk AJAX)
    public function history()
    {
        $backups = Backup::orderBy('created_at', 'desc')
            ->with('user')
            ->limit(10)
            ->get()
            ->map(function ($backup) {
                return [
                    'id' => $backup->id,
                    'name' => $backup->filename,
                    'type' => $backup->type,
                    'size' => $backup->size ? round($backup->size, 2) . ' MB' : '-',
                    'status' => $backup->status,
                    'progress' => $backup->progress ?? 0,
                    'created_at' => $backup->created_at->format('d M Y, H:i'),
                    'created_by' => $backup->user->name ?? 'System',
                    'description' => $backup->description,
                    'can_download' => $backup->status === 'completed' && $backup->filepath,
                    'download_url' => $backup->status === 'completed' ? route('backup.download', $backup->id) : null
                ];
            });

        return response()->json($backups);
    }
}
