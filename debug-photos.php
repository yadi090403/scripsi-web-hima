<?php
// Debug script untuk cek foto
// Akses dari browser: http://localhost:8000/debug-photos.php

require 'bootstrap/app.php';

use Illuminate\Support\Facades\Storage;
use App\Models\OrganizationalStructure;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Organizational Photos</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .position-item { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; background: #fafafa; }
        .photo-preview { width: 100px; height: 100px; margin: 10px 0; border-radius: 5px; background: #eee; display: flex; align-items: center; justify-content: center; color: #999; font-size: 12px; }
        .photo-url { background: #f0f0f0; padding: 8px; border-radius: 3px; word-break: break-all; font-family: monospace; font-size: 12px; }
        .status { padding: 5px 10px; border-radius: 3px; display: inline-block; font-size: 12px; font-weight: bold; }
        .status-ok { background: #d4edda; color: #155724; }
        .status-error { background: #f8d7da; color: #721c24; }
        .info-section { margin: 20px 0; padding: 15px; background: #e7f3ff; border-left: 4px solid #2196F3; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔍 Debug: Organizational Structure Photos</h1>

    <div class="info-section">
        <h3>ℹ️ Information</h3>
        <p><strong>App URL:</strong> <?php echo config('app.url'); ?></p>
        <p><strong>Storage Disk (public) Root:</strong> <?php echo storage_path('app/public'); ?></p>
        <p><strong>Public Storage URL Base:</strong> /storage</p>
        <p><strong>Organization Photos Folder:</strong> storage/app/public/organization-photos/</p>
    </div>

    <h2>📋 Database Records with Photos</h2>

    <?php
    // Get all positions with photos
    $positions = OrganizationalStructure::whereNotNull('photo')->get();

    if ($positions->isEmpty()) {
        echo "<p style='color: #999;'>Tidak ada posisi dengan foto di database.</p>";
    } else {
        foreach ($positions as $position) {
            $photoDb = $position->photo;
            $photoUrl = $position->photo_url;

            // Check if photo file exists
            $fileExists = false;
            if (strpos($photoDb, '/') !== false) {
                $fileExists = Storage::disk('public')->exists($photoDb);
            } else {
                $fileExists = Storage::disk('public')->exists('organization-photos/' . $photoDb);
            }

            ?>
            <div class="position-item">
                <h3><?php echo htmlspecialchars($position->position_name); ?> - <?php echo htmlspecialchars($position->display_name); ?></h3>

                <p><strong>Database Photo Field:</strong></p>
                <div class="photo-url"><?php echo htmlspecialchars($photoDb); ?></div>

                <p><strong>File Exists:</strong>
                    <span class="status <?php echo $fileExists ? 'status-ok' : 'status-error'; ?>">
                        <?php echo $fileExists ? '✓ YES' : '✗ NO'; ?>
                    </span>
                </p>

                <p><strong>Generated Photo URL:</strong></p>
                <div class="photo-url"><?php echo htmlspecialchars($photoUrl); ?></div>

                <p><strong>Preview:</strong></p>
                <div class="photo-preview">
                    <img src="<?php echo htmlspecialchars($photoUrl); ?>" alt="<?php echo htmlspecialchars($position->display_name); ?>" style="max-width: 100%; max-height: 100%; border-radius: 5px;">
                </div>

                <p>
                    <strong>Position ID:</strong> <?php echo $position->id; ?> |
                    <strong>Level:</strong> <?php echo htmlspecialchars($position->position_level); ?> |
                    <strong>User:</strong> <?php echo $position->user_id ? $position->user->name : 'N/A'; ?>
                </p>
            </div>
            <?php
        }
    }
    ?>

    <h2>📁 Physical Files in Storage</h2>
    <div class="info-section">
        <?php
        if (Storage::disk('public')->exists('organization-photos')) {
            $files = Storage::disk('public')->files('organization-photos');
            echo "<p><strong>Found " . count($files) . " files:</strong></p>";
            echo "<ul>";
            foreach ($files as $file) {
                echo "<li><code>" . htmlspecialchars($file) . "</code></li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color: red;'>❌ Folder organization-photos does not exist!</p>";
        }
        ?>
    </div>

    <h2>📊 Summary</h2>
    <div class="info-section">
        <?php
        $totalWithPhoto = OrganizationalStructure::whereNotNull('photo')->count();
        $totalPositions = OrganizationalStructure::count();
        $filesOnDisk = count(Storage::disk('public')->files('organization-photos'));

        echo "<p><strong>Total Positions:</strong> $totalPositions</p>";
        echo "<p><strong>Positions with Photo:</strong> $totalWithPhoto</p>";
        echo "<p><strong>Physical Files on Disk:</strong> $filesOnDisk</p>";

        if ($totalWithPhoto > $filesOnDisk) {
            echo "<p style='color: #721c24;'><strong>⚠️ Warning:</strong> Lebih banyak foto di database daripada file fisik!</p>";
        }
        ?>
    </div>

</div>
</body>
</html>
