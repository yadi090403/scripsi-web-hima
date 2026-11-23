<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 🔒 Matikan FK sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create([
            'name' => 'Mah Mudi',
            'email' => 'admin@himanj.ac.id',
            'nim' => 'ADMIN001',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'fakultas' => 'Fakultas Teknik',
            'jurusan' => 'Teknik Informatika',
            'angkatan' => '2020',
            'no_hp' => '081234567890',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'pengurus@himanj.ac.id',
            'nim' => '2021001',
            'password' => Hash::make('password123'),
            'role' => 'pengurus',
            'fakultas' => 'Fakultas Teknik',
            'jurusan' => 'Teknik Informatika',
            'angkatan' => '2021',
            'no_hp' => '081234567891',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'eko',
            'email' => 'eko@himanj.ac.id',
            'nim' => '33288923',
            'password' => Hash::make('password123'),
            'role' => 'anggota',
            'fakultas' => 'Fakultas Teknik',
            'jurusan' => 'Teknik Informatika',
            'angkatan' => '2022',
            'no_hp' => '081234567892',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Anggota',
            'email' => 'anggota2@himanj.ac.id',
            'nim' => '2021002',
            'password' => Hash::make('password123'),
            'role' => 'anggota',
            'fakultas' => 'Fakultas Teknik',
            'jurusan' => 'Teknik Industri',
            'angkatan' => '2021',
            'no_hp' => '081234567893',
            'is_active' => true,
        ]);
    }
}
