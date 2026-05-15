<x-guest-layout>
    <div class="card">
        <h2 class="card-title">Confirmar palavra-passe</h2>
        <p class="text-sm text-muted mb-6">Área segura. Confirme a sua palavra-passe para continuar.</p>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <x-input name="password" :label="__('Password')" type="password" required autocomplete="current-password" />
            <x-btn variant="primary" type="submit" class="w-full">Confirmar</x-btn>
        </form>
    </div>
</x-guest-layout>
