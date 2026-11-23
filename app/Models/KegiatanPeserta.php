<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanPeserta extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_peserta';

    protected $fillable = [
        'kegiatan_id',
        'user_id',
        'status_pendaftaran',
        'kehadiran',
        'sertifikat_path',
        'catatan'
    ];

    protected $casts = [
        'waktu_daftar' => 'datetime',
        'waktu_hadir' => 'datetime',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Scope untuk status pendaftaran
    public function scopeStatus($query, $status)
    {
        return $query->where('status_pendaftaran', $status);
    }

    // Scope untuk kehadiran
    public function scopeKehadiran($query, $kehadiran)
    {
        return $query->where('kehadiran', $kehadiran);
    }
}
