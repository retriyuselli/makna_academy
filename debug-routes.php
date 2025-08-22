<?php
// Debug script untuk troubleshoot routing issues
// Simpan sebagai debug-routes.php di root directory
// Akses via: https://maknaacademy.com/debug-routes.php

echo "<h1>Makna Academy - Debug Information</h1>";

echo "<h2>1. Basic PHP Info</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . __DIR__ . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";

echo "<h2>2. File Structure Check</h2>";
$files_to_check = [
    '.htaccess',
    'public/.htaccess', 
    'public/index.php',
    'bootstrap/app.php',
    'routes/web.php',
    'routes/auth.php',
    'vendor/autoload.php'
];

foreach ($files_to_check as $file) {
    if (file_exists($file)) {
        echo "✅ {$file} exists<br>";
    } else {
        echo "❌ {$file} MISSING<br>";
    }
}

echo "<h2>3. Directory Permissions</h2>";
$dirs_to_check = ['storage', 'bootstrap/cache', 'public'];
foreach ($dirs_to_check as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        echo "{$dir}: {$perms}<br>";
    }
}

echo "<h2>4. Laravel Bootstrap Test</h2>";
try {
    if (file_exists('vendor/autoload.php')) {
        require 'vendor/autoload.php';
        echo "✅ Composer autoload successful<br>";
        
        if (file_exists('bootstrap/app.php')) {
            $app = require 'bootstrap/app.php';
            echo "✅ Laravel app bootstrap successful<br>";
            echo "App Environment: " . $app->environment() . "<br>";
        } else {
            echo "❌ bootstrap/app.php not found<br>";
        }
    } else {
        echo "❌ vendor/autoload.php not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Bootstrap Error: " . $e->getMessage() . "<br>";
}

echo "<h2>5. URL Rewrite Test</h2>";
echo "<p>Test these links:</p>";
$test_urls = [
    '/' => 'Home',
    '/events' => 'Events',
    '/about' => 'About', 
    '/login' => 'Login',
    '/register' => 'Register'
];

foreach ($test_urls as $url => $name) {
    echo "<a href='{$url}' target='_blank'>{$name} ({$url})</a><br>";
}

echo "<h2>6. .htaccess Content</h2>";
if (file_exists('.htaccess')) {
    echo "<h3>Root .htaccess:</h3>";
    echo "<pre>" . htmlspecialchars(file_get_contents('.htaccess')) . "</pre>";
}

if (file_exists('public/.htaccess')) {
    echo "<h3>Public .htaccess:</h3>";
    echo "<pre>" . htmlspecialchars(file_get_contents('public/.htaccess')) . "</pre>";
}

echo "<h2>7. Environment Variables</h2>";
if (file_exists('.env')) {
    echo "✅ .env file exists<br>";
    
    // Only show safe environment variables
    $safe_vars = ['APP_NAME', 'APP_ENV', 'APP_DEBUG', 'APP_URL'];
    
    $env_content = file_get_contents('.env');
    foreach ($safe_vars as $var) {
        if (preg_match("/^{$var}=(.*)$/m", $env_content, $matches)) {
            echo "{$var}={$matches[1]}<br>";
        }
    }
} else {
    echo "❌ .env file missing<br>";
}

echo "<hr>";
echo "<p><strong>Instructions:</strong></p>";
echo "<ol>";
echo "<li>Run this script first to diagnose issues</li>";
echo "<li>If Laravel bootstrap fails, check file permissions and dependencies</li>";
echo "<li>If URL rewrite test shows 404s, check .htaccess configuration</li>";
echo "<li>Delete this file after debugging for security</li>";
echo "</ol>";
?>
