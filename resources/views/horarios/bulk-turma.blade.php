<x-app-layout>
    <x-page-header :title="__('Schedule editor')">
        <x-slot name="subtitleSlot">
            <x-turma-label :turma="$turma" :showAno="true" />
        </x-slot>
        <x-slot name="actions">
            <x-btn variant="secondary" :href="route('horarios.turma', $turma)">{{ __('Cancel') }}</x-btn>
        </x-slot>
    </x-page-header>

    @if($atribuicoes->isEmpty())
        <x-card>
            <x-empty :title="__('No assignments for this class group')" icon="link" description="{{ __('Create assignments first (Atribuições).') }}">
                <x-btn variant="primary" :href="route('atribuicoes.index')">{{ __('Assignments') }}</x-btn>
            </x-empty>
        </x-card>
    @else
        <x-card>
            <p class="text-sm text-muted mb-4">
                {{ __('Pick the assignment for each slot. Empty cells stay free. Saving overwrites the previous schedule of this class group.') }}
            </p>

            <form method="POST" action="{{ route('horarios.bulk-turma.store', $turma) }}">
                @csrf

                <div class="overflow-x-auto">
                    <table class="table text-sm">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 100px">{{ __('Time') }}</th>
                                @foreach($diasLectivos as $diaNum)
                                    <th class="text-center">{{ $diasSemana[$diaNum] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tempos as $tempoNum => [$ini, $fim])
                                <tr>
                                    <td class="text-center align-middle">
                                        <div class="font-bold text-navy">{{ $tempoNum }}º</div>
                                        <div class="text-[10px] text-muted font-mono">{{ $ini }}–{{ $fim }}</div>
                                    </td>
                                    @foreach($diasLectivos as $diaNum)
                                        @php
                                            $key = $diaNum . '-' . $tempoNum;
                                            $actual = $horariosActuais->get($key);
                                            $selected = old("slots.{$diaNum}.{$tempoNum}.atribuicao_id", $actual?->atribuicao_id);
                                            $sala = old("slots.{$diaNum}.{$tempoNum}.sala", $actual?->sala);
                                        @endphp
                                        <td class="align-top p-1">
                                            <select name="slots[{{ $diaNum }}][{{ $tempoNum }}][atribuicao_id]" class="form-select text-xs">
                                                <option value="">—</option>
                                                @foreach($atribuicoes as $a)
                                                    <option value="{{ $a->id }}" @selected($selected == $a->id)>
                                                        {{ $a->disciplina->sigla ?: \Illuminate\Support\Str::limit($a->disciplina->nome, 8) }} · {{ \Illuminate\Support\Str::limit($a->professor->user->name, 14) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="text" name="slots[{{ $diaNum }}][{{ $tempoNum }}][sala]" value="{{ $sala }}" placeholder="{{ __('Room') }}" class="form-input mt-1 text-xs" maxlength="20">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @error('slots')<p class="form-error mt-3">{{ $message }}</p>@enderror

                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-100">
                    <x-btn variant="primary" type="submit" icon="save">{{ __('Save schedule') }}</x-btn>
                    <x-btn variant="secondary" :href="route('horarios.turma', $turma)">{{ __('Cancel') }}</x-btn>
                    <span class="text-xs text-muted ms-auto">
                        {{ __('Tip:') }} {{ __('leave a slot empty to free that period.') }}
                    </span>
                </div>
            </form>
        </x-card>

        <x-card :title="__('Legend')">
            <p class="text-sm text-muted mb-3">{{ __('Available assignments for this class group:') }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                @foreach($atribuicoes as $a)
                    <div class="flex items-center gap-2">
                        <x-badge variant="info">{{ $a->disciplina->sigla ?: '—' }}</x-badge>
                        <span class="text-navy">{{ $a->disciplina->nome }}</span>
                        <span class="text-muted text-xs">· {{ $a->professor->user->name }}</span>
                    </div>
                @endforeach
            </div>
        </x-card>
    @endif
</x-app-layout>
