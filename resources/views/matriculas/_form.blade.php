@php($matricula = $matricula ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="aluno_id" :value="__('Student')" />
        <select id="aluno_id" name="aluno_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($alunos as $a)<option value="{{ $a->id }}" @selected(old('aluno_id', $matricula?->aluno_id) == $a->id)>{{ $a->user->name }} ({{ $a->numero_processo }})</option>@endforeach
        </select>
    </div>
    <div>
        <x-input-label for="ano_lectivo_id" :value="__('School Year')" />
        <select id="ano_lectivo_id" name="ano_lectivo_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($anos as $a)<option value="{{ $a->id }}" @selected(old('ano_lectivo_id', $matricula?->ano_lectivo_id) == $a->id)>{{ $a->codigo }}</option>@endforeach
        </select>
    </div>
    <div>
        <x-input-label for="turma_id" :value="__('Class Groups')" />
        <select id="turma_id" name="turma_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($turmas as $t)<option value="{{ $t->id }}" @selected(old('turma_id', $matricula?->turma_id) == $t->id)>{{ $t->classe->nome }} {{ $t->nome }} — {{ $t->anoLectivo->codigo }}</option>@endforeach
        </select>
    </div>
    <div><x-input-label for="numero_matricula" :value="__('Enrollment Number')" /><x-text-input id="numero_matricula" name="numero_matricula" class="mt-1 block w-full" :value="old('numero_matricula', $matricula?->numero_matricula)" required /></div>
    <div><x-input-label for="data_matricula" :value="__('Enrollment Date')" /><x-text-input id="data_matricula" name="data_matricula" type="date" class="mt-1 block w-full" :value="old('data_matricula', $matricula?->data_matricula?->format('Y-m-d') ?? now()->toDateString())" required /></div>
    <div>
        <x-input-label for="estado" :value="__('Status')" />
        <select id="estado" name="estado" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach(['activa', 'transferido', 'desistente', 'aprovado', 'reprovado'] as $e)
                <option value="{{ $e }}" @selected(old('estado', $matricula?->estado ?? 'activa') === $e)>{{ __(str_replace('_', ' ', ucfirst($e))) }}</option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2"><x-input-label for="observacoes" value="Observações" /><textarea id="observacoes" name="observacoes" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('observacoes', $matricula?->observacoes) }}</textarea></div>
</div>
<div class="mt-6 flex gap-3"><button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button><a href="{{ route('matriculas.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a></div>
