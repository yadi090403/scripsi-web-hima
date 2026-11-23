<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class KegiatanSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $kegiatan = [
            [
                'nama_kegiatan' => 'Seminar Teknologi 2024',
                'deskripsi' => 'Seminar tentang perkembangan teknologi terbaru dan implementasinya di dunia industri.',
                'kategori' => 'seminar',
                'jenis' => 'hybrid',
                'tanggal_mulai' => Carbon::now()->addDays(10),
                'tanggal_selesai' => Carbon::now()->addDays(10)->addHours(5),
                'tempat' => 'Aula Utama Kampus',
                'link_meeting' => 'https://meet.google.com/xxx',
                'kuota' => 100,
                'status' => 'rencana',
                'created_by' => $users->first()->id,
            ],
            [
                'nama_kegiatan' => 'Pelatihan Public Speaking',
                'deskripsi' => 'Pelatihan untuk meningkatkan kemampuan berbicara di depan umum.',
                'kategori' => 'pelatihan',
                'jenis' => 'offline',
                'tanggal_mulai' => Carbon::now()->addDays(5),
                'tanggal_selesai' => Carbon::now()->addDays(7),
                'tempat' => 'Ruang Seminar Gedung B',
                'link_meeting' => null,
                'kuota' => 50,
                'status' => 'berlangsung',
                'created_by' => $users->first()->id,
            ],
            [
                'nama_kegiatan' => 'Lomba Programming Competition',
                'deskripsi' => 'Kompetisi pemrograman untuk mahasiswa se-Jawa Barat.',
                'kategori' => 'lomba',
                'jenis' => 'online',
                'tanggal_mulai' => Carbon::now()->addDays(15),
                'tanggal_selesai' => Carbon::now()->addDays(15)->addHours(8),
                'tempat' => null,
                'link_meeting' => 'https://zoom.us/j/xxx',
                'kuota' => 30,
                'status' => 'rencana',
                'created_by' => $users->first()->id,
            ],
            [
                'nama_kegiatan' => 'Rapat Bulanan Pengurus HIMA',
                'deskripsi' => 'Rapat evaluasi dan perencanaan bulanan pengurus HIMA NJ.',
                'kategori' => 'rapat',
                'jenis' => 'offline',
                'tanggal_mulai' => Carbon::now()->addDays(2),
                'tanggal_selesai' => Carbon::now()->addDays(2)->addHours(3),
                'tempat' => 'Ruang Rapat HIMA NJ',
                'link_meeting' => null,
                'kuota' => 20,
                'status' => 'rencana',
                'created_by' => $users->first()->id,
            ],
            [
                'nama_kegiatan' => 'Bakti Sosial ke Panti Asuhan',
                'deskripsi' => 'Kegiatan sosial untuk berbagi dengan anak-anak panti asuhan.',
                'kategori' => 'sosial',
                'jenis' => 'offline',
                'tanggal_mulai' => Carbon::now()->addDays(20),
                'tanggal_selesai' => Carbon::now()->addDays(20)->addHours(6),
                'tempat' => 'Panti Asuhan Kasih Ibu, Bandung',
                'link_meeting' => null,
                'kuota' => 40,
                'status' => 'rencana',
                'created_by' => $users->first()->id,
            ],
        ];

        foreach ($kegiatan as $data) {
            Kegiatan::create($data);
        }

        $this->command->info('Data kegiatan berhasil ditambahkan!');
    }
}
