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

<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>