#!/bin/bash

# Fix Avatar Display Issues on Server
# File: fix-avatar-server.sh

echo "🔧 Fixing Avatar Display Issues"
echo "==============================="

# Set working directory
cd /home/u380354370/domains/maknaacademy.com/public_html

echo "1. Creating storage link..."
php artisan storage:link

echo "2. Setting proper permissions..."
chmod -R 755 storage/
chmod -R 755 public/storage/

echo "3. Ensuring avatars directory exists..."
mkdir -p storage/app/public/avatars
chmod 755 storage/app/public/avatars

echo "4. Checking current avatar files..."
if [ -d "storage/app/public/avatars" ]; then
    echo "Avatar files found: $(ls -1 storage/app/public/avatars | wc -l)"
    ls -la storage/app/public/avatars/ | head -5
else
    echo "Creating avatars directory..."
    mkdir -p storage/app/public/avatars
fi

echo "5. Clearing caches..."
php artisan config:clear
php artisan view:clear
php artisan route:clear

echo "6. Optimizing for production..."
php artisan config:cache
php artisan view:cache
php artisan route:cache

echo "7. Testing avatar helper function..."
php artisan tinker --execute="
try {
    \$user = App\Models\User::whereNotNull('avatar_url')->first();
    if (\$user) {
        echo 'Test user: ' . \$user->name . PHP_EOL;
        echo 'Avatar URL: ' . \$user->avatar_url . PHP_EOL;
        echo 'Generated URL: ' . user_avatar(\$user) . PHP_EOL;
    } else {
        echo 'No users with avatars found' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
"

echo ""
echo "✅ Avatar fix completed!"
echo ""
echo "🔍 If issues persist, check:"
echo "1. APP_URL in .env matches your domain"
echo "2. Server has proper read permissions on storage/"
echo "3. Symbolic link exists: public/storage -> storage/app/public"
echo "4. Avatar files exist in storage/app/public/avatars/"