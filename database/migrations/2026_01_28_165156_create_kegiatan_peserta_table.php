<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kegiatan_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('status_pendaftaran', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->enum('kehadiran', ['hadir', 'tidak_hadir', 'izin'])->nullable();
            $table->string('sertifikat_path')->nullable();
            $table->timestamp('waktu_daftar')->useCurrent();
            $table->timestamp('waktu_hadir')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['kegiatan_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kegiatan_peserta');
    }
};
