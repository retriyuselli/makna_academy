<?php

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\ProfileController;
use App\Models\User;

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = \Illuminate\Http\Request::capture();

$response = $kernel->handle($request);

// Simulate avatar upload
echo "Testing avatar upload simulation...\n";

// Get test user
$testUser = User::where('email', 'testuser@makna.id')->first();

if (!$testUser) {
    echo "Test user not found!\n";
    exit;
}

echo "Found test user: {$testUser->name} (ID: {$testUser->id})\n";

// Create a temporary test image file
$testImagePath = storage_path('app/test-avatar.jpg');

// Create a simple test image (1x1 pixel JPEG)
$imageData = base64_decode('/9j/4AAQSkZJRgABAQAAAQABAAD//gA7Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2ODApLCBxdWFsaXR5ID0gOTAK/9sAQwADAgIDAgIDAwMDBAMDBAUIBQUEBAUKBwcGCAwKDAwLCgsLDQ4SEA0OEQ4LCxAWEBETFBUVFQwPFxgWFBgSFBUU/9sAQwEDBAQFBAUJBQUJFA0LDRQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQU/8AAEQgAAQABAwEiAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMEAgECBwcCBAMAAAABAgMEEQUhMQYSQVFhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD9/KKKKAP/2Q==');
file_put_contents($testImagePath, $imageData);

echo "Created test image at: {$testImagePath}\n";
echo "File size: " . filesize($testImagePath) . " bytes\n";

// Create UploadedFile object
$uploadedFile = new UploadedFile(
    $testImagePath,
    'test-avatar.jpg',
    'image/jpeg',
    null,
    true
);

echo "Created UploadedFile object\n";
echo "Original name: " . $uploadedFile->getClientOriginalName() . "\n";
echo "Size: " . $uploadedFile->getSize() . " bytes\n";
echo "MIME type: " . $uploadedFile->getMimeType() . "\n";
echo "Is valid: " . ($uploadedFile->isValid() ? 'YES' : 'NO') . "\n";

// Test upload process manually
echo "\n--- Testing upload process ---\n";

try {
    // Store the file
    $path = $uploadedFile->store('avatars', 'public');
    echo "File stored at: {$path}\n";
    
    // Check if file exists
    $fullPath = storage_path('app/public/' . $path);
    echo "Full path: {$fullPath}\n";
    echo "File exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
    
    // Update user avatar_url
    $testUser->avatar_url = $path;
    $testUser->save();
    
    echo "User avatar_url updated to: {$testUser->avatar_url}\n";
    
    // Test avatar display
    echo "Avatar URL: " . user_avatar($testUser) . "\n";
    
} catch (Exception $e) {
    echo "Error during upload: " . $e->getMessage() . "\n";
}

// Cleanup
if (file_exists($testImagePath)) {
    unlink($testImagePath);
    echo "\nCleaned up test file\n";
}

echo "\nAvatar upload test completed!\n";
