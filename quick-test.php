<?php
// Quick test untuk lihat apakah file bisa diakses via URL

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Storage;

$files = Storage::disk('public')->files('organization-photos');
$storageRoot = storage_path('app/public');

echo "=== STORAGE DISK PUBLIC TEST ===\n\n";
echo "Root path: " . $storageRoot . "\n";
echo "Organization photos folder: " . $storageRoot . '\organization-photos' . "\n";
echo "Files count: " . count($files) . "\n\n";

echo "=== FILES ===\n";
foreach ($files as $file) {
    $fullPath = $storageRoot . '\\' . str_replace('/', '\\', $file);
    $exists = file_exists($fullPath);
    echo "File: $file\n";
    echo "  Full path: $fullPath\n";
    echo "  Exists: " . ($exists ? 'YES' : 'NO') . "\n";
    echo "  URL: /storage/$file\n";
    echo "  Size: " . (file_exists($fullPath) ? filesize($fullPath) : 'N/A') . " bytes\n";
    echo "\n";
}
