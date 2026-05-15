@php($funcionario = $funcionario ?? null)
@php($u = $funcionario?->user)
@php($currentRole = old('role', $u?->roles->first()?->name))
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-input name="name" :label="__('Name')" :value="$u?->name" required />
    <x-input name="email" :label="__('Email')" type="email" :value="$u?->email" required />
    <x-input name="phone" :label="__('Phone')" :value="$u?->phone" />
    <x-select name="role" :label="__('Roles')" required :placeholder="null">
        @foreach($roles as $role)
            <option value="{{ $role->name }}" @selected($currentRole === $role->name)>{{ str_replace('_', ' ', $role->name) }}</option>
        @endforeach
    </x-select>
    <x-input name="numero_funcionario" label="Nº de funcionário" :value="$funcionario?->numero_funcionario" />
    <x-input name="bi" :label="__('BI Number')" :value="$funcionario?->bi" />
    <x-input name="data_nascimento" :label="__('Birth Date')" type="date" :value="$funcionario?->data_nascimento?->format('Y-m-d')" />
    <x-select name="sexo" :label="__('Gender')">
        <option value="M" @selected(old('sexo', $funcionario?->sexo) === 'M')>{{ __('Male') }}</option>
        <option value="F" @selected(old('sexo', $funcionario?->sexo) === 'F')>{{ __('Female') }}</option>
    </x-select>
    <x-input name="cargo" :label="__('Position')" :value="$funcionario?->cargo" />
    <x-input name="departamento" :label="__('Department')" :value="$funcionario?->departamento" />
    <x-input name="data_admissao" :label="__('Hire Date')" type="date" :value="$funcionario?->data_admissao?->format('Y-m-d')" />
    <div class="sm:col-span-2"><x-textarea name="morada" :label="__('Address')" :value="$funcionario?->morada" :rows="2" /></div>
    <x-input name="password" :label="__('Password')" type="password" />
    <x-input name="password_confirmation" :label="__('Confirm Password')" type="password" />
</div>

<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('funcionarios.index')">{{ __('Cancel') }}</x-btn>
</div>
