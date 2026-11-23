// database/migrations/2024_01_01_create_dokumens_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('nama_asli');
            $table->string('path');
            $table->string('ekstensi');
            $table->decimal('ukuran', 10, 2); // dalam KB
            $table->enum('tipe', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'image', 'zip', 'lainnya']);
            $table->string('kategori'); // Proposal, Laporan, SK, Sertifikat, dll
            $table->text('deskripsi')->nullable();
            $table->string('password')->nullable(); // untuk dokumen terproteksi
            $table->foreignId('uploaded_by')->constrained('users');
            $table->boolean('is_public')->default(true);
            $table->integer('download_count')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumens');
    }
};
