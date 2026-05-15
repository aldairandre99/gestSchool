<x-guest-layout>
    <div class="card">
        <h2 class="card-title">{{ __('Log in') }}</h2>
        @if(session('status'))<div class="alert alert-info">{{ session('status') }}</div>@endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <x-input name="email" :label="__('Email')" type="email" required autofocus />

            <x-input name="password" :label="__('Password')" type="password" required autocomplete="current-password" />

            <div class="flex items-center justify-between mb-6">
                <x-checkbox name="remember" :label="__('Remember me')" :hiddenFallback="false" />
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-primary hover:underline">{{ __('Forgot your password?') }}</a>
                @endif
            </div>

            <x-btn variant="primary" type="submit" class="w-full">{{ __('Log in') }}</x-btn>
        </form>
    </div>
</x-guest-layout>
