<x-guest-layout>
    <div class="card">
        <h2 class="card-title">{{ __('Register') }}</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <x-input name="name" :label="__('Name')" required autofocus />
            <x-input name="email" :label="__('Email')" type="email" required />
            <x-input name="password" :label="__('Password')" type="password" required autocomplete="new-password" />
            <x-input name="password_confirmation" :label="__('Confirm Password')" type="password" required autocomplete="new-password" />

            <div class="flex items-center justify-between mt-4">
                <a href="{{ route('login') }}" class="text-xs text-primary hover:underline">{{ __('Already registered?') }}</a>
                <x-btn variant="primary" type="submit">{{ __('Register') }}</x-btn>
            </div>
        </form>
    </div>
</x-guest-layout>
