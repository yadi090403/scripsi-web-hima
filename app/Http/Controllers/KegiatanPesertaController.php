<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanPeserta;
use Illuminate\Http\Request;

class KegiatanPesertaController extends Controller
{
    /**
     * Daftar sebagai peserta kegiatan
     */
    public function daftar(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Cek apakah sudah terdaftar
        $sudahDaftar = KegiatanPeserta::where('kegiatan_id', $id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($sudahDaftar) {
            return redirect()->back()
                ->with('error', 'Anda sudah terdaftar pada kegiatan ini.');
        }

        // Cek kuota jika ada
        if (!$kegiatan->hasKuota()) {
            return redirect()->back()
                ->with('error', 'Kuota kegiatan sudah penuh.');
        }

        // Daftarkan peserta
        KegiatanPeserta::create([
            'kegiatan_id' => $kegiatan->id,
            'user_id' => auth()->id(),
            'status_pendaftaran' => 'menunggu',
            'waktu_daftar' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Pendaftaran berhasil. Menunggu konfirmasi.');
    }

    /**
     * Batalkan pendaftaran
     */
    public function batalkanPendaftaran($id)
    {
        $pendaftaran = KegiatanPeserta::where('kegiatan_id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $pendaftaran->delete();

        return redirect()->back()
            ->with('success', 'Pendaftaran berhasil dibatalkan.');
    }

    /**
     * Konfirmasi peserta
     */
    public function konfirmasiPeserta(Request $request, $kegiatanId, $userId)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak'
        ]);

        $peserta = KegiatanPeserta::where('kegiatan_id', $kegiatanId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $peserta->status_pendaftaran = $request->status;
        $peserta->save();

        return redirect()->back()
            ->with('success', 'Status peserta berhasil diperbarui.');
    }

    /**
     * Update kehadiran
     */
    public function updateKehadiran(Request $request, $kegiatanId, $userId)
    {
        $request->validate([
            'kehadiran' => 'required|in:hadir,tidak_hadir,izin'
        ]);

        $peserta = KegiatanPeserta::where('kegiatan_id', $kegiatanId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $peserta->kehadiran = $request->kehadiran;
        $peserta->waktu_hadir = $request->kehadiran == 'hadir' ? now() : null;
        $peserta->save();

        return redirect()->back()
            ->with('success', 'Kehadiran berhasil diperbarui.');
    }
}
