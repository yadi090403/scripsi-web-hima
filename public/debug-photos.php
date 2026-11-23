<?php
// Debug script - bisa diakses dari browser
// Akses via http://localhost/debug-photos.php

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Bootstrap the framework (register service providers, load config, etc.)
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Now app services like config, db, storage are available
$db = $app->make('db');

echo "<h2>Debug: Organizational Structure Photos</h2>";

echo "<h3>Database Records</h3>";
echo "<table border='1'><tr><th>ID</th><th>Position</th><th>Photo Column</th><th>Display Name</th></tr>";

$positions = \App\Models\OrganizationalStructure::all();
foreach($positions as $pos) {
    echo "<tr>";
    echo "<td>" . $pos->id . "</td>";
    echo "<td>" . $pos->position_name . "</td>";
    echo "<td>" . ($pos->photo ?? 'NULL') . "</td>";
    echo "<td>" . $pos->display_name . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>Storage Files</h3>";
$files = glob(__DIR__ . '/../storage/app/public/organization-photos/*');
echo "<table border='1'>";
foreach($files as $file) {
    $size = filesize($file);
    echo "<tr><td>" . basename($file) . "</td><td>" . $size . " bytes</td></tr>";
}
echo "</table>";

echo "<h3>Photo URLs</h3>";
echo "<table border='1'><tr><th>Position</th><th>URL</th><th>File Exists</th></tr>";
foreach($positions as $pos) {
    echo "<tr>";
    echo "<td>" . $pos->position_name . "</td>";
    echo "<td>" . $pos->photo_url . "</td>";

    // Check if file exists
    if ($pos->photo) {
        // Build candidate path depending on how it's stored in DB
        if (strpos($pos->photo, '/') !== false) {
            // stored with folder prefix already
            $photoPathCandidate = __DIR__ . '/../storage/app/public/' . ltrim($pos->photo, '/');
        } else {
            // stored as basename only
            $photoPathCandidate = __DIR__ . '/../storage/app/public/organization-photos/' . $pos->photo;
        }
        $exists = file_exists($photoPathCandidate) ? '<span style="color:green">YES</span>' : '<span style="color:red">NO</span>';
        echo "<td>" . $exists . "</td>";
    } else {
        echo "<td>N/A (no photo)</td>";
    }
    echo "</tr>";
}
echo "</table>";
