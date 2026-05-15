@php($matricula = $matricula ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <x-select name="aluno_id" :label="__('Student')" required>
        @foreach($alunos as $a)<option value="{{ $a->id }}" @selected(old('aluno_id', $matricula?->aluno_id) == $a->id)>{{ $a->user->name }} ({{ $a->numero_processo }})</option>@endforeach
    </x-select>
    <x-select name="ano_lectivo_id" :label="__('School Year')" required>
        @foreach($anos as $a)<option value="{{ $a->id }}" @selected(old('ano_lectivo_id', $matricula?->ano_lectivo_id) == $a->id)>{{ $a->codigo }}</option>@endforeach
    </x-select>
    <x-select name="turma_id" :label="__('Class Groups')" required>
        @foreach($turmas as $t)<option value="{{ $t->id }}" @selected(old('turma_id', $matricula?->turma_id) == $t->id)>{{ $t->classe->nome }} {{ $t->nome }} — {{ $t->anoLectivo->codigo }}</option>@endforeach
    </x-select>
    <x-input name="numero_matricula" :label="__('Enrollment Number')" :value="$matricula?->numero_matricula" required />
    <x-input name="data_matricula" :label="__('Enrollment Date')" type="date" :value="$matricula?->data_matricula?->format('Y-m-d') ?? now()->toDateString()" required />
    <x-select name="estado" :label="__('Status')" required :placeholder="null">
        @foreach(['activa', 'transferido', 'desistente', 'aprovado', 'reprovado'] as $e)
            <option value="{{ $e }}" @selected(old('estado', $matricula?->estado ?? 'activa') === $e)>{{ str_replace('_', ' ', ucfirst($e)) }}</option>
        @endforeach
    </x-select>
    <div class="sm:col-span-2"><x-textarea name="observacoes" label="{{ __('Observations') }}" :value="$matricula?->observacoes" :rows="2" /></div>
</div>
<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('matriculas.index')">{{ __('Cancel') }}</x-btn>
</div>
