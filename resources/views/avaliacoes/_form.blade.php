@php($avaliacao = $avaliacao ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <x-select name="atribuicao_id" :label="__('Class Groups') . ' / ' . __('Subjects List')" required>
            @foreach($atribuicoes as $a)
                <option value="{{ $a->id }}" @selected(old('atribuicao_id', $avaliacao?->atribuicao_id) == $a->id)>{{ $a->turma->display_label }} — {{ $a->disciplina->nome }}</option>
            @endforeach
        </x-select>
    </div>
    <x-select name="trimestre_id" :label="__('Term')" required>
        @foreach($trimestres as $t)<option value="{{ $t->id }}" @selected(old('trimestre_id', $avaliacao?->trimestre_id) == $t->id)>{{ $t->anoLectivo->codigo }} · {{ $t->numero }}º</option>@endforeach
    </x-select>
    <x-select name="tipo" :label="__('Type')" required :placeholder="null">
        @foreach(['prova' => 'Prova', 'teste' => __('Test'), 'avaliacao_continua' => __('Continuous Assessment'), 'exame' => __('Exam')] as $k => $label)
            <option value="{{ $k }}" @selected(old('tipo', $avaliacao?->tipo ?? 'teste') === $k)>{{ $label }}</option>
        @endforeach
    </x-select>
    <div class="sm:col-span-2"><x-input name="titulo" :label="__('Title')" :value="$avaliacao?->titulo" required /></div>
    <x-input name="data" :label="__('Date')" type="date" :value="$avaliacao?->data?->format('Y-m-d')" />
    <x-input name="peso" :label="__('Weight')" type="number" step="0.01" :value="$avaliacao?->peso ?? 1" required />
    <x-input name="max_nota" :label="__('Max Score')" type="number" step="0.01" :value="$avaliacao?->max_nota ?? 20" required />
</div>
<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('avaliacoes.index')">{{ __('Cancel') }}</x-btn>
</div>
