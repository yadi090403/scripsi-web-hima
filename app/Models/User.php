<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'nim',
        'password',
        'role',
        'fakultas',
        'jurusan',
        'angkatan',
        'no_hp',
        'jenis_kelamin', // ← pastikan ada
        'tanggal_lahir',
        'agama',
        'divisi',
        'jabatan',
        'periode',
        'alamat',
        'foto_profil',
        'is_active',
    ];

    protected $hidden = [
        'password' => 'hashed',
        'is_active' => 'boolean',
        'tanggal_lahir' => 'date',
        'last_login_at' => 'datetime',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ==================== HELPER METHODS ====================

    public function normalizedRole(): string
    {
        return strtolower((string) ($this->role ?? ''));
    }

    // Method untuk mengecek role
    public function isAdmin(): bool
    {
        return $this->normalizedRole() === 'admin';
    }

    public function isPengurus(): bool
    {
        return $this->normalizedRole() === 'pengurus';
    }

    public function isAnggota(): bool
    {
        return $this->normalizedRole() === 'anggota';
    }

    // Method untuk display role
    public function getRoleDisplay(): string
    {
        return match($this->role) {
            'admin' => 'Admin',
            'pengurus' => 'Pengurus',
            'anggota' => 'Anggota',
            default => ucfirst($this->role),
        };
    }

    // Method untuk badge color berdasarkan role
    public function getRoleBadgeClass(): string
    {
        return match($this->role) {
            'admin' => 'bg-red-100 text-red-800',
            'pengurus' => 'bg-blue-100 text-blue-800',
            'anggota' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Method untuk inisial nama (untuk avatar)
    public function getInitials(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }

        return substr($initials, 0, 2);
    }

    // Method untuk mendapatkan URL foto profil
    public function getProfilePhotoUrl(): string
    {
        if ($this->foto_profil) {
            return asset('storage/' . $this->foto_profil);
        }

        // Default avatar berdasarkan role
        $colors = [
            'admin' => 'bg-red-500',
            'pengurus' => 'bg-blue-500',
            'anggota' => 'bg-gray-500',
        ];

        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) .
               "&color=FFFFFF&background=" . substr($colors[$this->role] ?? 'bg-gray-500', 3, 6);
    }

    public function getOrganizationPhotoUrl(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);

            // Cek apakah file benar-benar ada
            // if (file_exists(public_path($path))) {
            //     return asset($path);
            // }

            // Jika file tidak ada di storage, fallback ke default
        }

        // Default avatar
        $levelColors = [
            'Ketua' => 'DC2626',
            'Wakil Ketua' => 'F97316',
            'Sekretaris' => '3B82F6',
            'Bendahara' => '10B981',
            'Koordinator' => '8B5CF6',
        ];

        $background = $levelColors[$this->position_level] ?? '6B7280';

        // Ambil 2 huruf pertama untuk inisial
        $nameParts = explode(' ', $this->display_name);
        $initials = '';

        if (count($nameParts) >= 2) {
            $initials = substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1);
        } else {
            $initials = substr($this->display_name, 0, 2);
        }

        return "https://ui-avatars.com/api/?name=" . urlencode($initials) .
            "&color=FFFFFF&background=" . $background .
            "&size=128&bold=true";
    }

    // Method untuk status aktif
    public function getStatusDisplay(): string
    {
        return $this->is_active ? 'Aktif' : 'Non-Aktif';
    }

    public function getStatusBadgeClass(): string
    {
        return $this->is_active
            ? 'bg-green-100 text-green-800'
            : 'bg-red-100 text-red-800';
    }
}
