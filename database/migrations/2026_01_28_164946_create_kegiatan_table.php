<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->text('deskripsi');
            $table->enum('kategori', ['seminar', 'pelatihan', 'lomba', 'rapat', 'sosial', 'internal', 'eksternal', 'lainnya']);
            $table->enum('jenis', ['online', 'offline', 'hybrid']);
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->string('tempat')->nullable();
            $table->string('link_meeting')->nullable();
            $table->integer('kuota')->default(0);
            $table->enum('status', ['rencana', 'berlangsung', 'selesai', 'dibatalkan', 'arsip'])->default('rencana');
            $table->string('foto_utama')->nullable();
            $table->string('dokumen_proposal')->nullable();
            $table->string('dokumen_lpj')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kegiatan');
    }
};
