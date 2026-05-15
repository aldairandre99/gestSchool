@php($atribuicao = $atribuicao ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-combobox
        name="professor_id"
        :label="__('Teacher')"
        required
        :placeholder="__('Search teacher')"
        :selected="old('professor_id', $atribuicao?->professor_id)"
        :options="collect($professores)->map(fn($p) => [
            'value' => $p->id,
            'label' => $p->user->name,
            'hint'  => $p->especialidade,
        ])"
    />

    <x-combobox
        name="disciplina_id"
        :label="__('Subjects List')"
        required
        :placeholder="__('Choose subject')"
        :selected="old('disciplina_id', $atribuicao?->disciplina_id)"
        :options="collect($disciplinas)->map(fn($d) => [
            'value' => $d->id,
            'label' => $d->nome,
            'hint'  => $d->sigla,
        ])"
    />

    <x-combobox
        name="turma_id"
        :label="__('Class Groups')"
        required
        :placeholder="__('Choose class group')"
        :selected="old('turma_id', $atribuicao?->turma_id)"
        :options="collect($turmas)->map(fn($t) => [
            'value' => $t->id,
            'label' => $t->classe->nome . ' ' . $t->nome,
            'hint'  => $t->anoLectivo->codigo . ($t->curso ? ' · ' . $t->curso->sigla : ''),
        ])"
    />

    <x-select name="ano_lectivo_id" :label="__('School Year')" required>
        @foreach($anos as $a)
            <option value="{{ $a->id }}" @selected(old('ano_lectivo_id', $atribuicao?->ano_lectivo_id) == $a->id)>
                {{ $a->codigo }}
            </option>
        @endforeach
    </x-select>
</div>

<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('atribuicoes.index')">{{ __('Cancel') }}</x-btn>
</div>
