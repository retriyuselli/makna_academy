@if($user)
<div class="flex items-center {{ $class }}">
    <img 
        src="{{ $avatarUrl() }}" 
        alt="{{ $userName() }}"
        class="rounded-full object-cover border border-gray-200 shadow-sm"
        style="width: {{ $size }}px; height: {{ $size }}px; min-width: {{ $size }}px;"
        onerror="this.src='{{ default_avatar($size, $userName()) }}'"
        loading="lazy"
    >
    
    @if($showName)
        <div class="ml-3">
            <p class="text-sm font-medium text-gray-900">{{ $userName() }}</p>
            <p class="text-xs text-gray-500">{{ $user->email }}</p>
        </div>
    @endif
</div>
@else
<div class="flex items-center {{ $class }}">
    <img 
        src="{{ default_avatar($size) }}" 
        alt="Guest"
        class="rounded-full object-cover border border-gray-200 shadow-sm"
        style="width: {{ $size }}px; height: {{ $size }}px; min-width: {{ $size }}px;"
        loading="lazy"
    >
    
    @if($showName)
        <div class="ml-3">
            <p class="text-sm font-medium text-gray-900">Guest</p>
            <p class="text-xs text-gray-500">Not logged in</p>
        </div>
    @endif
</div>
@endif