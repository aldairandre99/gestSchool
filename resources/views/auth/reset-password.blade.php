<x-guest-layout>
    <div class="card">
        <h2 class="card-title">{{ __('Reset password') }}</h2>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <x-input name="email" :label="__('Email')" type="email" :value="$request->email" required autofocus />
            <x-input name="password" :label="__('Password')" type="password" required autocomplete="new-password" />
            <x-input name="password_confirmation" :label="__('Confirm Password')" type="password" required autocomplete="new-password" />
            <x-btn variant="primary" type="submit" class="w-full">{{ __('Reset') }}</x-btn>
        </form>
    </div>
</x-guest-layout>
