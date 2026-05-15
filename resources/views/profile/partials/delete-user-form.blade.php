<p class="text-sm text-muted mb-4">{{ __('This action is permanent.') }}</p>

<x-btn variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">{{ __('Delete Account') }}</x-btn>

<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
        @csrf
        @method('delete')

        <h3 class="card-title">{{ __('Are you sure?') }}</h3>
        <p class="text-sm text-muted mb-4">{{ __('Enter your password to confirm permanent deletion.') }}</p>

        <x-input name="password" :label="__('Password')" type="password" />

        <div class="mt-4 flex justify-end gap-3">
            <x-btn variant="secondary" type="button" x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-btn>
            <x-btn variant="danger" type="submit">{{ __('Delete') }}</x-btn>
        </div>
    </form>
</x-modal>
