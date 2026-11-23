<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'admin@himanj.ac.id')->first();

if ($user) {
    echo "User found: {$user->name} ({$user->email})\n";
    echo "Password hash: {$user->password}\n";
    echo "Password check: " . (Hash::check('password123', $user->password) ? 'OK' : 'FAIL') . "\n";
    echo "User active: " . ($user->is_active ? 'YES' : 'NO') . "\n";

    // Test Auth::attempt
    $credentials = ['email' => $user->email, 'password' => 'password123'];
    $attempt = Auth::attempt($credentials);
    echo "Auth::attempt result: " . ($attempt ? 'SUCCESS' : 'FAILED') . "\n";

    if ($attempt) {
        echo "Logged in user: " . Auth::user()->name . "\n";
        Auth::logout();
    } else {
        // Check what happens in attempt
        $userFromDb = User::where('email', $user->email)->first();
        if ($userFromDb) {
            echo "User found in DB: {$userFromDb->name}\n";
            echo "Password check: " . (Hash::check('password123', $userFromDb->password) ? 'OK' : 'FAIL') . "\n";
            echo "User active: " . ($userFromDb->is_active ? 'YES' : 'NO') . "\n";
        }

        // Try manual login
        echo "Trying manual login...\n";
        Auth::login($userFromDb);
        echo "Manual login: " . (Auth::check() ? 'SUCCESS' : 'FAILED') . "\n";
        if (Auth::check()) {
            echo "Logged in user: " . Auth::user()->name . "\n";
            Auth::logout();
        }
    }
} else {
    echo "User not found\n";
}
