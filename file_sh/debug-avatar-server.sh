#!/bin/bash

# Debug Avatar Display Issues on Production Server
# File: debug-avatar-server.sh

echo "🔍 Avatar Display Debug Script"
echo "============================="

# Check current directory
echo "📍 Current directory:"
pwd

# Check storage link
echo ""
echo "🔗 Checking storage link:"
ls -la public/ | grep storage

# Check storage/app/public structure
echo ""
echo "📁 Storage structure:"
ls -la storage/app/public/

# Check avatars directory
echo ""
echo "🖼️  Avatars directory:"
if [ -d "storage/app/public/avatars" ]; then
    ls -la storage/app/public/avatars/ | head -10
    echo "Avatar files count: $(ls -1 storage/app/public/avatars/ | wc -l)"
else
    echo "❌ Avatars directory not found!"
fi

# Check permissions
echo ""
echo "🔐 Storage permissions:"
ls -ld storage/
ls -ld storage/app/
ls -ld storage/app/public/
if [ -d "storage/app/public/avatars" ]; then
    ls -ld storage/app/public/avatars/
fi

# Check .env APP_URL
echo ""
echo "🌐 APP_URL configuration:"
grep "APP_URL" .env

# Test helper function
echo ""
echo "🧪 Testing helper function:"
php artisan tinker --execute="
try {
    \$user = App\Models\User::whereNotNull('avatar_url')->first();
    if (\$user) {
        echo 'Testing with user: ' . \$user->name . PHP_EOL;
        echo 'Avatar URL field: ' . \$user->avatar_url . PHP_EOL;
        echo 'Helper result: ' . user_avatar(\$user) . PHP_EOL;
        
        // Check if file exists
        if (str_starts_with(\$user->avatar_url, 'avatars/')) {
            \$path = storage_path('app/public/' . \$user->avatar_url);
        } else {
            \$path = storage_path('app/public/avatars/' . \$user->avatar_url);
        }
        echo 'File path: ' . \$path . PHP_EOL;
        echo 'File exists: ' . (file_exists(\$path) ? 'YES' : 'NO') . PHP_EOL;
    } else {
        echo 'No users with avatar found' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "✅ Debug completed!"
echo ""
echo "💡 Common fixes:"
echo "1. Create storage link: php artisan storage:link"
echo "2. Fix permissions: chmod -R 755 storage/"
echo "3. Check APP_URL in .env matches server domain"
echo "4. Ensure avatars directory exists with proper permissions"