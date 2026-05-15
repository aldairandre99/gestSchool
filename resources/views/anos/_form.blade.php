@php($ano = $ano ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div><x-input-label for="codigo" :value="__('Code')" /><x-text-input id="codigo" name="codigo" class="mt-1 block w-full" :value="old('codigo', $ano?->codigo)" placeholder="2026/2027" required /></div>
    <div class="flex items-end">
        <label class="inline-flex items-center text-sm">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1" {{ old('activo', $ano?->activo) ? 'checked' : '' }} class="rounded border-gray-300">
            <span class="ms-2">{{ __('Active Year') }}</span>
        </label>
    </div>
    <div><x-input-label for="inicio" :value="__('Start')" /><x-text-input id="inicio" name="inicio" type="date" class="mt-1 block w-full" :value="old('inicio', $ano?->inicio?->format('Y-m-d'))" required /></div>
    <div><x-input-label for="fim" :value="__('End')" /><x-text-input id="fim" name="fim" type="date" class="mt-1 block w-full" :value="old('fim', $ano?->fim?->format('Y-m-d'))" required /></div>
</div>
<div class="mt-6 flex gap-3"><button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button><a href="{{ route('anos.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a></div>
