@php($trimestre = $trimestre ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-select name="ano_lectivo_id" :label="__('School Year')" required :placeholder="null">
        @foreach($anos as $a)<option value="{{ $a->id }}" @selected(old('ano_lectivo_id', $trimestre?->ano_lectivo_id) == $a->id)>{{ $a->codigo }}</option>@endforeach
    </x-select>
    <x-select name="numero" :label="__('Term')" required :placeholder="null">
        @foreach([1, 2, 3] as $n)<option value="{{ $n }}" @selected(old('numero', $trimestre?->numero) == $n)>{{ $n }}º {{ __('Term') }}</option>@endforeach
    </x-select>
    <x-input name="inicio" :label="__('Start')" type="date" :value="$trimestre?->inicio?->format('Y-m-d')" required />
    <x-input name="fim" :label="__('End')" type="date" :value="$trimestre?->fim?->format('Y-m-d')" required />
    <div class="flex items-end form-group">
        <x-checkbox name="aberto" :label="__('Open')" :checked="old('aberto', $trimestre?->aberto ?? true)" />
    </div>
</div>
<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('trimestres.index')">{{ __('Cancel') }}</x-btn>
</div>
