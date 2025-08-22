<?php
// Test Auth Debug - Simpan sebagai test-auth.php di root
// Akses: https://maknaacademy.com/test-auth.php

echo "<h1>Test Auth Debug - Makna Academy</h1>";

// Test autoload Laravel
try {
    require 'vendor/autoload.php';
    $app = require 'bootstrap/app.php';
    echo "✅ Laravel bootstrap successful<br>";
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage() . "<br>";
    exit;
}

echo "<h2>1. Route Testing</h2>";

// Test route registration
$routes_to_test = [
    'login' => '/login',
    'register' => '/register', 
    'dashboard' => '/dashboard'
];

echo "<p>Test these auth routes:</p>";
foreach ($routes_to_test as $name => $path) {
    echo "<a href='{$path}' target='_blank'>{$name} ({$path})</a><br>";
}

echo "<h2>2. Controller Files Check</h2>";

$controllers = [
    'AuthenticatedSessionController' => 'app/Http/Controllers/Auth/AuthenticatedSessionController.php',
    'RegisteredUserController' => 'app/Http/Controllers/Auth/RegisteredUserController.php',
    'DashboardController' => 'app/Http/Controllers/DashboardController.php'
];

foreach ($controllers as $name => $path) {
    if (file_exists($path)) {
        echo "✅ {$name} exists<br>";
    } else {
        echo "❌ {$name} MISSING at {$path}<br>";
    }
}

echo "<h2>3. View Files Check</h2>";

$views = [
    'Login View' => 'resources/views/auth/login.blade.php',
    'Register View' => 'resources/views/auth/register.blade.php',
    'Dashboard View' => 'resources/views/dashboard.blade.php'
];

foreach ($views as $name => $path) {
    if (file_exists($path)) {
        echo "✅ {$name} exists<br>";
    } else {
        echo "❌ {$name} MISSING at {$path}<br>";
    }
}

echo "<h2>4. Database Connection Test</h2>";

try {
    // Test database connection
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $request = Illuminate\Http\Request::capture();
    
    // Boot the application
    $app->boot();
    
    // Test database
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Database connection successful<br>";
    
    // Test User model
    $userCount = \App\Models\User::count();
    echo "✅ User model works - Total users: {$userCount}<br>";
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

echo "<h2>5. Auth Configuration</h2>";

try {
    // Check auth config
    $authGuards = config('auth.guards');
    echo "Auth Guards: " . implode(', ', array_keys($authGuards)) . "<br>";
    
    $authProviders = config('auth.providers');
    echo "Auth Providers: " . implode(', ', array_keys($authProviders)) . "<br>";
    
} catch (Exception $e) {
    echo "❌ Auth config error: " . $e->getMessage() . "<br>";
}

echo "<h2>6. Manual Route Test</h2>";

try {
    // Test manual routing
    $router = app('router');
    $routes = $router->getRoutes();
    
    $authRoutes = [];
    foreach ($routes as $route) {
        $uri = $route->uri();
        if (in_array($uri, ['login', 'register', 'dashboard'])) {
            $authRoutes[] = $uri . ' -> ' . $route->getActionName();
        }
    }
    
    if (!empty($authRoutes)) {
        echo "Found auth routes:<br>";
        foreach ($authRoutes as $route) {
            echo "- {$route}<br>";
        }
    } else {
        echo "❌ No auth routes found<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Route test error: " . $e->getMessage() . "<br>";
}

echo "<h2>7. Error Log Check</h2>";

$logPath = 'storage/logs/laravel.log';
if (file_exists($logPath)) {
    $logContent = file_get_contents($logPath);
    $recentErrors = array_slice(explode("\n", $logContent), -20);
    
    echo "<h3>Recent Log Entries:</h3>";
    echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 300px; overflow-y: scroll;'>";
    foreach ($recentErrors as $line) {
        if (trim($line)) {
            echo htmlspecialchars($line) . "\n";
        }
    }
    echo "</pre>";
} else {
    echo "❌ Laravel log file not found<br>";
}

echo "<hr>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Test the auth route links above</li>";
echo "<li>Check any errors shown in the log section</li>";
echo "<li>If routes show 404, check .htaccess configuration</li>";
echo "<li>If routes work but show errors, check the specific error messages</li>";
echo "<li>Delete this file after debugging</li>";
echo "</ol>";
?>
