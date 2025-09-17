#!/bin/bash

echo "🔐 Auth Fix Script - Makna Academy"
echo "=================================="

echo "🧹 Clearing all auth-related caches..."
php artisan auth:clear-resets 2>/dev/null || echo "No password resets to clear"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "🔄 Regenerating auth caches..."
php artisan config:cache
php artisan route:cache

echo "🔗 Ensuring storage link exists..."
php artisan storage:link 2>/dev/null || echo "Storage link already exists"

echo "🔑 Generating application key if needed..."
php artisan key:generate --show

echo "📋 Testing auth routes..."
php artisan route:list | grep -E "(login|register|dashboard)"

echo "🔒 Setting proper permissions..."
chmod 644 app/Http/Controllers/Auth/*.php
chmod 644 resources/views/auth/*.blade.php
chmod 644 routes/auth.php

echo "✅ Auth fix completed!"
echo ""
echo "🧪 Test URLs:"
echo "- Login: https://maknaacademy.com/login"
echo "- Register: https://maknaacademy.com/register"
echo ""
echo "📊 Debug URL (delete after use):"
echo "- https://maknaacademy.com/test-auth.php"
