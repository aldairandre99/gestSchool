<x-app-layout>
    <x-page-header :title="__('Gradebook')" :subtitle="__('Choose the gradebook type')" />

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- MODO 1: Por disciplina --}}
        <x-card :title="__('Gradebook by subject (detail)')">
            <p class="text-sm text-muted mb-4">{{ __('Detailed evaluations of one subject in one term — for the teacher.') }}</p>
            @if($atribuicoes->isEmpty())
                <x-empty title="{{ __('No assignments') }}" />
            @else
                <form x-data="{ atribuicao: '', trimestre: '' }"
                      x-on:submit.prevent="if (atribuicao && trimestre) window.location = '/pautas/disciplina/' + atribuicao + '/' + trimestre">
                    <div class="form-group">
                        <label class="form-label">{{ __('Class Groups') }} / {{ __('Subjects List') }}</label>
                        <select x-model="atribuicao" class="form-select">
                            <option value="">—</option>
                            @foreach($atribuicoes as $a)
                                <option value="{{ $a->id }}">{{ $a->turma->display_label }} — {{ $a->disciplina->nome }} ({{ $a->anoLectivo->codigo }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Term') }}</label>
                        <select x-model="trimestre" class="form-select">
                            <option value="">—</option>
                            @foreach($trimestres as $t)<option value="{{ $t->id }}">{{ $t->numero }}º — {{ $t->anoLectivo->codigo }}</option>@endforeach
                        </select>
                    </div>
                    <x-btn variant="primary" type="submit" icon="search">{{ __('Open gradebook') }}</x-btn>
                </form>
            @endif
        </x-card>

        {{-- MODO 2: Da turma por trimestre --}}
        <x-card :title="__('Class gradebook for a term')">
            <p class="text-sm text-muted mb-4">{{ __('All subjects × students of a class group for one term.') }}</p>
            <form x-data="{ turma: '', trimestre: '' }"
                  x-on:submit.prevent="if (turma && trimestre) window.location = '/pautas/turma/' + turma + '/trimestre/' + trimestre">
                <div class="form-group">
                    <label class="form-label">{{ __('Class Groups') }}</label>
                    <select x-model="turma" class="form-select">
                        <option value="">—</option>
                        @foreach($turmas as $t)
                            <option value="{{ $t->id }}">{{ $t->display_label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('Term') }}</label>
                    <select x-model="trimestre" class="form-select">
                        <option value="">—</option>
                        @foreach($trimestres as $t)<option value="{{ $t->id }}">{{ $t->numero }}º — {{ $t->anoLectivo->codigo }}</option>@endforeach
                    </select>
                </div>
                <x-btn variant="primary" type="submit" icon="table-2">{{ __('Open gradebook') }}</x-btn>
            </form>
        </x-card>

        {{-- MODO 3: Anual da turma --}}
        <x-card :title="__('Annual gradebook')">
            <p class="text-sm text-muted mb-4">{{ __('Annual averages, general average and final situation. Useful at year end.') }}</p>
            <form x-data="{ turma: '' }"
                  x-on:submit.prevent="if (turma) window.location = '/pautas/turma/' + turma + '/anual'">
                <div class="form-group">
                    <label class="form-label">{{ __('Class Groups') }}</label>
                    <select x-model="turma" class="form-select">
                        <option value="">—</option>
                        @foreach($turmas as $t)
                            <option value="{{ $t->id }}">{{ $t->display_label }}</option>
                        @endforeach
                    </select>
                </div>
                <x-btn variant="primary" type="submit" icon="calendar">{{ __('Open annual gradebook') }}</x-btn>
            </form>
        </x-card>

        {{-- MODO 4: Situação final --}}
        <x-card :title="__('Final results')">
            <p class="text-sm text-muted mb-4">{{ __('Compact list: passed, second-chance and failed. To post on the board.') }}</p>
            <form x-data="{ turma: '' }"
                  x-on:submit.prevent="if (turma) window.location = '/pautas/turma/' + turma + '/situacao'">
                <div class="form-group">
                    <label class="form-label">{{ __('Class Groups') }}</label>
                    <select x-model="turma" class="form-select">
                        <option value="">—</option>
                        @foreach($turmas as $t)
                            <option value="{{ $t->id }}">{{ $t->display_label }}</option>
                        @endforeach
                    </select>
                </div>
                <x-btn variant="primary" type="submit" icon="award">{{ __('Open results') }}</x-btn>
            </form>
        </x-card>
    </div>
</x-app-layout>
