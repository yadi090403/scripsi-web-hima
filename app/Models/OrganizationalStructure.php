<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // ✅ TAMBAHKAN INI

class OrganizationalStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_name',
        'position_level',
        'order',
        'user_id',
        'custom_name',
        'photo',
        'description',
        'parent_id'
    ];

    // Relasi ke user jika ada
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke parent (jika ada hierarki)
    public function parent()
    {
        return $this->belongsTo(OrganizationalStructure::class, 'parent_id');
    }

    // Relasi ke children (jika ada hierarki)
    public function children()
    {
        return $this->hasMany(OrganizationalStructure::class, 'parent_id')->orderBy('order');
    }

    // Helper untuk mendapatkan nama yang ditampilkan
    public function getDisplayNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
        return $this->custom_name ?? 'Belum ditentukan';
    }

    // Helper untuk mendapatkan foto
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            // Handle dua format: nama file saja atau dengan folder
            $photoPath = $this->photo;

            // Jika sudah include folder path, gunakan langsung
            if (strpos($this->photo, '/') !== false) {
                if (Storage::disk('public')->exists($this->photo)) {
                    return url('storage/' . $this->photo);
                }
            } else {
                // Jika hanya nama file, tambah folder
                if (Storage::disk('public')->exists('organization-photos/' . $this->photo)) {
                    return url('storage/organization-photos/' . $this->photo);
                }
            }
        }

        // Coba ambil foto dari user
        if ($this->user && $this->user->profile_photo) {
            if (Storage::disk('public')->exists('profile-photos/' . $this->user->profile_photo)) {
                return url('storage/profile-photos/' . $this->user->profile_photo);
            }
        }

        // Fallback ke UI Avatars
        return $this->getFallbackPhotoUrl();
    }

    // Method untuk fallback photo
    private function getFallbackPhotoUrl()
    {
        // Gunakan UI Avatars sebagai fallback
        $name = urlencode($this->display_name);
        $bgColor = $this->getAvatarBackgroundColor();

        return "https://ui-avatars.com/api/?name={$name}&background={$bgColor}&color=fff&size=256";
    }

    // Helper untuk warna avatar
    private function getAvatarBackgroundColor()
    {
        $colors = [
            'Ketua' => 'FF5733',
            'Wakil Ketua' => 'FF8D33',
            'Sekretaris' => '337DFF',
            'Bendahara' => '33FF57',
            'Koordinator' => '9D33FF',
            'Anggota' => '6C757D',
            'Staf' => '20C997'
        ];

        return $colors[$this->position_level] ?? '6C757D';
    }

    // Helper untuk mendapatkan inisial nama
    public function getInitialsAttribute()
    {
        $name = $this->display_name;
        $words = explode(' ', $name);
        $initials = '';

        foreach ($words as $word) {
            if (isset($word[0])) {
                $initials .= strtoupper($word[0]);
            }
        }

        return substr($initials, 0, 2);
    }

    // Helper untuk mendapatkan level warna berdasarkan posisi
    public function getLevelColorAttribute()
    {
        $colors = [
            'Ketua' => 'from-red-500 to-red-600',
            'Wakil Ketua' => 'from-orange-500 to-orange-600',
            'Sekretaris' => 'from-blue-500 to-blue-600',
            'Bendahara' => 'from-green-500 to-green-600',
            'Koordinator' => 'from-purple-500 to-purple-600',
            'Anggota' => 'from-gray-500 to-gray-600'
        ];

        return $colors[$this->position_level] ?? 'from-indigo-500 to-indigo-600';
    }
}
