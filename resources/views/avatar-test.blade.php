<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avatar Test - Makna Academy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">🖼️ Avatar System Test</h1>
            <p class="text-gray-600">Testing Google OAuth avatars and default avatar generation</p>
        </div>

        <!-- Current User -->
        @auth
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Current User (You)</h2>
            <div class="flex items-center space-x-8">
                <!-- Different sizes -->
                <div class="text-center">
                    <x-user-avatar :size="32" />
                    <p class="text-xs text-gray-500 mt-1">32px</p>
                </div>
                <div class="text-center">
                    <x-user-avatar :size="50" />
                    <p class="text-xs text-gray-500 mt-1">50px</p>
                </div>
                <div class="text-center">
                    <x-user-avatar :size="80" />
                    <p class="text-xs text-gray-500 mt-1">80px</p>
                </div>
                <div class="text-center">
                    <x-user-avatar :size="150" :show-name="true" />
                    <p class="text-xs text-gray-500 mt-1">150px + name</p>
                </div>
            </div>
        </div>
        @endauth

        <!-- Google Users -->
        @if($googleUsers->count() > 0)
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">🔥 Google OAuth Users</h2>
            <div class="space-y-4">
                @foreach($googleUsers as $user)
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <x-user-avatar :user="$user" :size="60" />
                        <div class="ml-4">
                            <h3 class="font-medium text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            <p class="text-xs text-green-600">✅ Google Avatar</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Registered</p>
                        <p class="text-sm font-medium">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Regular Users with Generated Avatars -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">👤 Regular Users (Generated Avatars)</h2>
            <div class="space-y-4">
                @foreach($regularUsers as $user)
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <x-user-avatar :user="$user" :size="60" />
                        <div class="ml-4">
                            <h3 class="font-medium text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            <p class="text-xs text-blue-600">🎨 Generated Avatar</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Role</p>
                        <p class="text-sm font-medium">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Avatar Sizes Demo -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">📏 Avatar Sizes Demo</h2>
            <div class="flex items-end space-x-6">
                @php $demoUser = $googleUsers->first() ?? $regularUsers->first() @endphp
                @if($demoUser)
                    @foreach([24, 32, 40, 50, 60, 80, 100, 150] as $size)
                    <div class="text-center">
                        <x-user-avatar :user="$demoUser" :size="$size" />
                        <p class="text-xs text-gray-500 mt-1">{{ $size }}px</p>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Helper Function Examples -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold mb-4">🛠️ Helper Function Examples</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-medium mb-2">Usage in Blade:</h3>
                    <pre class="bg-gray-100 p-3 rounded text-sm"><code>&lt;x-user-avatar :user="$user" :size="60" /&gt;
&lt;x-user-avatar :show-name="true" /&gt;
&lt;img src="{{ user_avatar($user, 100) }}" alt="Avatar" /&gt;</code></pre>
                </div>
                <div>
                    <h3 class="font-medium mb-2">PHP Helper Functions:</h3>
                    <pre class="bg-gray-100 p-3 rounded text-sm"><code>user_avatar($user, $size)
default_avatar($size, $name)
get_initials($name)</code></pre>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="text-center mt-8">
            <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                ← Back to Home
            </a>
            <a href="{{ route('login') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                🔐 Test Google Login
            </a>
        </div>
    </div>
</body>
</html>
