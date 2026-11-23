<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dokumen;
use App\Models\Kegiatan;
use App\Models\KegiatanPeserta;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPengurus = User::where('role', 'pengurus')->count();
        $totalAnggota  = User::where('role', 'anggota')->count();
        $totalAktif    = User::where('is_active', true)->count();

        // 🔥 INI KUNCINYA
        $dokumenTerbaru = Dokumen::latest()->take(5)->get();
        $totalDokumen   = Dokumen::count();

        $anggotaTerbaru = User::latest()->take(5)->get();

        // Monitoring Data
        $totalKegiatan = Kegiatan::count();
        $kegiatanAktif = Kegiatan::where('status', 'aktif')->count();
        $totalPeserta = KegiatanPeserta::count();

        // Chart data - Kegiatan per bulan
        $chartLabels = [];
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = $date->format('M Y');
            $chartData[] = Kegiatan::whereYear('created_at', $date->year)
                                    ->whereMonth('created_at', $date->month)
                                    ->count();
        }

        // Peserta distribution per kegiatan
        $pesertaLabels = Kegiatan::take(5)->pluck('nama_kegiatan')->toArray();
        $pesertaData = Kegiatan::take(5)->get()->map(function($kegiatan) {
            return KegiatanPeserta::where('kegiatan_id', $kegiatan->id)->count();
        })->toArray();

        // List kegiatan terbaru
        $kegiatanList = Kegiatan::latest()->take(10)->get();

        // Add peserta count to kegiatan
        foreach ($kegiatanList as $kegiatan) {
            $kegiatan->peserta_count = KegiatanPeserta::where('kegiatan_id', $kegiatan->id)->count();
        }

        return view('dashboard', compact(
            'totalPengurus',
            'totalAnggota',
            'totalAktif',
            'totalDokumen',
            'dokumenTerbaru',
            'anggotaTerbaru',
            'totalKegiatan',
            'kegiatanAktif',
            'totalPeserta',
            'chartLabels',
            'chartData',
            'pesertaLabels',
            'pesertaData',
            'kegiatanList'
        ));
    }

    // API endpoint untuk auto-refresh data
    public function monitoringData()
    {
        $totalKegiatan = Kegiatan::count();
        $kegiatanAktif = Kegiatan::where('status', 'aktif')->count();
        $totalPeserta = KegiatanPeserta::count();

        // Chart data - Kegiatan per bulan
        $chartLabels = [];
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = $date->format('M Y');
            $chartData[] = Kegiatan::whereYear('created_at', $date->year)
                                    ->whereMonth('created_at', $date->month)
                                    ->count();
        }

        // Peserta distribution per kegiatan
        $pesertaLabels = Kegiatan::take(5)->pluck('nama_kegiatan')->toArray();
        $pesertaData = Kegiatan::take(5)->get()->map(function($kegiatan) {
            return KegiatanPeserta::where('kegiatan_id', $kegiatan->id)->count();
        })->toArray();

        // List kegiatan terbaru
        $kegiatanList = Kegiatan::latest()->take(10)->get()->map(function($kegiatan) {
            return [
                'id' => $kegiatan->id,
                'nama' => $kegiatan->nama_kegiatan,
                'tanggal_mulai' => $kegiatan->tanggal_mulai->format('d M Y'),
                'peserta_count' => KegiatanPeserta::where('kegiatan_id', $kegiatan->id)->count(),
                'status' => $kegiatan->status
            ];
        });

        return response()->json([
            'totalKegiatan' => $totalKegiatan,
            'kegiatanAktif' => $kegiatanAktif,
            'totalPeserta' => $totalPeserta,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'pesertaLabels' => $pesertaLabels,
            'pesertaData' => $pesertaData,
            'kegiatanList' => $kegiatanList
        ]);
    }
}
