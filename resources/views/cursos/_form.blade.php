@php($curso = $curso ?? null)
@php($currentClasses = $curso ? $curso->classes->keyBy('id') : collect())
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="sm:col-span-2"><x-input name="nome" :label="__('Name')" :value="$curso?->nome" required /></div>
    <x-input name="sigla" :label="__('Abbreviation')" :value="$curso?->sigla" required  />
    <div class="sm:col-span-3"><x-textarea name="descricao" label="{{ __('Description') }}" :value="$curso?->descricao" :rows="2" /></div>
    <div class="flex items-end form-group">
        <x-checkbox name="activo" :label="__('Active')" :checked="old('activo', $curso?->activo ?? true)" />
    </div>
</div>

<div class="form-group">
    <label class="form-label">{{ __('Course classes (with year number)') }}</label>
    <p class="form-help mb-2">{{ __('Indicate the course year number for each class.') }}</p>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach($classesMedio as $classe)
            @php($pivot = $currentClasses->get($classe->id))
            <div class="border border-gray-100 rounded p-3">
                <div class="font-semibold text-navy text-sm">{{ $classe->nome }}</div>
                <label class="form-label mt-2">Ano</label>
                <input type="number" name="classes[{{ $classe->id }}]" value="{{ old('classes.' . $classe->id, $pivot?->pivot?->ano) }}" min="1" max="6" class="form-input" placeholder="—">
            </div>
        @endforeach
    </div>
</div>

<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('cursos.index')">{{ __('Cancel') }}</x-btn>
</div>
