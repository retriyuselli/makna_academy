#!/bin/bash

echo "🔍 FONTAWESOME ICON TROUBLESHOOTING"
echo "==================================="
echo ""

echo "📋 Step 1: Check FontAwesome CDN availability..."
curl -I "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" 2>/dev/null | head -1

echo ""
echo "📋 Step 2: Check guest layout for FontAwesome include..."
grep -n "font-awesome\|fontawesome" resources/views/layouts/guest.blade.php

echo ""
echo "📋 Step 3: Check if login page uses guest layout..."
head -1 resources/views/auth/login.blade.php

echo ""
echo "📋 Step 4: Test FontAwesome classes in use..."
grep -r "fas\|fab\|far" resources/views/auth/ | head -5

echo ""
echo "📋 Step 5: Create FontAwesome fallback solution..."

# Update guest.blade.php with multiple FontAwesome sources
cp resources/views/layouts/guest.blade.php resources/views/layouts/guest.blade.php.backup

# Add multiple FontAwesome CDN sources
sed -i '/<!-- Font Awesome -->/,/css\/all\.min\.css">/c\
        <!-- Font Awesome - Multiple Sources for Reliability -->\
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />\
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>\
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">' resources/views/layouts/guest.blade.php

echo "✅ Multiple FontAwesome CDNs added for fallback"

echo ""
echo "📋 Step 6: Add inline CSS fallback for icons..."
cat >> resources/views/layouts/guest.blade.php << 'EOF'

        <!-- FontAwesome Fallback CSS -->
        <style>
            /* Fallback for FontAwesome icons if CDN fails */
            .fas, .fab, .far, .fa {
                font-family: "Font Awesome 6 Free", "Font Awesome 5 Free", "FontAwesome", serif !important;
                font-weight: 900;
                display: inline-block;
            }
            
            /* Specific icon fallbacks */
            .fa-sign-in-alt:before { content: "→"; }
            .fa-envelope:before { content: "✉"; }
            .fa-lock:before { content: "🔒"; }
            .fa-user-plus:before { content: "+"; }
            .fa-graduation-cap:before { content: "🎓"; }
            
            /* Hide broken icons */
            .fas:empty, .fab:empty, .far:empty {
                display: none;
            }
        </style>
EOF

echo "✅ Fallback CSS for icons added"

echo ""
echo "📋 Step 7: Test icon display..."
php -r "
echo 'Creating test HTML for icon verification...' . PHP_EOL;
\$html = '<!DOCTYPE html>
<html>
<head>
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css\">
</head>
<body>
    <h1>FontAwesome Test</h1>
    <i class=\"fas fa-sign-in-alt\"></i> Sign In Icon<br>
    <i class=\"fas fa-envelope\"></i> Email Icon<br>
    <i class=\"fas fa-lock\"></i> Lock Icon<br>
</body>
</html>';
file_put_contents('public/fontawesome-test.html', \$html);
echo 'Test file created: public/fontawesome-test.html' . PHP_EOL;
"

echo ""
echo "🎯 FONTAWESOME TROUBLESHOOTING COMPLETED!"
echo "========================================"
echo ""
echo "✅ Multiple CDN sources added"
echo "✅ Fallback CSS created"  
echo "✅ Test file generated"
echo ""
echo "📋 Test methods:"
echo "1. Visit: https://maknaacademy.com/fontawesome-test.html"
echo "2. Check login page: https://maknaacademy.com/login"
echo "3. Open browser console for any CDN errors"
echo ""
echo "💡 If icons still don't show:"
echo "- Check browser console for CDN loading errors"
echo "- Verify internet connection"
echo "- Try different browser"