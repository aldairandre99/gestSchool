<x-guest-layout>
    <div class="card">
        <h2 class="card-title">{{ __('Forgot your password?') }}</h2>
        <p class="text-sm text-muted mb-6">Indique o seu e-mail e enviaremos uma hiperligação para repor a palavra-passe.</p>

        @if(session('status'))<div class="alert alert-info">{{ session('status') }}</div>@endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <x-input name="email" :label="__('Email')" type="email" required autofocus />
            <div class="flex items-center justify-between">
                <a href="{{ route('login') }}" class="text-xs text-primary hover:underline">{{ __('Back') }}</a>
                <x-btn variant="primary" type="submit">{{ __('Email Password Reset Link') }}</x-btn>
            </div>
        </form>
    </div>
</x-guest-layout>
