@php($disciplina = $disciplina ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-input name="nome" :label="__('Name')" :value="$disciplina?->nome" required />
    <x-input name="sigla" :label="__('Abbreviation')" :value="$disciplina?->sigla" />
    <x-input name="carga_horaria_semanal" :label="__('Weekly Hours')" type="number" :value="$disciplina?->carga_horaria_semanal" />
    <div class="flex items-end form-group">
        <x-checkbox name="activa" :label="__('Active')" :checked="old('activa', $disciplina?->activa ?? true)" />
    </div>
</div>
<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('disciplinas.index')">{{ __('Cancel') }}</x-btn>
</div>
