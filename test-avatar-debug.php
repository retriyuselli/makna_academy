<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = \Illuminate\Http\Request::capture();

$response = $kernel->handle($request);

// Test the AvatarHelper function
use App\Helpers\AvatarHelper;

echo "Testing AvatarHelper functions:\n";

// Test with a real user
$users = \App\Models\User::take(3)->get();

foreach ($users as $user) {
    echo "User ID: {$user->id}\n";
    echo "Username: {$user->username}\n";
    echo "Avatar URL in DB: {$user->avatar_url}\n";
    echo "user_avatar() result: " . user_avatar($user) . "\n";
    echo "Avatar exists: " . (file_exists(storage_path('app/public/' . ltrim($user->avatar_url, '/'))) ? 'YES' : 'NO') . "\n";
    echo "---\n";
}

// Test file upload debug
echo "\nTesting file upload conditions:\n";
echo "Max upload size: " . ini_get('upload_max_filesize') . "\n";
echo "Max post size: " . ini_get('post_max_size') . "\n";
echo "File uploads enabled: " . (ini_get('file_uploads') ? 'YES' : 'NO') . "\n";

echo "\nStorage permissions:\n";
$avatarPath = storage_path('app/public/avatars');
echo "Avatars directory: {$avatarPath}\n";
echo "Directory exists: " . (is_dir($avatarPath) ? 'YES' : 'NO') . "\n";
echo "Directory writable: " . (is_writable($avatarPath) ? 'YES' : 'NO') . "\n";
echo "Directory permissions: " . substr(sprintf('%o', fileperms($avatarPath)), -4) . "\n";
