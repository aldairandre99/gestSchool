@php($turma = $turma ?? null)
@php($classeNivel = $classes->mapWithKeys(fn($c) => [$c->id => $c->nivel])->toJson())

<div x-data="{
        classeId: '{{ old('classe_id', $turma?->classe_id) }}',
        classeNivel: {{ $classeNivel }},
        cursoId: '{{ old('curso_id', $turma?->curso_id) }}',
        get isMedio() { return this.classeNivel[this.classeId] === 'ensino_medio'; }
     }"
     x-init="$watch('classeId', () => { if (!isMedio) cursoId = '' })">

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="form-group">
            <label class="form-label">{{ __('Grade') }} <span class="text-danger">*</span></label>
            <select name="classe_id" x-model="classeId" required class="form-select">
                <option value="">—</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}">
                        {{ $c->nome }} ({{ $c->nivel === 'ensino_medio' ? 'Médio' : 'Base' }})
                    </option>
                @endforeach
            </select>
            @error('classe_id')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group" x-show="isMedio" x-cloak>
            <label class="form-label">{{ __('Course') }} <span class="text-danger">*</span></label>
            <select name="curso_id" x-model="cursoId" class="form-select">
                <option value="">—</option>
                @foreach($cursos as $cu)
                    <option value="{{ $cu->id }}">{{ $cu->sigla }} — {{ $cu->nome }}</option>
                @endforeach
            </select>
            @error('curso_id')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <x-select name="ano_lectivo_id" :label="__('School Year')" required>
            @foreach($anos as $a)<option value="{{ $a->id }}" @selected(old('ano_lectivo_id', $turma?->ano_lectivo_id) == $a->id)>{{ $a->codigo }}</option>@endforeach
        </x-select>
        <x-input name="nome" :label="__('Name')" :value="$turma?->nome" placeholder="A, B, C…" required />
        <x-input name="sala" :label="__('Room')" :value="$turma?->sala" />
        <x-select name="turno" :label="__('Shift')">
            @foreach(['Manhã' => __('Morning'), 'Tarde' => __('Afternoon'), 'Noite' => __('Evening')] as $val => $label)<option value="{{ $val }}" @selected(old('turno', $turma?->turno) === $val)>{{ $label }}</option>@endforeach
        </x-select>
        <x-input name="capacidade" :label="__('Capacity')" type="number" :value="$turma?->capacidade ?? 40" />
        <div class="sm:col-span-2">
            <x-select name="director_turma_id" :label="__('Class Director')">
                @foreach($professores as $p)<option value="{{ $p->id }}" @selected(old('director_turma_id', $turma?->director_turma_id) == $p->id)>{{ $p->user->name }}</option>@endforeach
            </x-select>
        </div>
    </div>
</div>

<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('turmas.index')">{{ __('Cancel') }}</x-btn>
</div>
