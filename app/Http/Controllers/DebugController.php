<?php

namespace App\Http\Controllers;

use App\Models\OrganizationalStructure;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DebugController extends Controller
{
    /**
     * Display debug information for organizational structure photos
     */
    public function photos(): View
    {
        // Get all positions with photos
        $positions = OrganizationalStructure::whereNotNull('photo')->get();

        // Get physical files
        $filesOnDisk = [];
        if (Storage::disk('public')->exists('organization-photos')) {
            $filesOnDisk = Storage::disk('public')->files('organization-photos');
        }

        // Calculate statistics
        $stats = [
            'totalPositions' => OrganizationalStructure::count(),
            'positionsWithPhoto' => OrganizationalStructure::whereNotNull('photo')->count(),
            'filesOnDisk' => count($filesOnDisk),
            'storageRoot' => storage_path('app/public'),
            'appUrl' => config('app.url'),
        ];

        return view('debug.photos', compact('positions', 'filesOnDisk', 'stats'));
    }
}
