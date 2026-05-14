<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'GestSchool') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="min-h-screen flex items-center justify-center px-6">
        <div class="max-w-2xl text-center">
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900">{{ __('School Management') }}</h1>
            <p class="mt-4 text-gray-600">{{ config('app.name') }} — Gestão escolar para o ensino médio.</p>

            <div class="mt-8 flex items-center justify-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-gray-900 text-white rounded-md text-sm">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 bg-gray-900 text-white rounded-md text-sm">{{ __('Log in') }}</a>
                @endauth
            </div>

            <div class="mt-6 text-xs text-gray-500 flex justify-center gap-2">
                <a href="{{ route('locale.switch', 'pt') }}" class="px-2 py-1 rounded {{ app()->getLocale() === 'pt' ? 'bg-gray-800 text-white' : 'bg-gray-100' }}">PT</a>
                <a href="{{ route('locale.switch', 'en') }}" class="px-2 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-gray-800 text-white' : 'bg-gray-100' }}">EN</a>
            </div>
        </div>
    </div>
</body>
</html>
