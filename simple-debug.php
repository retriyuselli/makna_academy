<?php
// Simple debug untuk 500 error
echo "<h1>Simple Debug - Error 500 Fix</h1>";

echo "<h2>1. Basic PHP Check</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Directory: " . __DIR__ . "<br>";

echo "<h2>2. File Existence Check</h2>";
$critical_files = [
    '.env',
    'vendor/autoload.php',
    'bootstrap/app.php',
    'config/app.php'
];

foreach ($critical_files as $file) {
    if (file_exists($file)) {
        echo "✅ {$file}<br>";
    } else {
        echo "❌ {$file} MISSING!<br>";
    }
}

echo "<h2>3. Try Loading Laravel</h2>";
try {
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        echo "✅ Autoload successful<br>";
        
        if (file_exists('bootstrap/app.php')) {
            echo "Attempting to load Laravel app...<br>";
            $app = require_once 'bootstrap/app.php';
            echo "✅ Laravel app loaded<br>";
            
            // Try to get environment
            try {
                $env = $app->environment();
                echo "Environment: {$env}<br>";
            } catch (Exception $e) {
                echo "❌ Environment error: " . $e->getMessage() . "<br>";
            }
            
        } else {
            echo "❌ bootstrap/app.php missing<br>";
        }
    } else {
        echo "❌ vendor/autoload.php missing<br>";
    }
} catch (Exception $e) {
    echo "❌ Laravel load error: " . $e->getMessage() . "<br>";
    echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>4. Environment Variables</h2>";
if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    $lines = explode("\n", $envContent);
    foreach ($lines as $line) {
        if (strpos($line, 'APP_') === 0 || strpos($line, 'DB_') === 0) {
            echo htmlspecialchars($line) . "<br>";
        }
    }
} else {
    echo "❌ .env file missing<br>";
}

echo "<h2>5. Error Log (Last 10 lines)</h2>";
$logFile = 'storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -10);
    echo "<pre>";
    foreach ($lastLines as $line) {
        echo htmlspecialchars($line);
    }
    echo "</pre>";
} else {
    echo "No Laravel log file found<br>";
}

echo "<hr>";
echo "<p>Run emergency-fix.sh script on server to fix common issues.</p>";
?>
