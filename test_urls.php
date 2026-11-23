<?php

require_once __DIR__ . '/vendor/autoload.php';

try {
    $app = require __DIR__ . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Http\Kernel::class)->bootstrap();

    echo "=== PHOTO URLS TEST ===\n\n";

    $positions = \App\Models\OrganizationalStructure::where('photo', '!=', null)->get();

    foreach($positions as $pos) {
        echo "Position: " . $pos->position_name . "\n";
        echo "  Photo (DB): " . $pos->photo . "\n";
        echo "  Photo URL: " . $pos->photo_url . "\n";

        // Check if URL starts with http or asset
        if(strpos($pos->photo_url, 'http') === 0) {
            echo "  Type: REAL URL ✓\n";
        } else if(strpos($pos->photo_url, '/storage/') !== false) {
            echo "  Type: ASSET URL ✓\n";
        } else {
            echo "  Type: FALLBACK URL ✗\n";
        }

        echo "\n";
    }

} catch(\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
