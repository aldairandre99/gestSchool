@php($professor = $professor ?? null)
@php($u = $professor?->user)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-input name="name" :label="__('Name')" :value="$u?->name" required />
    <x-input name="email" :label="__('Email')" type="email" :value="$u?->email" required />
    <x-input name="phone" :label="__('Phone')" :value="$u?->phone" />
    <x-input name="numero_professor" :label="__('Process Number')" :value="$professor?->numero_professor" />
    <x-input name="bi" :label="__('BI Number')" :value="$professor?->bi" />
    <x-input name="data_nascimento" :label="__('Birth Date')" type="date" :value="$professor?->data_nascimento?->format('Y-m-d')" />
    <x-select name="sexo" :label="__('Gender')">
        <option value="M" @selected(old('sexo', $professor?->sexo) === 'M')>{{ __('Male') }}</option>
        <option value="F" @selected(old('sexo', $professor?->sexo) === 'F')>{{ __('Female') }}</option>
    </x-select>
    <x-input name="especialidade" label="Especialidade" :value="$professor?->especialidade" />
    <div class="sm:col-span-2">
        <x-input name="habilitacoes" :label="__('Qualification')" :value="$professor?->habilitacoes" />
    </div>
    <div class="sm:col-span-2">
        <x-textarea name="disciplinas" :label="__('Subjects')" :value="$professor?->disciplinas" :rows="2" />
    </div>
    <x-input name="data_admissao" :label="__('Hire Date')" type="date" :value="$professor?->data_admissao?->format('Y-m-d')" />
    <div class="flex items-end form-group">
        <x-checkbox name="assistente" :label="__('Assistant Teacher')" :checked="old('assistente', $professor?->assistente)" />
    </div>
    <div class="sm:col-span-2">
        <x-textarea name="morada" :label="__('Address')" :value="$professor?->morada" :rows="2" />
    </div>
    <x-input name="password" :label="__('Password')" type="password" />
    <x-input name="password_confirmation" :label="__('Confirm Password')" type="password" />
</div>

<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('professores.index')">{{ __('Cancel') }}</x-btn>
</div>
