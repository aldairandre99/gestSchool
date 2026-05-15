<x-guest-layout>
    <div class="card">
        <h2 class="card-title">{{ __('Confirm Password') }}</h2>
        <p class="text-sm text-muted mb-6">{{ __('Confirm password screen subtitle') }}</p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <x-input name="password" :label="__('Password')" type="password" required autocomplete="current-password" />
            <x-btn variant="primary" type="submit" class="w-full">{{ __('Confirm') }}</x-btn>
        </form>
    </div>
</x-guest-layout>
