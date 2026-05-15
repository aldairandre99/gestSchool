@php($encarregado = $encarregado ?? null)
@php($u = $encarregado?->user)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-input name="name" :label="__('Name')" :value="$u?->name" required />
    <x-input name="email" :label="__('Email')" type="email" :value="$u?->email" required />
    <x-input name="phone" :label="__('Phone')" :value="$u?->phone" />
    <x-input name="bi" :label="__('BI Number')" :value="$encarregado?->bi" />
    <x-input name="data_nascimento" :label="__('Birth Date')" type="date" :value="$encarregado?->data_nascimento?->format('Y-m-d')" />
    <x-select name="sexo" :label="__('Gender')">
        <option value="M" @selected(old('sexo', $encarregado?->sexo) === 'M')>{{ __('Male') }}</option>
        <option value="F" @selected(old('sexo', $encarregado?->sexo) === 'F')>{{ __('Female') }}</option>
    </x-select>
    <x-input name="profissao" label="{{ __('Profession') }}" :value="$encarregado?->profissao" />
    <x-input name="local_trabalho" label="{{ __('Workplace') }}" :value="$encarregado?->local_trabalho" />
    <div class="sm:col-span-2"><x-textarea name="morada" :label="__('Address')" :value="$encarregado?->morada" :rows="2" /></div>
    <x-input name="password" :label="__('Password')" type="password" />
    <x-input name="password_confirmation" :label="__('Confirm Password')" type="password" />
</div>

<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('encarregados.index')">{{ __('Cancel') }}</x-btn>
</div>
