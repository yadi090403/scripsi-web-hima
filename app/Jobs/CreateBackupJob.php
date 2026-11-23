<?php

namespace App\Jobs;

use App\Models\Backup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class CreateBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $backup;

    public function __construct(Backup $backup)
    {
        $this->backup = $backup;
    }

    public function handle()
    {
        $tempDir = null;

        try {
            // STEP 1: Update status menjadi processing (Progress 10%)
            $this->backup->update(['status' => 'processing', 'progress' => 10]);

            \Log::info('========== BACKUP JOB STARTED ==========', [
                'backup_id' => $this->backup->id,
                'type' => $this->backup->type,
                'included_data' => $this->backup->included_data,
            ]);

            // STEP 2: Buat direktori temporary
            $tempDir = storage_path('app/temp/backup_' . $this->backup->id . '_' . time());

            if (!$this->createDirectory($tempDir)) {
                throw new \Exception('Gagal membuat direktori temporary: ' . $tempDir);
            }

            \Log::info('Created temp directory', ['path' => $tempDir]);

            // STEP 3: Buat file ZIP kosong
            $zipPath = $tempDir . '/backup.zip';
            $zip = new ZipArchive();

            $zipOpen = $zip->open($zipPath, ZipArchive::CREATE);
            if ($zipOpen !== TRUE) {
                throw new \Exception('Tidak bisa membuat file ZIP. Error code: ' . $zipOpen);
            }

            \Log::info('Created ZIP file', ['path' => $zipPath]);

            // STEP 4: Update progress ke 20%
            $this->backup->update(['progress' => 20]);

            // STEP 5: Backup database (Progress 20% → 60%)
            \Log::info('Starting database backup...');
            $this->backupDatabase($zip, $tempDir);

            $this->backup->update(['progress' => 60]);
            \Log::info('Database backup completed');

            // STEP 6: Backup file uploads (Progress 60% → 80%)
            if ($this->backup->included_data['documents'] ?? false) {
                \Log::info('Starting uploads backup...');
                $this->backupUploads($zip);
                \Log::info('Uploads backup completed');
            } else {
                \Log::info('Documents not selected for backup - skipping uploads');
            }

            $this->backup->update(['progress' => 80]);

            // STEP 7: Tambah file info backup
            \Log::info('Adding backup info file...');
            $this->addInfoFile($zip, $tempDir);
            \Log::info('Backup info file added');

            // STEP 8: Tutup file ZIP
            $zip->close();
            \Log::info('ZIP file closed');

            // STEP 9: Buat direktori backup permanen
            $backupMonth = date('Y/m');
            $backupDir = storage_path('app/backups/' . $backupMonth);

            if (!$this->createDirectory($backupDir)) {
                throw new \Exception('Gagal membuat direktori backup permanen: ' . $backupDir);
            }

            \Log::info('Created backup directory', ['path' => $backupDir]);

            // STEP 10: Copy file ZIP ke lokasi permanen
            $finalPath = $backupDir . '/' . $this->backup->filename;

            \Log::info('Copying ZIP file to permanent location', [
                'from' => $zipPath,
                'to' => $finalPath
            ]);

            if (!file_exists($zipPath)) {
                throw new \Exception('File ZIP source tidak ditemukan: ' . $zipPath);
            }

            if (!copy($zipPath, $finalPath)) {
                throw new \Exception('Gagal menyalin file ZIP ke lokasi permanen');
            }

            if (!file_exists($finalPath)) {
                throw new \Exception('File ZIP tidak ditemukan di lokasi tujuan: ' . $finalPath);
            }

            \Log::info('ZIP file copied successfully', ['path' => $finalPath]);

            // STEP 11: Hitung ukuran file dalam MB
            $fileSize = filesize($finalPath);
            $fileSizeMB = $fileSize / (1024 * 1024);

            \Log::info('Backup file size', [
                'bytes' => $fileSize,
                'mb' => round($fileSizeMB, 2)
            ]);

            // STEP 12: Update backup record dengan status COMPLETED (Progress 100%)
            $relativePath = 'backups/' . $backupMonth . '/' . $this->backup->filename;

            // Gunakan forceFill()->save() untuk memastikan atribut tersimpan walau model guarded/fillable membatasi
            $this->backup->forceFill([
                'status' => 'completed',
                'filepath' => $relativePath,
                'size' => round($fileSizeMB, 2),
                'progress' => 100
            ])->save();

            \Log::info('========== BACKUP JOB COMPLETED SUCCESSFULLY ==========', [
                'backup_id' => $this->backup->id,
                'filepath' => $relativePath,
                'size_mb' => round($fileSizeMB, 2)
            ]);

            // STEP 13: Bersihkan direktori temporary
            $this->cleanup($tempDir);
            \Log::info('Temporary directory cleaned up');

        } catch (\Exception $e) {
            // ERROR HANDLING
            \Log::error('========== BACKUP JOB FAILED ==========', [
                'backup_id' => $this->backup->id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'trace' => $e->getTraceAsString()
            ]);

            // Update backup record dengan status FAILED
            $this->backup->update([
                'status' => 'failed',
                'description' => 'Backup Error: ' . $e->getMessage(),
                'progress' => 0
            ]);

            // Bersihkan temp directory jika ada
            if ($tempDir && is_dir($tempDir)) {
                $this->cleanup($tempDir);
                \Log::info('Temporary directory cleaned up after error');
            }

            throw $e;
        }
    }

    /**
     * BACKUP DATABASE - Backup semua tabel ke JSON
     * Progress: 20% → 60%
     */
    private function backupDatabase($zip, $tempDir)
    {
        $includedData = $this->backup->included_data ?? [];
        $tables = [];

        \Log::info('Backup Database - Included Data', $includedData);

        // Cek apakah semua nilai included_data adalah false atau kosong
        $allFalse = empty(array_filter($includedData, function($value) {
            return $value === true;
        }));

        // Tentukan tabel mana saja yang akan di-backup
        if ($includedData['users'] ?? false) {
            $tables = array_merge($tables, ['users']);
        }

        if ($includedData['organizations'] ?? false) {
            $tables = array_merge($tables, ['organizational_structures']);
        }

        if ($includedData['activities'] ?? false) {
            $tables = array_merge($tables, ['kegiatans', 'kegiatan_fotos', 'kietnam_pesertas']);
        }

        if ($includedData['announcements'] ?? false) {
            $tables = array_merge($tables, ['announcements']);
        }

        if ($includedData['documents'] ?? false) {
            $tables = array_merge($tables, ['dokumens']);
        }

        \Log::info('Tables to backup: ' . count($tables), $tables);

        // Jika tidak ada tabel dipilih atau semua false, backup semua tabel (Full Backup)
        if (empty($tables) || $allFalse) {
            \Log::info('No tables selected in included_data, backing up all tables (Full Backup)');
            $tables = [
                'users',
                'organizational_structures',
                'kegiatans',
                'kegiatan_fotos',
                'kegiatan_pesertas',
                'announcements',
                'dokumens'
            ];
        }

        // Backup data untuk setiap tabel
        $totalRows = 0;
        foreach ($tables as $table) {
            try {
                // Cek apakah tabel ada
                if (!DB::getSchemaBuilder()->hasTable($table)) {
                    \Log::warning("Table does not exist in database: {$table}");
                    continue;
                }

                // Query semua data dari tabel
                $data = DB::table($table)->get()->toArray();
                $rowCount = count($data);
                $totalRows += $rowCount;

                \Log::info("Backing up table: {$table}", [
                    'row_count' => $rowCount,
                    'records' => count($data) > 0 ? 'ada data' : 'kosong'
                ]);

                // Convert ke JSON
                $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                if ($jsonData === false) {
                    throw new \Exception('Gagal convert data ke JSON untuk tabel: ' . $table);
                }

                // Simpan ke file JSON temporary
                $filePath = $tempDir . '/' . $table . '.json';
                $bytesWritten = file_put_contents($filePath, $jsonData);

                if ($bytesWritten === false) {
                    throw new \Exception('Gagal menulis file JSON untuk tabel: ' . $table);
                }

                // Tambahkan file ke ZIP dengan struktur: database/nama_tabel.json
                if (!$zip->addFile($filePath, 'database/' . $table . '.json')) {
                    throw new \Exception('Gagal menambahkan file ke ZIP untuk tabel: ' . $table);
                }

                \Log::info("Added to ZIP: database/{$table}.json", [
                    'file_size' => $bytesWritten,
                    'rows' => $rowCount
                ]);

            } catch (\Exception $e) {
                \Log::error("Error backing up table {$table}", [
                    'error' => $e->getMessage()
                ]);
                // Lanjut ke tabel berikutnya jangan stop
                continue;
            }
        }

        \Log::info('Database backup summary', [
            'total_tables' => count($tables),
            'total_rows' => $totalRows
        ]);
    }

    /**
     * BACKUP UPLOADS - Backup semua file dari storage/app/public
     * Progress: 60% → 80%
     */
    private function backupUploads($zip)
    {
        $uploadPath = storage_path('app/public');

        \Log::info('Backup Uploads - Checking path: ' . $uploadPath);

        // Cek apakah direktori uploads ada
        if (!is_dir($uploadPath)) {
            \Log::warning('Upload path does not exist: ' . $uploadPath);
            return;
        }

        $fileCount = 0;
        $failedFiles = 0;
        $totalSize = 0;

        try {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($uploadPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                try {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($uploadPath) + 1);
                        $zipPath = 'uploads/' . str_replace("\\", "/", $relativePath);

                        // Tambahkan file ke ZIP
                        if ($zip->addFile($filePath, $zipPath)) {
                            $fileSize = filesize($filePath);
                            $totalSize += $fileSize;
                            $fileCount++;

                            \Log::info("Added to ZIP: {$zipPath}", [
                                'size' => $fileSize,
                                'real_path' => $filePath
                            ]);
                        } else {
                            $failedFiles++;
                            \Log::warning("Failed to add file to ZIP: {$zipPath}");
                        }
                    }
                } catch (\Exception $e) {
                    $failedFiles++;
                    \Log::warning('Error processing upload file', [
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

        } catch (\Exception $e) {
            \Log::error('Error during backup uploads', [
                'error' => $e->getMessage()
            ]);
        }

        \Log::info('Uploads backup summary', [
            'files_added' => $fileCount,
            'files_failed' => $failedFiles,
            'total_size_mb' => round($totalSize / (1024 * 1024), 2)
        ]);
    }

    /**
     * ADD INFO FILE - Tambahkan file backup_info.json yang berisi metadata backup
     */
    private function addInfoFile($zip, $tempDir)
    {
        try {
            // Load user relationship jika belum di-load
            $userName = 'System';
            if ($this->backup->relationLoaded('user') && $this->backup->user) {
                $userName = $this->backup->user->name;
            } else {
                // Coba load ulang user
                $backupWithUser = Backup::with('user')->find($this->backup->id);
                if ($backupWithUser && $backupWithUser->user) {
                    $userName = $backupWithUser->user->name;
                }
            }

            $info = [
                'backup_id' => $this->backup->id,
                'created_at' => now()->toISOString(),
                'created_by' => $userName,
                'type' => $this->backup->type,
                'included_data' => $this->backup->included_data,
                'app_version' => config('app.version', '1.0.0'),
                'database' => config('database.default'),
                'backup_description' => $this->backup->description,
                'server_info' => [
                    'os' => php_uname(),
                    'php_version' => phpversion(),
                    'laravel_version' => app()->version()
                ]
            ];

            $infoPath = $tempDir . '/backup_info.json';
            $jsonContent = json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            if ($jsonContent === false) {
                throw new \Exception('Gagal convert backup info ke JSON');
            }

            $bytesWritten = file_put_contents($infoPath, $jsonContent);

            if ($bytesWritten === false) {
                throw new \Exception('Gagal menulis backup_info.json');
            }

            // Tambahkan file ke ZIP
            if (!$zip->addFile($infoPath, 'backup_info.json')) {
                throw new \Exception('Gagal menambahkan backup_info.json ke ZIP');
            }

            \Log::info('Backup info file added', [
                'file_size' => $bytesWritten
            ]);

        } catch (\Exception $e) {
            \Log::error('Error adding backup info file', [
                'error' => $e->getMessage()
            ]);
            // Jangan throw error, info file tidak critical
        }
    }

    /**
     * CREATE DIRECTORY - Helper untuk membuat direktori dengan error handling
     */
    private function createDirectory($path)
    {
        try {
            if (!is_dir($path)) {
                if (!mkdir($path, 0755, true)) {
                    return false;
                }
            }
            return true;
        } catch (\Exception $e) {
            \Log::error('Error creating directory', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * CLEANUP - Bersihkan direktori temporary setelah backup selesai
     */
    private function cleanup($tempDir)
    {
        try {
            if (!is_dir($tempDir)) {
                return;
            }

            \Log::info('Starting cleanup of temporary directory', ['path' => $tempDir]);

            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempDir, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            $deletedFiles = 0;
            $deletedDirs = 0;

            foreach ($files as $file) {
                try {
                    if ($file->isDir()) {
                        if (@rmdir($file->getRealPath())) {
                            $deletedDirs++;
                        }
                    } else {
                        if (@unlink($file->getRealPath())) {
                            $deletedFiles++;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete file during cleanup', [
                        'path' => $file->getRealPath(),
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Hapus direktori utama
            if (@rmdir($tempDir)) {
                $deletedDirs++;
            }

            \Log::info('Cleanup completed', [
                'files_deleted' => $deletedFiles,
                'dirs_deleted' => $deletedDirs
            ]);

        } catch (\Exception $e) {
            \Log::error('Error during cleanup', [
                'path' => $tempDir,
                'error' => $e->getMessage()
            ]);
            // Jangan throw error, cleanup failure tidak critical
        }
    }
}
