<?php

// Simple check script
echo "=== CHECKING PHOTOS ===\n\n";

$storageDir = 'storage/app/public/organization-photos/';
echo "Files in storage:\n";
$files = glob($storageDir . '*');
foreach($files as $file) {
    $filename = basename($file);
    echo "  - " . $filename . " (" . filesize($file) . " bytes)\n";
}

// Read database via SQLite
echo "\n=== DATABASE RECORDS ===\n";
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$result = $db->query("SELECT id, position_name, photo FROM organizational_structures");
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . $row['id'] . " | Position: " . $row['position_name'] . " | Photo: " . ($row['photo'] ?? 'NULL') . "\n";
}

echo "\n";
