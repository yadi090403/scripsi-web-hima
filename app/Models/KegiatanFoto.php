<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanFoto extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_foto';

    protected $fillable = [
        'kegiatan_id',
        'foto_path',
        'deskripsi_foto',
        'urutan',
        'uploaded_by'
    ];

    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
