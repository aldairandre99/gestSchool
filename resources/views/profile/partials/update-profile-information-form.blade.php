<form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <x-input name="name" :label="__('Name')" :value="$user->name" required />
    <x-input name="email" :label="__('Email')" type="email" :value="$user->email" required />

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <p class="text-sm text-muted mt-2">
            E-mail por verificar.
            <button form="send-verification" class="btn-link">Reenviar verificação</button>
        </p>
        @if (session('status') === 'verification-link-sent')
            <p class="text-sm text-success mt-2">Hiperligação enviada.</p>
        @endif
    @endif

    <div class="flex items-center gap-4 mt-4">
        <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-success">Guardado.</p>
        @endif
    </div>
</form>
