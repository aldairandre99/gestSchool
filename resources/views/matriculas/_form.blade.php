@php($matricula = $matricula ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-combobox
        name="aluno_id"
        :label="__('Student')"
        required
        :placeholder="__('Search student by name or process number')"
        :selected="old('aluno_id', $matricula?->aluno_id)"
        :options="collect($alunos)->map(fn($a) => [
            'value' => $a->id,
            'label' => $a->user->name,
            'hint'  => $a->numero_processo,
        ])"
    />

    <x-select name="ano_lectivo_id" :label="__('School Year')" required>
        @foreach($anos as $a)
            <option value="{{ $a->id }}" @selected(old('ano_lectivo_id', $matricula?->ano_lectivo_id) == $a->id)>
                {{ $a->codigo }}
            </option>
        @endforeach
    </x-select>

    <x-combobox
        name="turma_id"
        :label="__('Class Groups')"
        required
        :placeholder="__('Choose class group')"
        :selected="old('turma_id', $matricula?->turma_id)"
        :options="collect($turmas)->map(fn($t) => [
            'value' => $t->id,
            'label' => $t->classe->nome . ' ' . $t->nome,
            'hint'  => $t->anoLectivo->codigo . ($t->curso ? ' · ' . $t->curso->sigla : ''),
        ])"
    />

    <x-input name="numero_matricula" :label="__('Enrollment Number')" :value="$matricula?->numero_matricula" required />
    <x-input name="data_matricula" :label="__('Enrollment Date')" type="date" :value="$matricula?->data_matricula?->format('Y-m-d') ?? now()->toDateString()" required />

    <x-select name="estado" :label="__('Status')" required :placeholder="null">
        @foreach(['activa', 'transferido', 'desistente', 'aprovado', 'reprovado'] as $e)
            <option value="{{ $e }}" @selected(old('estado', $matricula?->estado ?? 'activa') === $e)>
                {{ str_replace('_', ' ', ucfirst($e)) }}
            </option>
        @endforeach
    </x-select>

    <div class="sm:col-span-2">
        <x-textarea name="observacoes" label="{{ __('Observations') }}" :value="$matricula?->observacoes" :rows="2" />
    </div>
</div>

<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('matriculas.index')">{{ __('Cancel') }}</x-btn>
</div>
