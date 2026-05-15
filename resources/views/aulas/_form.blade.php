@php($aula = $aula ?? null)
@php($atribuicaoIdActual = old('atribuicao_id', $aula?->atribuicao_id ?? $atribuicaoId ?? null))
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <x-select name="atribuicao_id" :label="__('Class Groups') . ' / ' . __('Subjects List')" required>
            @foreach($atribuicoes as $a)
                <option value="{{ $a->id }}" @selected($atribuicaoIdActual == $a->id)>{{ $a->turma->classe->nome }} {{ $a->turma->nome }} — {{ $a->disciplina->nome }}</option>
            @endforeach
        </x-select>
    </div>
    <x-input name="data" :label="__('Date')" type="date" :value="$aula?->data?->format('Y-m-d') ?? now()->toDateString()" required />
    <x-input name="numero" label="{{ __('Lesson number (optional)') }}" type="number" :value="$aula?->numero" />
    <x-input name="hora_inicio" label="{{ __('Start time') }}" type="time" :value="$aula?->hora_inicio" />
    <x-input name="hora_fim" label="{{ __('End time') }}" type="time" :value="$aula?->hora_fim" />
    <div class="sm:col-span-2"><x-textarea name="sumario" label="{{ __('Lesson summary (what was taught)') }}" :value="$aula?->sumario" :rows="3" /></div>
    <div class="sm:col-span-2"><x-textarea name="conteudo_planeado" label="{{ __('Planned content (optional)') }}" :value="$aula?->conteudo_planeado" :rows="2" /></div>
</div>
<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('aulas.index')">{{ __('Cancel') }}</x-btn>
</div>
