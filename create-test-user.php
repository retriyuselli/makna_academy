<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = \Illuminate\Http\Request::capture();

$response = $kernel->handle($request);

// Create test user for avatar upload testing
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$testUser = new User([
    'name' => 'Test Avatar User',
    'username' => 'testuser',
    'email' => 'testuser@makna.id',
    'role' => 'customer',
    'email_verified_at' => now(),
]);

$testUser->password = Hash::make('password123');
$testUser->save();

echo "Test user created successfully!\n";
echo "Username: testuser\n";
echo "Password: password123\n";
echo "User ID: {$testUser->id}\n";
