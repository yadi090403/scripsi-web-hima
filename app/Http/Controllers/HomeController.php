<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dokumen;
use App\Models\Announcement;
use App\Models\OrganizationalStructure;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Stats data
        $totalPengurus = User::where('role', 'pengurus')->count();
        $totalAnggota = User::where('role', 'anggota')->count();
        $anggotaAktif = User::where('is_active', true)->count();
        $totalDokumen = Dokumen::count();

        // Latest announcements for home page (only 5 latest)
        $announcements = Announcement::with('author')->latest()->take(4)->get();

        // Organizational structure data for home page
        $positions = OrganizationalStructure::whereNull('parent_id')
            ->orderBy('order')
            ->with(['children', 'user'])
            ->get();

        return view('home', compact(
            'totalPengurus',
            'totalAnggota',
            'anggotaAktif',
            'totalDokumen',
            'announcements',
            'positions'
        ));
    }
}
