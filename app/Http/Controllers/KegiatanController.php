<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\KegiatanFoto;
use App\Models\KegiatanPeserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kegiatan::query();

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori
        if ($request->has('kategori') && $request->kategori != 'all') {
            $query->where('kategori', $request->kategori);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_kegiatan', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $kegiatan = $query->orderBy('tanggal_mulai', 'desc')->paginate(10);

        $statuses = [
            'all' => 'Semua Status',
            'rencana' => 'Rencana',
            'berlangsung' => 'Berlangsung',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'arsip' => 'Arsip'
        ];

        $kategories = [
            'all' => 'Semua Kategori',
            'seminar' => 'Seminar',
            'pelatihan' => 'Pelatihan',
            'lomba' => 'Lomba',
            'rapat' => 'Rapat',
            'sosial' => 'Sosial',
            'internal' => 'Internal',
            'eksternal' => 'Eksternal'
        ];

        return view('kegiatan.index', compact('kegiatan', 'statuses', 'kategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kegiatan' => 'required|max:255',
            'deskripsi' => 'required',
            'kategori' => 'required|in:seminar,pelatihan,lomba,rapat,sosial,internal,eksternal,lainnya',
            'jenis' => 'required|in:online,offline,hybrid',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tempat' => 'required_if:jenis,offline,hybrid',
            'link_meeting' => 'nullable|url|required_if:jenis,online,hybrid',
            'kuota' => 'nullable|integer|min:0',
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'dokumen_proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle upload foto utama
        $fotoUtamaPath = null;
        if ($request->hasFile('foto_utama')) {
            $fotoUtamaPath = $request->file('foto_utama')->store('kegiatan/foto_utama', 'public');
        }

        // Handle upload dokumen proposal
        $dokumenProposalPath = null;
        if ($request->hasFile('dokumen_proposal')) {
            $dokumenProposalPath = $request->file('dokumen_proposal')->store('kegiatan/dokumen', 'public');
        }

        // Simpan kegiatan
        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'jenis' => $request->jenis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'tempat' => $request->tempat,
            'link_meeting' => $request->link_meeting,
            'kuota' => $request->kuota ?? 0,
            'status' => $request->status ?? 'rencana',
            'foto_utama' => $fotoUtamaPath,
            'dokumen_proposal' => $dokumenProposalPath,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kegiatan = Kegiatan::with(['fotos', 'peserta.user', 'creator'])->findOrFail($id);
        $peserta = $kegiatan->peserta()->with('user')->paginate(10);

        return view('kegiatan.show', compact('kegiatan', 'peserta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        return view('kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_kegiatan' => 'required|max:255',
            'deskripsi' => 'required',
            'kategori' => 'required|in:seminar,pelatihan,lomba,rapat,sosial,internal,eksternal,lainnya',
            'jenis' => 'required|in:online,offline,hybrid',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tempat' => 'required_if:jenis,offline,hybrid',
            'link_meeting' => 'nullable|url|required_if:jenis,online,hybrid',
            'kuota' => 'nullable|integer|min:0',
            'foto_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'dokumen_proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'dokumen_lpj' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle upload foto utama baru
        if ($request->hasFile('foto_utama')) {
            // Hapus foto lama jika ada
            if ($kegiatan->foto_utama) {
                Storage::disk('public')->delete($kegiatan->foto_utama);
            }
            $fotoUtamaPath = $request->file('foto_utama')->store('kegiatan/foto_utama', 'public');
            $kegiatan->foto_utama = $fotoUtamaPath;
        }

        // Handle upload dokumen proposal baru
        if ($request->hasFile('dokumen_proposal')) {
            if ($kegiatan->dokumen_proposal) {
                Storage::disk('public')->delete($kegiatan->dokumen_proposal);
            }
            $dokumenProposalPath = $request->file('dokumen_proposal')->store('kegiatan/dokumen', 'public');
            $kegiatan->dokumen_proposal = $dokumenProposalPath;
        }

        // Handle upload dokumen LPJ
        if ($request->hasFile('dokumen_lpj')) {
            if ($kegiatan->dokumen_lpj) {
                Storage::disk('public')->delete($kegiatan->dokumen_lpj);
            }
            $dokumenLpjPath = $request->file('dokumen_lpj')->store('kegiatan/dokumen', 'public');
            $kegiatan->dokumen_lpj = $dokumenLpjPath;
        }

        // Update data lainnya
        $kegiatan->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'jenis' => $request->jenis,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'tempat' => $request->tempat,
            'link_meeting' => $request->link_meeting,
            'kuota' => $request->kuota ?? 0,
            'status' => $request->status,
        ]);

        return redirect()->route('kegiatan.show', $kegiatan->id)
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        // Hapus file foto utama jika ada
        if ($kegiatan->foto_utama) {
            Storage::disk('public')->delete($kegiatan->foto_utama);
        }

        // Hapus file dokumen jika ada
        if ($kegiatan->dokumen_proposal) {
            Storage::disk('public')->delete($kegiatan->dokumen_proposal);
        }

        if ($kegiatan->dokumen_lpj) {
            Storage::disk('public')->delete($kegiatan->dokumen_lpj);
        }

        // Hapus foto-foto terkait
        foreach ($kegiatan->fotos as $foto) {
            Storage::disk('public')->delete($foto->foto_path);
            $foto->delete();
        }

        // Hapus data peserta
        $kegiatan->peserta()->delete();

        // Hapus kegiatan
        $kegiatan->delete();

        return redirect()->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    /**
     * Upload foto tambahan untuk kegiatan
     */
    public function uploadFoto(Request $request, $id)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'deskripsi_foto' => 'nullable|max:200'
        ]);

        $kegiatan = Kegiatan::findOrFail($id);

        $fotoPath = $request->file('foto')->store('kegiatan/foto_tambahan', 'public');

        KegiatanFoto::create([
            'kegiatan_id' => $kegiatan->id,
            'foto_path' => $fotoPath,
            'deskripsi_foto' => $request->deskripsi_foto,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Foto berhasil diupload.');
    }

    /**
     * Hapus foto kegiatan
     */
    public function hapusFoto($id, $fotoId)
    {
        $foto = KegiatanFoto::where('kegiatan_id', $id)
            ->where('id', $fotoId)
            ->firstOrFail();

        Storage::disk('public')->delete($foto->foto_path);
        $foto->delete();

        return redirect()->back()
            ->with('success', 'Foto berhasil dihapus.');
    }

    /**
     * Update status kegiatan
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:rencana,berlangsung,selesai,dibatalkan,arsip'
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->status = $request->status;
        $kegiatan->save();

        return redirect()->back()
            ->with('success', 'Status kegiatan berhasil diperbarui.');
    }
}
