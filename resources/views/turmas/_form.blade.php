@php($turma = $turma ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="classe_id" :value="__('Class') === 'Turma' ? 'Classe' : __('Class')" />
        <select id="classe_id" name="classe_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($classes as $c)<option value="{{ $c->id }}" @selected(old('classe_id', $turma?->classe_id) == $c->id)>{{ $c->nome }}</option>@endforeach
        </select>
    </div>
    <div>
        <x-input-label for="ano_lectivo_id" :value="__('School Year')" />
        <select id="ano_lectivo_id" name="ano_lectivo_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($anos as $a)<option value="{{ $a->id }}" @selected(old('ano_lectivo_id', $turma?->ano_lectivo_id) == $a->id)>{{ $a->codigo }}</option>@endforeach
        </select>
    </div>
    <div><x-input-label for="nome" :value="__('Name')" /><x-text-input id="nome" name="nome" class="mt-1 block w-full" :value="old('nome', $turma?->nome)" placeholder="A, B, C..." required /></div>
    <div><x-input-label for="sala" :value="__('Room')" /><x-text-input id="sala" name="sala" class="mt-1 block w-full" :value="old('sala', $turma?->sala)" /></div>
    <div>
        <x-input-label for="turno" :value="__('Shift')" />
        <select id="turno" name="turno" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach(['Manhã', 'Tarde', 'Noite'] as $opt)<option value="{{ $opt }}" @selected(old('turno', $turma?->turno) === $opt)>{{ $opt }}</option>@endforeach
        </select>
    </div>
    <div><x-input-label for="capacidade" :value="__('Capacity')" /><x-text-input id="capacidade" name="capacidade" type="number" class="mt-1 block w-full" :value="old('capacidade', $turma?->capacidade ?? 40)" /></div>
    <div class="sm:col-span-2">
        <x-input-label for="director_turma_id" :value="__('Class Director')" />
        <select id="director_turma_id" name="director_turma_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($professores as $p)<option value="{{ $p->id }}" @selected(old('director_turma_id', $turma?->director_turma_id) == $p->id)>{{ $p->user->name }}</option>@endforeach
        </select>
    </div>
</div>
<div class="mt-6 flex gap-3"><button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button><a href="{{ route('turmas.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a></div>
