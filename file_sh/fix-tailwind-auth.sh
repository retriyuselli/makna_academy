#!/bin/bash

echo "🎨 Fix Tailwind CSS for Auth Pages - Makna Academy"
echo "================================================"

echo "📋 Step 1: Backup current guest layout..."
cp resources/views/layouts/guest.blade.php resources/views/layouts/guest.blade.php.backup

echo ""
echo "🔧 Step 2: Update guest layout to use Tailwind CDN..."

# Create improved guest layout with Tailwind CDN
cat > resources/views/layouts/guest.blade.php << 'EOF'
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Makna Academy') }} - Authentication</title>

        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <!-- Google Fonts - Poppins -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Poppins', sans-serif; }
            
            /* Custom styling for auth forms */
            .auth-container {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
            }
            
            .auth-card {
                backdrop-filter: blur(10px);
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }
            
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                transition: all 0.3s ease;
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="auth-container flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="mb-6">
                <a href="/" class="flex items-center text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-graduation-cap text-3xl mr-3"></i>
                    <span class="text-2xl font-bold">{{ config('app.name', 'Makna Academy') }}</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 auth-card shadow-2xl overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
            
            <div class="mt-6 text-center">
                <p class="text-white text-sm">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Secure Authentication System
                </p>
            </div>
        </div>
    </body>
</html>
EOF

echo "✅ Guest layout updated with Tailwind CDN"

echo ""
echo "🔧 Step 3: Update auth views for better styling..."

# Update login view
if [ -f "resources/views/auth/login.blade.php" ]; then
    echo "Checking login view..."
    head -5 resources/views/auth/login.blade.php
fi

# Update register view  
if [ -f "resources/views/auth/register.blade.php" ]; then
    echo "Checking register view..."
    head -5 resources/views/auth/register.blade.php
fi

echo ""
echo "🧪 Step 4: Test CSS loading..."
echo "Checking if Tailwind CDN is accessible:"
curl -s -I https://cdn.tailwindcss.com | head -3

echo ""
echo "✅ Tailwind CSS fix completed!"
echo ""
echo "🔄 Test these URLs now (should have proper styling):"
echo "- https://maknaacademy.com/login"
echo "- https://maknaacademy.com/register"
echo ""
echo "💡 Changes made:"
echo "- ✅ Switched from @vite to Tailwind CDN"
echo "- ✅ Added custom auth styling"
echo "- ✅ Added Font Awesome icons"
echo "- ✅ Added gradient background"
echo "- ✅ Added Poppins font family"
