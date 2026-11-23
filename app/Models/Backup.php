<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Backup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'filename',
        'filepath',
        'type',
        'size',
        'description',
        'encrypted',
        'status',
        'included_data',
        'user_id'
    ];

    protected $casts = [
        'encrypted' => 'boolean',
        'included_data' => 'array',
        'size' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method untuk format size
    public function getFormattedSizeAttribute()
    {
        $size = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    // Helper method untuk mendapatkan URL download
    public function getDownloadUrlAttribute()
    {
        return route('backup.download', $this->id);
    }

    // Helper method untuk status badge class
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'partial' => 'bg-blue-100 text-blue-800'
        ];

        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}
