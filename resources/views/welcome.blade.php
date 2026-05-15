<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'GestSchool') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:300,400,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-6">
        <div class="max-w-xl w-full">
            <div class="card text-center">
                <h1 class="page-title">{{ __('School Management') }}</h1>
                <p class="page-subtitle mb-8">{{ config('app.name') }} — Gestão escolar para o ensino médio.</p>

                <div class="flex items-center justify-center gap-3">
                    @auth
                        <x-btn variant="primary" :href="route('dashboard')">{{ __('Dashboard') }}</x-btn>
                    @else
                        <x-btn variant="primary" :href="route('login')">{{ __('Log in') }}</x-btn>
                    @endauth
                </div>

                <div class="mt-6 text-xs flex justify-center gap-2">
                    <a href="{{ route('locale.switch', 'pt') }}" class="px-3 py-1.5 rounded {{ app()->getLocale() === 'pt' ? 'bg-navy text-white' : 'bg-gray-100' }}">PT</a>
                    <a href="{{ route('locale.switch', 'en') }}" class="px-3 py-1.5 rounded {{ app()->getLocale() === 'en' ? 'bg-navy text-white' : 'bg-gray-100' }}">EN</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
