<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $tables = DB::select('SHOW TABLES');
    echo "Database tables:\n";
    foreach ($tables as $table) {
        $tableName = array_values((array)$table)[0];
        echo "- $tableName\n";
    }

    // Check sessions table
    $sessionsCount = DB::table('sessions')->count();
    echo "\nSessions table has $sessionsCount records\n";

    // Check users table
    $usersCount = DB::table('users')->count();
    echo "Users table has $usersCount records\n";

} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
