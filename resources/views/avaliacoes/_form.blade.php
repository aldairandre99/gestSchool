@php($avaliacao = $avaliacao ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <x-input-label for="atribuicao_id" :value="__('Class Groups') . ' / ' . __('Subjects List')" />
        <select id="atribuicao_id" name="atribuicao_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($atribuicoes as $a)
                <option value="{{ $a->id }}" @selected(old('atribuicao_id', $avaliacao?->atribuicao_id) == $a->id)>{{ $a->turma->classe->nome }} {{ $a->turma->nome }} — {{ $a->disciplina->nome }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <x-input-label for="trimestre_id" :value="__('Term')" />
        <select id="trimestre_id" name="trimestre_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($trimestres as $t)
                <option value="{{ $t->id }}" @selected(old('trimestre_id', $avaliacao?->trimestre_id) == $t->id)>{{ $t->anoLectivo->codigo }} · {{ $t->numero }}º</option>
            @endforeach
        </select>
    </div>
    <div>
        <x-input-label for="tipo" :value="__('Type')" />
        <select id="tipo" name="tipo" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach([
                'prova' => 'Prova',
                'teste' => __('Test'),
                'avaliacao_continua' => __('Continuous Assessment'),
                'exame' => __('Exam'),
            ] as $k => $label)
                <option value="{{ $k }}" @selected(old('tipo', $avaliacao?->tipo ?? 'teste') === $k)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2"><x-input-label for="titulo" :value="__('Title')" /><x-text-input id="titulo" name="titulo" class="mt-1 block w-full" :value="old('titulo', $avaliacao?->titulo)" required /></div>
    <div><x-input-label for="data" :value="__('Date')" /><x-text-input id="data" name="data" type="date" class="mt-1 block w-full" :value="old('data', $avaliacao?->data?->format('Y-m-d'))" /></div>
    <div><x-input-label for="peso" :value="__('Weight')" /><x-text-input id="peso" name="peso" type="number" step="0.01" min="0.1" max="10" class="mt-1 block w-full" :value="old('peso', $avaliacao?->peso ?? 1)" required /></div>
    <div><x-input-label for="max_nota" :value="__('Max Score')" /><x-text-input id="max_nota" name="max_nota" type="number" step="0.01" min="1" max="20" class="mt-1 block w-full" :value="old('max_nota', $avaliacao?->max_nota ?? 20)" required /></div>
</div>
<div class="mt-6 flex gap-3"><button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button><a href="{{ route('avaliacoes.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a></div>
