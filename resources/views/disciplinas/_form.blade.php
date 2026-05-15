@php($disciplina = $disciplina ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div><x-input-label for="nome" :value="__('Name')" /><x-text-input id="nome" name="nome" class="mt-1 block w-full" :value="old('nome', $disciplina?->nome)" required /></div>
    <div><x-input-label for="sigla" :value="__('Abbreviation')" /><x-text-input id="sigla" name="sigla" class="mt-1 block w-full" :value="old('sigla', $disciplina?->sigla)" /></div>
    <div><x-input-label for="carga_horaria_semanal" :value="__('Weekly Hours')" /><x-text-input id="carga_horaria_semanal" name="carga_horaria_semanal" type="number" class="mt-1 block w-full" :value="old('carga_horaria_semanal', $disciplina?->carga_horaria_semanal)" /></div>
    <div class="flex items-end">
        <label class="inline-flex items-center text-sm">
            <input type="hidden" name="activa" value="0">
            <input type="checkbox" name="activa" value="1" {{ old('activa', $disciplina?->activa ?? true) ? 'checked' : '' }} class="rounded border-gray-300">
            <span class="ms-2">{{ __('Active') }}</span>
        </label>
    </div>
</div>
<div class="mt-6 flex gap-3"><button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button><a href="{{ route('disciplinas.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a></div>
