<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Dokumen extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_file',
        'nama_asli',
        'path',
        'ekstensi',
        'ukuran',
        'tipe',
        'kategori',
        'deskripsi',
        'password',
        'uploaded_by',
        'is_public',
        'download_count'
    ];

    protected $appends = ['icon', 'ukuran_format', 'uploaded_at'];

    // Relasi dengan user yang mengupload
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Accessor untuk icon berdasarkan tipe file
    public function getIconAttribute()
    {
        $icons = [
            'pdf' => 'fas fa-file-pdf',
            'doc' => 'fas fa-file-word',
            'docx' => 'fas fa-file-word',
            'xls' => 'fas fa-file-excel',
            'xlsx' => 'fas fa-file-excel',
            'ppt' => 'fas fa-file-powerpoint',
            'pptx' => 'fas fa-file-powerpoint',
            'image' => 'fas fa-file-image',
            'zip' => 'fas fa-file-archive',
            'lainnya' => 'fas fa-file'
        ];

        return $icons[$this->tipe] ?? 'fas fa-file';
    }

    // Accessor untuk format ukuran file
    public function getUkuranFormatAttribute()
    {
        if ($this->ukuran < 1024) {
            return number_format($this->ukuran, 2) . ' KB';
        } elseif ($this->ukuran < 1048576) {
            return number_format($this->ukuran / 1024, 2) . ' MB';
        } else {
            return number_format($this->ukuran / 1048576, 2) . ' GB';
        }
    }

    // Accessor untuk waktu upload yang friendly
    public function getUploadedAtAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Method untuk download
    public function incrementDownloadCount()
    {
        $this->download_count++;
        $this->save();
    }

    // Method untuk cek apakah file ada
    public function fileExists()
    {
        return Storage::disk('public')->exists($this->path);
    }

    // Method untuk mendapatkan full URL
    public function getUrl()
    {
        return Storage::disk('public')->url($this->path);
    }

    // Scope untuk dokumen public
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    // Scope untuk filter kategori
    public function scopeKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }
}
