<x-guest-layout>
    <div class="card">
        <h2 class="card-title">Verificar e-mail</h2>
        <p class="text-sm text-muted mb-6">Obrigado pelo registo. Confirme o seu e-mail clicando no link que enviámos.</p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success">Nova hiperligação de verificação enviada.</div>
        @endif

        <div class="flex items-center justify-between mt-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-btn variant="primary" type="submit">Reenviar e-mail</x-btn>
            </form>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-muted hover:text-navy">{{ __('Log Out') }}</button>
            </form>
        </div>
    </div>
</x-guest-layout>
