<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <x-input name="current_password" id="update_password_current_password" :label="__('Password') . ' actual'" type="password" autocomplete="current-password" />
    <x-input name="password" id="update_password_password" :label="__('New Password')" type="password" autocomplete="new-password" />
    <x-input name="password_confirmation" id="update_password_password_confirmation" :label="__('Confirm Password')" type="password" autocomplete="new-password" />

    <div class="flex items-center gap-4 mt-4">
        <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
        @if (session('status') === 'password-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-success">Guardado.</p>
        @endif
    </div>
</form>
