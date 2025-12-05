<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- Security Meta Tags -->
        <meta http-equiv="X-Content-Type-Options" content="nosniff">
        <meta http-equiv="X-Frame-Options" content="DENY">
        <meta http-equiv="X-XSS-Protection" content="1; mode=block">
        <meta name="referrer" content="strict-origin-when-cross-origin">

        <title>{{ config('app.name', 'Makna Academy') }} - Authentication</title>

        <!-- Google Fonts - Poppins (preload untuk performa optimal) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- Tailwind CSS CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Configure Tailwind to use Poppins sebagai default font -->
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            'sans': ['Poppins', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Arial', 'sans-serif'],
                            'poppins': ['Poppins', 'sans-serif']
                        }
                    }
                }
            }
        </script>
        
        <!-- Font Awesome - Multiple CDN Sources for Reliability -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        
        <style>
            /* FontAwesome Fallback CSS */
            .fas, .fab, .far, .fa {
                font-family: "Font Awesome 6 Free", "Font Awesome 5 Free", "FontAwesome", serif !important;
                font-weight: 900;
                display: inline-block;
            }
            
            /* Specific icon fallbacks if CDN fails */
            .fa-sign-in-alt:before { content: "→"; font-family: Arial, sans-serif; }
            .fa-envelope:before { content: "✉"; font-family: Arial, sans-serif; }
            .fa-lock:before { content: "🔒"; font-family: Arial, sans-serif; }
            .fa-user-plus:before { content: "+"; font-family: Arial, sans-serif; }
            .fa-graduation-cap:before { content: "🎓"; font-family: Arial, sans-serif; }
            .fa-shield-alt:before { content: "🛡"; font-family: Arial, sans-serif; }
            .fa-bars:before { content: "☰"; font-family: Arial, sans-serif; }
            .fa-times:before { content: "✕"; font-family: Arial, sans-serif; }

        <style>
            /* Force Poppins font untuk semua elemen */
            *, *::before, *::after {
                font-family: 'Poppins', sans-serif !important;
            }
            
            body {
                font-family: 'Poppins', sans-serif !important;
                font-weight: 400;
                letter-spacing: 0.01em;
            }
            
            h1, h2, h3, h4, h5, h6 {
                font-family: 'Poppins', sans-serif !important;
                font-weight: 600;
            }
            
            input, button, select, textarea {
                font-family: 'Poppins', sans-serif !important;
            }
            
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
            
            /* Password toggle button styling */
            .password-toggle {
                transition: all 0.2s ease;
            }
            
            .password-toggle:hover {
                color: #4f46e5 !important;
                transform: scale(1.1);
            }
            
            /* Input focus styling */
            input:focus {
                outline: none;
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            }
        </style>
        <link rel="icon" type="image/png" href="{{ asset('storage/images/icon.png') }}">
        <link rel="shortcut icon" href="{{ asset('storage/images/icon.png') }}">

        <!-- Alpine.js for interactive components -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
