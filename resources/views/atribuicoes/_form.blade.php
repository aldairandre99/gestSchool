@php($atribuicao = $atribuicao ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="professor_id" :value="__('Teacher')" />
        <select id="professor_id" name="professor_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($professores as $p)<option value="{{ $p->id }}" @selected(old('professor_id', $atribuicao?->professor_id) == $p->id)>{{ $p->user->name }}</option>@endforeach
        </select>
    </div>
    <div>
        <x-input-label for="disciplina_id" :value="__('Subjects List')" />
        <select id="disciplina_id" name="disciplina_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($disciplinas as $d)<option value="{{ $d->id }}" @selected(old('disciplina_id', $atribuicao?->disciplina_id) == $d->id)>{{ $d->nome }}</option>@endforeach
        </select>
    </div>
    <div>
        <x-input-label for="turma_id" :value="__('Class Groups')" />
        <select id="turma_id" name="turma_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($turmas as $t)<option value="{{ $t->id }}" @selected(old('turma_id', $atribuicao?->turma_id) == $t->id)>{{ $t->classe->nome }} {{ $t->nome }} ({{ $t->anoLectivo->codigo }})</option>@endforeach
        </select>
    </div>
    <div>
        <x-input-label for="ano_lectivo_id" :value="__('School Year')" />
        <select id="ano_lectivo_id" name="ano_lectivo_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($anos as $a)<option value="{{ $a->id }}" @selected(old('ano_lectivo_id', $atribuicao?->ano_lectivo_id) == $a->id)>{{ $a->codigo }}</option>@endforeach
        </select>
    </div>
</div>
<div class="mt-6 flex gap-3"><button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button><a href="{{ route('atribuicoes.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a></div>
