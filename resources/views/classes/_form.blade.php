@php($classe = $classe ?? null)
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <x-input name="nome" :label="__('Name')" :value="$classe?->nome" required />
    <x-input name="ordem" :label="__('Order')" type="number" :value="$classe?->ordem ?? 0" />
    <x-input name="nivel" :label="__('Level')" :value="$classe?->nivel" />
</div>

<div class="form-group">
    <label class="form-label">{{ __('Subjects') }}</label>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-60 overflow-y-auto p-4 border border-gray-100 rounded bg-gray-50">
        @php($selected = collect(old('disciplinas', $classe?->disciplinas->pluck('id')->all() ?? [])))
        @foreach($disciplinas as $d)
            <x-checkbox name="disciplinas[]" :value="$d->id" :checked="$selected->contains($d->id)" :hiddenFallback="false" :label="$d->nome" />
        @endforeach
    </div>
</div>

<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('classes.index')">{{ __('Cancel') }}</x-btn>
</div>
