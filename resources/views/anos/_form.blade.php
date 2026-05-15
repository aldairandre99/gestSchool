@php($ano = $ano ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-input name="codigo" :label="__('Code')" :value="$ano?->codigo" placeholder="2026/2027" required />
    <div class="flex items-end form-group">
        <x-checkbox name="activo" :label="__('Active Year')" :checked="old('activo', $ano?->activo)" />
    </div>
    <x-input name="inicio" :label="__('Start')" type="date" :value="$ano?->inicio?->format('Y-m-d')" required />
    <x-input name="fim" :label="__('End')" type="date" :value="$ano?->fim?->format('Y-m-d')" required />
</div>
<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('anos.index')">{{ __('Cancel') }}</x-btn>
</div>
