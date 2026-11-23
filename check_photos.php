<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$positions = \App\Models\OrganizationalStructure::all();

echo "=== ORGANIZATIONAL STRUCTURE DATABASE ===\n";
echo "ID | Position Name | Photo | Photo URL\n";
echo "------|---------------|-------|----------\n";

foreach($positions as $p) {
    echo $p->id . " | " . $p->position_name . " | " . ($p->photo ?? 'NULL') . " | " . $p->photo_url . "\n";
}

echo "\n=== STORAGE FILES ===\n";
$files = glob('storage/app/public/organization-photos/*');
foreach($files as $file) {
    echo basename($file) . "\n";
}
