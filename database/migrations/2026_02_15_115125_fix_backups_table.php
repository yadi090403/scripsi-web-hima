<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus tabel lama jika ada
        if (Schema::hasTable('backups')) {
            Schema::drop('backups');
        }

        // Buat tabel baru dengan struktur yang benar
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('filepath')->nullable(); // Harus nullable!
            $table->string('type')->default('full'); // full, user, document
            $table->decimal('size', 10, 2)->default(0); // dalam MB
            $table->text('description')->nullable();
            $table->boolean('encrypted')->default(false);
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->integer('progress')->default(0); // progress 0-100
            $table->json('included_data')->nullable(); // data yang di-backup
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
