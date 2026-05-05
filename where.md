color code is here
bg-[#FDFDFC]
<x-app-logo class="mb-8"  href="{{ route('home') }}" />

<div class="flex items-center gap-2">
        @auth
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                Login
            </a>
            <a href="{{ route('register') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900">
                Register
            </a>
        @endauth
</div>

http://localhost:8000
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache 