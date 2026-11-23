<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    public function test_admin_role_is_detected_case_insensitively(): void
    {
        $user = new User([
            'name' => 'Admin Test',
            'email' => 'admincase@example.com',
            'nim' => 'ADMINCASE001',
            'password' => bcrypt('password123'),
            'role' => 'Admin',
            'is_active' => true,
        ]);

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isPengurus());
        $this->assertFalse($user->isAnggota());
    }

    public function test_pengurus_and_anggota_roles_stay_out_of_admin_mode(): void
    {
        $pengurus = new User([
            'name' => 'Pengurus Test',
            'email' => 'penguruscase@example.com',
            'nim' => 'PENGURUS001',
            'password' => bcrypt('password123'),
            'role' => 'Pengurus',
            'is_active' => true,
        ]);

        $anggota = new User([
            'name' => 'Anggota Test',
            'email' => 'anggotacase@example.com',
            'nim' => 'ANGGOTA001',
            'password' => bcrypt('password123'),
            'role' => 'Anggota',
            'is_active' => true,
        ]);

        $this->assertFalse($pengurus->isAdmin());
        $this->assertTrue($pengurus->isPengurus());
        $this->assertFalse($anggota->isAdmin());
        $this->assertTrue($anggota->isAnggota());
    }
}
