<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organizational_structures', function (Blueprint $table) {
            $table->id();
            $table->string('position_name'); // Contoh: Ketua, Wakil Ketua, Sekretaris
            $table->string('position_level'); // Contoh: Ketua, Koordinator, Anggota
            $table->integer('order')->default(0); // Untuk mengurutkan posisi
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Bisa dikosongkan
            $table->string('custom_name')->nullable(); // Jika tidak ada user yang ditugaskan
            $table->text('description')->nullable(); // Deskripsi tugas
            $table->integer('parent_id')->nullable(); // Untuk struktur hierarki (parent-child)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizational_structures');
    }
};
