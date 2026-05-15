<x-guest-layout>
    <div class="card">
        <h2 class="card-title">{{ __('Verify email') }}</h2>
        <p class="text-sm text-muted mb-6">{{ __('Thanks for registering, confirm your email.') }}</p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success">{{ __('New verification link sent.') }}</div>
        @endif

        <div class="flex items-center justify-between mt-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-btn variant="primary" type="submit">{{ __('Resend email') }}</x-btn>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-muted hover:text-navy">{{ __('Log Out') }}</button>
            </form>
        </div>
    </div>
</x-guest-layout>
