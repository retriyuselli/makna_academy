#!/bin/bash

echo "🔥 Emergency Fix for 500 Error - Makna Academy"
echo "============================================="

echo "📋 Step 1: Check current directory and files..."
pwd
ls -la | head -10

echo ""
echo "🔧 Step 2: Fix composer autoload..."
composer dump-autoload --optimize

echo ""
echo "🗂️ Step 3: Clear ALL Laravel caches (aggressive)..."
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

echo ""
echo "📝 Step 4: Regenerate Laravel key..."
php artisan key:generate --force

echo ""
echo "⚙️ Step 5: Clear and rebuild config..."
php artisan config:clear
php artisan config:cache

echo ""
echo "🛣️ Step 6: Clear and rebuild routes..."
php artisan route:clear
php artisan route:cache

echo ""
echo "👀 Step 7: Clear views..."
php artisan view:clear

echo ""
echo "🔍 Step 8: Check .env file..."
if [ -f ".env" ]; then
    echo "✅ .env file exists"
    grep -E "^(APP_NAME|APP_ENV|APP_DEBUG|APP_URL|DB_CONNECTION)" .env | head -5
else
    echo "❌ .env file missing!"
    if [ -f ".env.example" ]; then
        echo "📋 Creating .env from .env.example..."
        cp .env.example .env
        php artisan key:generate --force
    fi
fi

echo ""
echo "🔒 Step 9: Set proper permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env

echo ""
echo "🧪 Step 10: Test basic Laravel..."
echo "Testing artisan commands:"
php artisan --version
echo ""
php artisan list | grep route

echo ""
echo "✅ Emergency fix completed!"
echo ""
echo "🔄 Now try these URLs again:"
echo "- https://maknaacademy.com/login"
echo "- https://maknaacademy.com/register"
echo ""
echo "🐛 If still error 500, check:"
echo "- Server error logs: tail -f storage/logs/laravel.log"
echo "- Apache/Nginx error logs"
echo "- PHP error logs"
