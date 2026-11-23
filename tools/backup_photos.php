<?php
// Backup script: eksport id,photo ke CSV sebelum normalisasi
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('organizational_structures')->select('id','photo')->whereNotNull('photo')->get();
$path = __DIR__ . '/../storage/app/backup_photos_before_normalize.csv';
$fp = fopen($path, 'w');
if (!$fp) {
    echo "Failed to open $path for writing\n";
    exit(1);
}
// header
fputcsv($fp, ['id','photo']);
foreach ($rows as $r) {
    fputcsv($fp, [$r->id, $r->photo]);
}
fclose($fp);
echo "Backup written to: $path\n"; 
