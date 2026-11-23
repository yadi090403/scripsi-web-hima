<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kegiatan';

    protected $fillable = [
        'nama_kegiatan',
        'deskripsi',
        'kategori',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'tempat',
        'link_meeting',
        'kuota',
        'status',
        'foto_utama',
        'dokumen_proposal',
        'dokumen_lpj',
        'created_by'
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    // Relasi dengan user yang membuat kegiatan
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi dengan foto kegiatan
    public function fotos()
    {
        return $this->hasMany(KegiatanFoto::class, 'kegiatan_id')->orderBy('urutan');
    }

    // Relasi dengan peserta
    public function peserta()
    {
        return $this->hasMany(KegiatanPeserta::class, 'kegiatan_id');
    }

    // Scope untuk status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope untuk kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Scope aktif (tidak diarsip)
    public function scopeAktif($query)
    {
        return $query->where('status', '!=', 'arsip');
    }

    // Cek apakah masih ada kuota
    public function hasKuota()
    {
        if ($this->kuota == 0) return true;

        $terdaftar = $this->peserta()->where('status_pendaftaran', 'diterima')->count();
        return $terdaftar < $this->kuota;
    }

    // Hitung sisa kuota
    public function sisaKuota()
    {
        if ($this->kuota == 0) return '∞';

        $terdaftar = $this->peserta()->where('status_pendaftaran', 'diterima')->count();
        return $this->kuota - $terdaftar;
    }
}
