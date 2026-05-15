@php($horario = $horario ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <x-select name="atribuicao_id" :label="__('Assignment')" required :placeholder="null">
            @foreach($atribuicoes as $a)
                <option value="{{ $a->id }}" @selected(old('atribuicao_id', $horario?->atribuicao_id) == $a->id)>
                    {{ $a->turma->display_label }} — {{ $a->disciplina->nome }} — {{ $a->professor->user->name }}
                </option>
            @endforeach
        </x-select>
    </div>
    <x-select name="dia_semana" :label="__('Weekday')" required :placeholder="null">
        @foreach($diasSemana as $n => $nome)
            <option value="{{ $n }}" @selected(old('dia_semana', $horario?->dia_semana) == $n)>{{ $nome }}</option>
        @endforeach
    </x-select>
    <x-select name="tempo" :label="__('Period')" required :placeholder="null">
        @foreach($tempos as $n => [$ini, $fim])
            <option value="{{ $n }}" @selected(old('tempo', $horario?->tempo) == $n)>{{ $n }}º ({{ $ini }} – {{ $fim }})</option>
        @endforeach
    </x-select>
    <x-input name="sala" :label="__('Room')" :value="$horario?->sala" />
    <x-input name="observacao" :label="__('Note')" :value="$horario?->observacao" />
</div>
<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('horarios.index')">{{ __('Cancel') }}</x-btn>
</div>
