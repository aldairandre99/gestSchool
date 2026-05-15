@php($classe = $classe ?? null)
<div x-data="{ nivel: '{{ old('nivel', $classe?->nivel ?? 'ensino_base') }}' }">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-input name="nome" :label="__('Name')" :value="$classe?->nome" required />
        <div class="form-group">
            <label class="form-label">{{ __('Level') }} <span class="text-danger">*</span></label>
            <select name="nivel" x-model="nivel" required class="form-select">
                <option value="ensino_base">{{ __('Basic Education') }}</option>
                <option value="ensino_medio">{{ __('Secondary Education') }}</option>
            </select>
        </div>
        <x-input name="ordem" :label="__('Order')" type="number" :value="$classe?->ordem ?? 0" />
    </div>

    <div x-show="nivel === 'ensino_base'" class="form-group">
        <label class="form-label">{{ __('Compulsory subjects (basic education)') }}</label>
        <p class="form-help mb-2">{{ __('All students of this class will have these subjects.') }}</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-60 overflow-y-auto p-4 border border-gray-100 rounded bg-gray-50">
            @php($selected = collect(old('disciplinas', $classe?->disciplinas->pluck('id')->all() ?? [])))
            @foreach($disciplinas as $d)
                <x-checkbox name="disciplinas[]" :value="$d->id" :checked="$selected->contains($d->id)" :hiddenFallback="false" :label="$d->nome" />
            @endforeach
        </div>
    </div>

    <div x-show="nivel === 'ensino_medio'" x-cloak class="alert alert-info">
        {{ __('For secondary education classes the subjects vary by course. Configure them via') }}
        <a href="{{ route('cursos.index') }}" class="font-semibold underline">{{ __('Courses') }}</a>.
    </div>
</div>

<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('classes.index')">{{ __('Cancel') }}</x-btn>
</div>
