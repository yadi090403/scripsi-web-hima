<?php

namespace App\Http\Controllers;

use App\Models\OrganizationalStructure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // ✅ TAMBAHKAN INI

class OrganizationalStructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua posisi dengan parent null (top level)
        $positions = OrganizationalStructure::whereNull('parent_id')
            ->orderBy('order')
            ->with(['children', 'user'])
            ->get();

        // Ambil semua user untuk dropdown
        $users = User::where('role', '!=', 'admin')->get();

        return view('organizational-structure.index', compact('positions', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya admin dan pengurus yang bisa membuat
        if (!in_array(Auth::user()->role, ['admin', 'pengurus'])) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::where('role', '!=', 'admin')->get();
        $parents = OrganizationalStructure::all();

        return view('organizational-structure.create', compact('users', 'parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'pengurus'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'position_name' => 'required|string|max:100',
            'position_level' => 'required|string|max:50',
            'order' => 'nullable|integer',
            'user_id' => 'nullable|exists:users,id',
            'custom_name' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // ✅ TAMBAHKAN VALIDASI FOTO
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:organizational_structures,id'
        ]);

        // Upload foto jika ada
        $photoPath = null;
        if ($request->hasFile('photo')) {
            // Simpan path lengkap (contoh: organization-photos/abc123.jpg)
            $photoPath = $request->file('photo')->store('organization-photos', 'public');
        }

        // Jika custom_name tidak diisi dan user_id ada, ambil nama dari user
        if (empty($request->custom_name) && $request->user_id) {
            $user = User::find($request->user_id);
            $request->merge(['custom_name' => $user->name]);
        }

        OrganizationalStructure::create(array_merge($request->except('photo'), [
            'photo' => $photoPath
        ]));

        return redirect()->route('organizational-structure.index')
            ->with('success', 'Posisi berhasil ditambahkan ke struktur organisasi.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'pengurus'])) {
            abort(403, 'Unauthorized action.');
        }

        $position = OrganizationalStructure::findOrFail($id);
        $users = User::where('role', '!=', 'admin')->get();
        $parents = OrganizationalStructure::where('id', '!=', $id)->get();

        return view('organizational-structure.edit', compact('position', 'users', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'pengurus'])) {
            abort(403, 'Unauthorized action.');
        }

        $position = OrganizationalStructure::findOrFail($id);

        $request->validate([
            'position_name' => 'required|string|max:100',
            'position_level' => 'required|string|max:50',
            'order' => 'nullable|integer',
            'user_id' => 'nullable|exists:users,id',
            'custom_name' => 'nullable|string|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // ✅ TAMBAHKAN VALIDASI FOTO
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:organizational_structures,id'
        ]);

        // Hindari circular reference
        if ($request->parent_id == $id) {
            return back()->withErrors(['parent_id' => 'Tidak bisa memilih diri sendiri sebagai parent.']);
        }

        // ✅ TAMBAHKAN LOGIKA UPLOAD FOTO DI SINI
        // Upload foto baru jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada (hitung path yang tersimpan)
            if ($position->photo) {
                if (strpos($position->photo, '/') !== false) {
                    Storage::disk('public')->delete($position->photo);
                } else {
                    Storage::disk('public')->delete('organization-photos/' . $position->photo);
                }
            }

            $photoPath = $request->file('photo')->store('organization-photos', 'public');
            $position->photo = $photoPath; // simpan path lengkap
        }

        if ($request->has('remove_photo') && $request->remove_photo == '1') {
            if ($position->photo) {
                if (strpos($position->photo, '/') !== false) {
                    Storage::disk('public')->delete($position->photo);
                } else {
                    Storage::disk('public')->delete('organization-photos/' . $position->photo);
                }
            }
            $photoPath = null;
            $position->photo = null;
        }

        // Jika custom_name tidak diisi dan user_id ada, ambil nama dari user
        if (empty($request->custom_name) && $request->user_id) {
            $user = User::find($request->user_id);
            $request->merge(['custom_name' => $user->name]);
        }

        $position->update(array_merge($request->except(['photo', 'remove_photo']), [
            'photo' => $photoPath ?? $position->photo
        ]));

        return redirect()->route('organizational-structure.index')
            ->with('success', 'Posisi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!in_array(Auth::user()->role, ['admin', 'pengurus'])) {
            abort(403, 'Unauthorized action.');
        }

        $position = OrganizationalStructure::findOrFail($id);

        // ✅ PERBAIKAN: Jika ada children, pindahkan ke parent dari posisi yang dihapus
        // Dapatkan parent_id dari posisi yang akan dihapus
        $parentId = $position->parent_id;

        // Update semua children untuk menunjuk ke parent baru
        OrganizationalStructure::where('parent_id', $id)
            ->update(['parent_id' => $parentId]);

        // Hapus foto jika ada
        if ($position->photo) {
            if (strpos($position->photo, '/') !== false) {
                Storage::disk('public')->delete($position->photo);
            } else {
                Storage::disk('public')->delete('organization-photos/' . $position->photo);
            }
        }

        $position->delete();

        return redirect()->route('organizational-structure.index')
            ->with('success', 'Posisi berhasil dihapus. Anggota bawahan telah dipindahkan ke atasan.');
    }

    /**
     * Update order of positions
     */
    public function updateOrder(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'pengurus'])) {
            abort(403, 'Unauthorized action.');
        }

        $positions = $request->input('positions');

        foreach ($positions as $position) {
            OrganizationalStructure::where('id', $position['id'])->update([
                'order' => $position['order'],
                'parent_id' => $position['parent_id'] ?? null
            ]);
        }

        return response()->json(['success' => true]);
    }
}
