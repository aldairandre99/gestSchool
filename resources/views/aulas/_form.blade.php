@php($aula = $aula ?? null)
@php($atribuicaoIdActual = old('atribuicao_id', $aula?->atribuicao_id ?? $atribuicaoId ?? null))
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <x-input-label for="atribuicao_id" :value="__('Class Groups') . ' / ' . __('Subjects List')" />
        <select id="atribuicao_id" name="atribuicao_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($atribuicoes as $a)
                <option value="{{ $a->id }}" @selected($atribuicaoIdActual == $a->id)>{{ $a->turma->classe->nome }} {{ $a->turma->nome }} — {{ $a->disciplina->nome }}</option>
            @endforeach
        </select>
    </div>
    <div><x-input-label for="data" :value="__('Date')" /><x-text-input id="data" name="data" type="date" class="mt-1 block w-full" :value="old('data', $aula?->data?->format('Y-m-d') ?? now()->toDateString())" required /></div>
    <div><x-input-label for="numero" value="Nº da aula (opcional)" /><x-text-input id="numero" name="numero" type="number" min="1" max="300" class="mt-1 block w-full" :value="old('numero', $aula?->numero)" /></div>
    <div><x-input-label for="hora_inicio" value="Hora início" /><x-text-input id="hora_inicio" name="hora_inicio" type="time" class="mt-1 block w-full" :value="old('hora_inicio', $aula?->hora_inicio)" /></div>
    <div><x-input-label for="hora_fim" value="Hora fim" /><x-text-input id="hora_fim" name="hora_fim" type="time" class="mt-1 block w-full" :value="old('hora_fim', $aula?->hora_fim)" /></div>
    <div class="sm:col-span-2"><x-input-label for="sumario" value="Sumário (o que foi leccionado)" /><textarea id="sumario" name="sumario" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('sumario', $aula?->sumario) }}</textarea></div>
    <div class="sm:col-span-2"><x-input-label for="conteudo_planeado" value="Conteúdo planeado (opcional)" /><textarea id="conteudo_planeado" name="conteudo_planeado" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('conteudo_planeado', $aula?->conteudo_planeado) }}</textarea></div>
</div>
<div class="mt-6 flex gap-3"><button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button><a href="{{ route('aulas.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a></div>
