<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $role = request('role');
        $status = request('status');

        $query = User::where('role', '!=', 'admin');

        // Search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }

        // Filter role
        if ($role && in_array($role, ['pengurus', 'anggota'])) {
            $query->where('role', $role);
        }

        // Filter status
        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $anggota = $query->latest()->paginate(10);

        return view('anggota.index', compact('anggota'));
    }

    /** 
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('anggota.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'nim' => 'required|string|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'nullable|in:pengurus,anggota',
            'fakultas' => 'nullable|string|max:100',
            'jurusan' => 'nullable|string|max:100',
            'angkatan' => 'nullable|string|max:4',
            'no_hp' => 'nullable|string|max:15',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'divisi' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:100',
            'periode' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profiles', 'public');
        }

        // Create user
        $role = strtolower((string) ($validated['role'] ?? 'anggota'));

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nim' => $validated['nim'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'fakultas' => $validated['fakultas'] ?? null,
            'jurusan' => $validated['jurusan'] ?? null,
            'angkatan' => $validated['angkatan'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'agama' => $validated['agama'] ?? null,
            'divisi' => $validated['divisi'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'periode' => $validated['periode'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'foto_profil' => $photoPath,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Pastikan bukan admin
        if ($user->isAdmin()) {
            abort(404);
        }

        return view('anggota.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Pastikan bukan admin
        if ($user->isAdmin()) {
            abort(404);
        }

        return view('anggota.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Pastikan bukan admin
        if ($user->isAdmin()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nim' => 'required|string|unique:users,nim,' . $user->id,
            'role' => 'nullable|in:pengurus,anggota',
            'fakultas' => 'nullable|string|max:100',
            'jurusan' => 'nullable|string|max:100',
            'angkatan' => 'nullable|string|max:4',
            'no_hp' => 'nullable|string|max:15',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'divisi' => 'nullable|string|max:100',
            'jabatan' => 'nullable|string|max:100',
            'periode' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            $photoPath = $request->file('photo')->store('profiles', 'public');
            $validated['foto_profil'] = $photoPath;
        }

        // Update user
        $validated['role'] = strtolower((string) ($validated['role'] ?? $user->role));
        $user->update($validated);

        return redirect()->route('anggota.index')
            ->with('success', 'Data anggota berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Pastikan bukan admin
        if ($user->isAdmin()) {
            abort(404);
        }

        // Delete photo if exists
        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->delete();

        return redirect()->route('anggota.index')
            ->with('success', 'Anggota berhasil dihapus!');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(User $user)
    {
        // Pastikan bukan admin
        if ($user->isAdmin()) {
            abort(404);
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Anggota berhasil $status!");
    }

    /**
     * Reset password anggota
     */
    public function resetPassword(Request $request, User $user)
    {
        // Pastikan bukan admin
        if ($user->isAdmin()) {
            abort(404);
        }

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil direset!');
    }
}
