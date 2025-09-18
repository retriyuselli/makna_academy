<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test URL generation dalam web context
echo "Testing avatar URL in web context:\n";

// Set proper web context
app()->instance('request', Illuminate\Http\Request::create('http://127.0.0.1:8001'));

use App\Models\User;

$testUser = User::find(6); // Test user we created
if ($testUser) {
    echo "Test user found: {$testUser->name}\n";
    echo "Avatar URL in DB: {$testUser->avatar_url}\n";
    
    // Test user_avatar function
    $avatarUrl = user_avatar($testUser);
    echo "user_avatar() result: {$avatarUrl}\n";
    
    // Test asset() function directly
    if ($testUser->avatar_url) {
        $assetUrl = asset('storage/' . $testUser->avatar_url);
        echo "asset() result: {$assetUrl}\n";
    }
    
    // Test config values
    echo "APP_URL: " . config('app.url') . "\n";
    echo "Asset URL base: " . config('app.asset_url', config('app.url')) . "\n";
} else {
    echo "Test user not found\n";
}

// Test with real avatar upload simulation in web context
echo "\n--- Testing web context upload ---\n";

use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;

// Create a mock request for profile update
$request = Request::create('/profile', 'PATCH', [
    'name' => 'Updated Test User',
    'email' => 'testuser@makna.id'
]);

// Add authentication
$request->setUserResolver(function () use ($testUser) {
    return $testUser;
});

echo "Created mock profile update request\n";
echo "Authenticated user: " . ($request->user() ? $request->user()->name : 'None') . "\n";
