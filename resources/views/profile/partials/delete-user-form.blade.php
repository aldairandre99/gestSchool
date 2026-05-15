<p class="text-sm text-muted mb-4">Esta acção é definitiva. Todos os dados associados à sua conta serão eliminados.</p>

<x-btn variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
    Eliminar conta
</x-btn>

<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
        @csrf
        @method('delete')

        <h3 class="card-title">Tem a certeza?</h3>
        <p class="text-sm text-muted mb-4">Indique a sua palavra-passe para confirmar a eliminação permanente da conta.</p>

        <x-input name="password" :label="__('Password')" type="password" />

        <div class="mt-4 flex justify-end gap-3">
            <x-btn variant="secondary" type="button" x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-btn>
            <x-btn variant="danger" type="submit">Eliminar</x-btn>
        </div>
    </form>
</x-modal>
