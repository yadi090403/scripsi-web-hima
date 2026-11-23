<?php

// Test file untuk debug foto
// Pastikan environment sudah setup dengan benar

putenv('APP_ENV=local');
putenv('APP_DEBUG=true');

require_once __DIR__ . '/vendor/autoload.php';

try {
    $app = require __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Http\Kernel::class)->bootstrap();

    echo "Database Connection: OK\n\n";

    // Get positions
    $positions = \App\Models\OrganizationalStructure::all();

    echo "Total Positions: " . count($positions) . "\n\n";

    foreach($positions as $pos) {
        echo "Position ID: " . $pos->id . "\n";
        echo "  Name: " . $pos->position_name . "\n";
        echo "  Photo (DB): " . ($pos->photo ?? 'NULL') . "\n";
        echo "  Display Name: " . $pos->display_name . "\n";
        echo "  Initials: " . $pos->initials . "\n";
        echo "  Level Color: " . $pos->level_color . "\n";

        // Check file
        if($pos->photo) {
            $file1 = 'storage/app/public/' . $pos->photo;
            $file2 = 'storage/app/public/organization-photos/' . basename($pos->photo);

            echo "  File Check 1: " . ($file1) . " = " . (file_exists($file1) ? 'EXISTS' : 'NOT FOUND') . "\n";
            echo "  File Check 2: " . ($file2) . " = " . (file_exists($file2) ? 'EXISTS' : 'NOT FOUND') . "\n";
        }

        echo "\n";
    }

} catch(\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
