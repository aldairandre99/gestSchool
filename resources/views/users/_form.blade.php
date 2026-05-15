@php($user = $user ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-input name="name" :label="__('Name')" :value="$user?->name" required />
    <x-input name="email" :label="__('Email')" type="email" :value="$user?->email" required />
    <x-input name="phone" :label="__('Phone')" :value="$user?->phone" />
    <div class="flex items-end form-group">
        <x-checkbox name="is_active" :label="__('Active')" :checked="old('is_active', $user?->is_active ?? true)" />
    </div>
    <x-input name="password" :label="__('Password')" type="password" />
    <x-input name="password_confirmation" :label="__('Confirm Password')" type="password" />
</div>

<div class="form-group">
    <label class="form-label">{{ __('Roles') }}</label>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
        @foreach($roles as $role)
            @php($checked = collect(old('roles', $user?->roles->pluck('name')->all() ?? []))->contains($role->name))
            <x-checkbox name="roles[]" :value="$role->name" :checked="$checked" :hiddenFallback="false" :label="str_replace('_', ' ', $role->name)" />
        @endforeach
    </div>
</div>

<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('users.index')">{{ __('Cancel') }}</x-btn>
</div>
