<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AnnouncementController extends Controller
{

    public function index()
    {
        $announcements = Announcement::latest()->with('author')->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        // Cek role admin/pengurus
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'pengurus'])) {
            abort(403, 'Unauthorized action.');
        }

        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'pengurus'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'author_id' => $user->id
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('announcements', 'public');
            $data['image_path'] = $imagePath;
        }

        Announcement::create($data);

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function show($id)
    {
        $announcement = Announcement::with('author')->findOrFail($id);
        return view('announcements.show', compact('announcement'));
    }

    public function baca($id)
    {
        $announcement = Announcement::with('author')->findOrFail($id);
        return view('Home1.baca', compact('announcement'));
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'pengurus'])) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $announcement = Announcement::findOrFail($id);

            // Delete associated image if exists
            if ($announcement->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($announcement->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($announcement->image_path);
            }

            $announcement->delete();

            return redirect()->route('announcements.index')
                ->with('success', 'Pengumuman berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('announcements.index')
                ->with('error', 'Terjadi kesalahan saat menghapus pengumuman: ' . $e->getMessage());
        }
    }
}
