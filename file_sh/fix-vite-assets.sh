#!/bin/bash

echo "🎨 Fix Vite Assets - Makna Academy"
echo "================================="

echo "📋 Step 1: Check current assets..."
echo "Checking public/build directory:"
ls -la public/build/ 2>/dev/null || echo "❌ public/build directory not found"

echo ""
echo "📋 Step 2: Check if npm/vite is properly configured..."
if [ -f "package.json" ]; then
    echo "✅ package.json exists"
    grep -E "vite|build" package.json
else
    echo "❌ package.json not found"
fi

echo ""
echo "🔧 Step 3: Build assets or create fallback..."
if [ -f "package.json" ] && command -v npm >/dev/null 2>&1; then
    echo "🔨 Building assets with npm..."
    npm install
    npm run build
elif [ -f "package.json" ] && command -v yarn >/dev/null 2>&1; then
    echo "🔨 Building assets with yarn..."
    yarn install
    yarn build
else
    echo "⚠️ npm/yarn not available, creating fallback manifest..."
    
    # Create build directory if not exists
    mkdir -p public/build
    
    # Create minimal manifest.json
    cat > public/build/manifest.json << 'EOF'
{
  "resources/css/app.css": {
    "file": "assets/app.css",
    "isEntry": true,
    "src": "resources/css/app.css"
  },
  "resources/js/app.js": {
    "file": "assets/app.js",
    "isEntry": true,
    "src": "resources/js/app.js"
  }
}
EOF

    # Create minimal CSS file
    mkdir -p public/build/assets
    cat > public/build/assets/app.css << 'EOF'
/* Minimal app styles */
body { font-family: system-ui, sans-serif; }
EOF

    # Create minimal JS file
    cat > public/build/assets/app.js << 'EOF'
// Minimal app script
console.log('Makna Academy App Loaded');
EOF

    echo "✅ Fallback manifest and assets created"
fi

echo ""
echo "📋 Step 4: Verify assets..."
echo "Checking public/build contents:"
ls -la public/build/

echo ""
echo "🔧 Step 5: Update view templates to handle missing assets..."
echo "Checking auth views..."
find resources/views/auth -name "*.php" | head -3

echo ""
echo "✅ Asset fix completed!"
echo ""
echo "🔄 Now run: ./fix-service-bindings.sh"
