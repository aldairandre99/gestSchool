@php($trimestre = $trimestre ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="ano_lectivo_id" :value="__('School Year')" />
        <select id="ano_lectivo_id" name="ano_lectivo_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach($anos as $a)<option value="{{ $a->id }}" @selected(old('ano_lectivo_id', $trimestre?->ano_lectivo_id) == $a->id)>{{ $a->codigo }}</option>@endforeach
        </select>
    </div>
    <div>
        <x-input-label for="numero" :value="__('Term')" />
        <select id="numero" name="numero" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach([1, 2, 3] as $n)<option value="{{ $n }}" @selected(old('numero', $trimestre?->numero) == $n)>{{ $n }}º {{ __('Term') }}</option>@endforeach
        </select>
    </div>
    <div><x-input-label for="inicio" :value="__('Start')" /><x-text-input id="inicio" name="inicio" type="date" class="mt-1 block w-full" :value="old('inicio', $trimestre?->inicio?->format('Y-m-d'))" required /></div>
    <div><x-input-label for="fim" :value="__('End')" /><x-text-input id="fim" name="fim" type="date" class="mt-1 block w-full" :value="old('fim', $trimestre?->fim?->format('Y-m-d'))" required /></div>
    <div class="flex items-end">
        <label class="inline-flex items-center text-sm">
            <input type="hidden" name="aberto" value="0">
            <input type="checkbox" name="aberto" value="1" {{ old('aberto', $trimestre?->aberto ?? true) ? 'checked' : '' }} class="rounded border-gray-300">
            <span class="ms-2">{{ __('Open') }}</span>
        </label>
    </div>
</div>
<div class="mt-6 flex gap-3"><button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button><a href="{{ route('trimestres.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a></div>
