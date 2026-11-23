<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'author_id',
        'image_path'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    public function getExcerptAttribute()
    {
        return \Illuminate\Support\Str::limit(strip_tags($this->content), 150);
    }
}
