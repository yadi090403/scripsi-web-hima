<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DokumenController extends Controller
{
    // Tampilkan semua dokumen
    public function index(Request $request)
    {
        $kategori = $request->get('kategori');
        $search = $request->get('search');

        $dokumens = Dokumen::with('uploader')
            ->when($kategori, function($query) use ($kategori) {
                return $query->where('kategori', $kategori);
            })
            ->when($search, function($query) use ($search) {
                return $query->where('nama_file', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12);

        $kategoriList = Dokumen::select('kategori')->distinct()->pluck('kategori');

        return view('dokumen.index', compact('dokumens', 'kategoriList'));
    }

    // Tampilkan form upload
    public function create()
    {
        $kategoriList = [
            'Proposal' => 'Proposal',
            'Laporan' => 'Laporan',
            'SK' => 'Surat Keputusan',
            'Sertifikat' => 'Sertifikat',
            'Materi' => 'Materi Presentasi',
            'Absensi' => 'Absensi',
            'Keuangan' => 'Laporan Keuangan',
            'Struktur' => 'Struktur Organisasi',
            'Anggaran' => 'Anggaran',
            'Lainnya' => 'Lainnya'
        ];

        return view('dokumen.create', compact('kategoriList'));
    }

    // Proses upload dokumen
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dokumen' => 'required|file|max:10240', // max 10MB
            'nama_file' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi' => 'nullable|string',
            'is_public' => 'boolean',
            'password' => 'nullable|string|min:3'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('dokumen');
        $ekstensi = $file->getClientOriginalExtension();
        $namaAsli = $file->getClientOriginalName();
        $ukuran = $file->getSize() / 1024; // Convert to KB

        // Tentukan tipe file
        $tipe = $this->getTipeFile($ekstensi);

        // Generate nama file unik
        $namaFile = Str::slug($request->nama_file) . '_' . time() . '.' . $ekstensi;
        $path = $file->storeAs('dokumen', $namaFile, 'public');

        // Simpan ke database
        $dokumen = Dokumen::create([
            'nama_file' => $request->nama_file,
            'nama_asli' => $namaAsli,
            'path' => $path,
            'ekstensi' => $ekstensi,
            'ukuran' => $ukuran,
            'tipe' => $tipe,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'password' => $request->password,
            'uploaded_by' => auth()->id(),
            'is_public' => $request->boolean('is_public', true)
        ]);

        return redirect()->route('dokumen.index')
            ->with('success', 'Dokumen berhasil diupload!');
    }

    // Download dokumen
    public function download($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        // Cek apakah dokumen public atau user memiliki akses
        if (!$dokumen->is_public && auth()->id() != $dokumen->uploaded_by) {
            if (!auth()->user()->isAdmin() && !auth()->user()->isPengurus()) {
                abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
            }
        }

        // Cek password jika ada
        if ($dokumen->password) {
            // Anda bisa implementasikan password check di sini
            // Misalnya dengan session atau form konfirmasi
        }

        // Increment download count
        $dokumen->incrementDownloadCount();

        // Download file
        return Storage::disk('public')->download($dokumen->path, $dokumen->nama_file . '.' . $dokumen->ekstensi);
    }

    // Preview dokumen (khusus PDF)
    public function preview($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        if ($dokumen->tipe != 'pdf') {
            return redirect()->route('dokumen.index')
                ->with('error', 'Preview hanya tersedia untuk file PDF.');
        }

        if (!Storage::disk('public')->exists($dokumen->path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $path = Storage::disk('public')->path($dokumen->path);

        return response()->file($path);
    }

    // Hapus dokumen
    public function destroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        // Cek izin
        if (auth()->id() != $dokumen->uploaded_by && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki izin menghapus dokumen ini.');
        }

        // Hapus file dari storage
        Storage::disk('public')->delete($dokumen->path);

        // Hapus dari database
        $dokumen->delete();

        return redirect()->route('dokumen.index')
            ->with('success', 'Dokumen berhasil dihapus!');
    }

    // Helper: Tentukan tipe file berdasarkan ekstensi
    private function getTipeFile($ekstensi)
    {
        $pdf = ['pdf'];
        $doc = ['doc', 'docx'];
        $excel = ['xls', 'xlsx', 'csv'];
        $powerpoint = ['ppt', 'pptx'];
        $image = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];
        $zip = ['zip', 'rar', '7z'];

        if (in_array($ekstensi, $pdf)) return 'pdf';
        if (in_array($ekstensi, $doc)) return 'doc';
        if (in_array($ekstensi, $excel)) return 'xls';
        if (in_array($ekstensi, $powerpoint)) return 'ppt';
        if (in_array($ekstensi, $image)) return 'image';
        if (in_array($ekstensi, $zip)) return 'zip';

        return 'lainnya';
    }

    // Statistik dokumen
    public function stats()
    {
        $totalDokumen = Dokumen::count();
        $totalUkuran = Dokumen::sum('ukuran') / 1024; // Convert to MB
        $totalDownload = Dokumen::sum('download_count');
        $kategoriStats = Dokumen::select('kategori', \DB::raw('COUNT(*) as total'))
            ->groupBy('kategori')
            ->get();

        return view('dokumen.stats', compact('totalDokumen', 'totalUkuran', 'totalDownload', 'kategoriStats'));
    }
}
