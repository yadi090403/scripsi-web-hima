<?php
// Normalize script: prefix 'organization-photos/' when missing
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Count rows to be updated
$toUpdate = DB::table('organizational_structures')
    ->whereNotNull('photo')
    ->where('photo', 'not like', 'organization-photos/%')
    ->count();

if ($toUpdate === 0) {
    echo "No rows require normalization.\n";
    exit(0);
}

// Perform update
$affected = DB::table('organizational_structures')
    ->whereNotNull('photo')
    ->where('photo', 'not like', 'organization-photos/%')
    ->update(['photo' => DB::raw("CONCAT('organization-photos/', photo)")]);

echo "Rows updated: $affected\n";
