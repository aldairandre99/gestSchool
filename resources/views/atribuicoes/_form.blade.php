@php($atribuicao = $atribuicao ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-select name="professor_id" :label="__('Teacher')" required>
        @foreach($professores as $p)<option value="{{ $p->id }}" @selected(old('professor_id', $atribuicao?->professor_id) == $p->id)>{{ $p->user->name }}</option>@endforeach
    </x-select>
    <x-select name="disciplina_id" :label="__('Subjects List')" required>
        @foreach($disciplinas as $d)<option value="{{ $d->id }}" @selected(old('disciplina_id', $atribuicao?->disciplina_id) == $d->id)>{{ $d->nome }}</option>@endforeach
    </x-select>
    <x-select name="turma_id" :label="__('Class Groups')" required>
        @foreach($turmas as $t)<option value="{{ $t->id }}" @selected(old('turma_id', $atribuicao?->turma_id) == $t->id)>{{ $t->classe->nome }} {{ $t->nome }} ({{ $t->anoLectivo->codigo }})</option>@endforeach
    </x-select>
    <x-select name="ano_lectivo_id" :label="__('School Year')" required>
        @foreach($anos as $a)<option value="{{ $a->id }}" @selected(old('ano_lectivo_id', $atribuicao?->ano_lectivo_id) == $a->id)>{{ $a->codigo }}</option>@endforeach
    </x-select>
</div>
<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('atribuicoes.index')">{{ __('Cancel') }}</x-btn>
</div>
