@php($user = $user ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="name" :value="__('Name')" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user?->name)" required />
    </div>
    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user?->email)" required />
    </div>
    <div>
        <x-input-label for="phone" :value="__('Phone')" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user?->phone)" />
    </div>
    <div>
        <x-input-label for="password" :value="__('Password')" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
    </div>
    <div>
        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
    </div>
    <div class="flex items-end">
        <label class="inline-flex items-center">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user?->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300">
            <span class="ms-2 text-sm">{{ __('Active') }}</span>
        </label>
    </div>

    <div class="sm:col-span-2">
        <x-input-label :value="__('Roles')" />
        <div class="mt-2 flex flex-wrap gap-3">
            @foreach($roles as $role)
                @php($checked = collect(old('roles', $user?->roles->pluck('name')->all() ?? []))->contains($role->name))
                <label class="inline-flex items-center text-sm">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $checked ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="ms-2">{{ str_replace('_', ' ', $role->name) }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button>
    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a>
</div>
