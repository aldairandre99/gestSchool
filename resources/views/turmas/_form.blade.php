@php($turma = $turma ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-select name="classe_id" label="Classe" required>
        @foreach($classes as $c)<option value="{{ $c->id }}" @selected(old('classe_id', $turma?->classe_id) == $c->id)>{{ $c->nome }}</option>@endforeach
    </x-select>
    <x-select name="ano_lectivo_id" :label="__('School Year')" required>
        @foreach($anos as $a)<option value="{{ $a->id }}" @selected(old('ano_lectivo_id', $turma?->ano_lectivo_id) == $a->id)>{{ $a->codigo }}</option>@endforeach
    </x-select>
    <x-input name="nome" :label="__('Name')" :value="$turma?->nome" placeholder="A, B, C…" required />
    <x-input name="sala" :label="__('Room')" :value="$turma?->sala" />
    <x-select name="turno" :label="__('Shift')">
        @foreach(['Manhã', 'Tarde', 'Noite'] as $opt)<option value="{{ $opt }}" @selected(old('turno', $turma?->turno) === $opt)>{{ $opt }}</option>@endforeach
    </x-select>
    <x-input name="capacidade" :label="__('Capacity')" type="number" :value="$turma?->capacidade ?? 40" />
    <div class="sm:col-span-2">
        <x-select name="director_turma_id" :label="__('Class Director')">
            @foreach($professores as $p)<option value="{{ $p->id }}" @selected(old('director_turma_id', $turma?->director_turma_id) == $p->id)>{{ $p->user->name }}</option>@endforeach
        </x-select>
    </div>
</div>
<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('turmas.index')">{{ __('Cancel') }}</x-btn>
</div>
