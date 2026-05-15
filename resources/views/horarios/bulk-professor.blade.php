@php
    use App\Support\TurmaColor;
    // Mapa de cores por turma + payload por atribuição (para reactividade Alpine)
    $turmasUnicas = $atribuicoes->pluck('turma')->unique('id')->values();
    $turmaColors = [];
    foreach ($turmasUnicas as $t) {
        $turmaColors[$t->id] = TurmaColor::for($t->id);
    }
    $atrPayload = [];
    foreach ($atribuicoes as $a) {
        $atrPayload[$a->id] = [
            'turma_id' => $a->turma_id,
            'turma_label' => $a->turma->classe->nome . $a->turma->nome,
            'disciplina' => $a->disciplina->sigla ?: \Illuminate\Support\Str::limit($a->disciplina->nome, 8),
            'disciplina_full' => $a->disciplina->nome,
        ];
    }
@endphp
<x-app-layout>
    <x-page-header :title="__('Teacher schedule editor')" :subtitle="$professor->user->name">
        <x-slot name="actions">
            <x-btn variant="secondary" :href="route('horarios.professor', $professor)">{{ __('Cancel') }}</x-btn>
        </x-slot>
    </x-page-header>

    @if($atribuicoes->isEmpty())
        <x-card>
            <x-empty :title="__('No assignments for this teacher')" icon="link" description="{{ __('Create assignments first (Atribuições).') }}">
                <x-btn variant="primary" :href="route('atribuicoes.index')">{{ __('Assignments') }}</x-btn>
            </x-empty>
        </x-card>
    @else
        <x-card x-data="bulkProfessorEditor(
            {{ \Illuminate\Support\Js::from($initialSlots) }},
            {{ \Illuminate\Support\Js::from($diasLectivos) }},
            {{ \Illuminate\Support\Js::from($atrPayload) }},
            {{ \Illuminate\Support\Js::from($turmaColors) }}
        )">
            <p class="text-sm text-muted mb-4">
                {{ __('Pick an assignment for each slot. Cell color reflects the class group. Saving overwrites this teacher\'s entire schedule.') }}
                @if($anoActivo)<span class="ms-2 text-xs">· {{ __('Active year') }}: <strong>{{ $anoActivo->codigo }}</strong></span>@endif
            </p>

            {{-- Toolbar global --}}
            <div class="flex flex-wrap items-center gap-2 mb-4 pb-4 border-b border-gray-100">
                <span class="text-xs text-muted me-2">{{ __('Clipboard:') }}</span>
                <span x-show="!clipboard" class="text-xs text-muted italic">{{ __('empty') }}</span>
                <span x-show="clipboard" class="text-xs">
                    <x-badge variant="info"><span x-text="clipboardLabel"></span></x-badge>
                    <button type="button" class="btn-link btn-link-muted ms-1" @click="clearClipboard()">{{ __('clear') }}</button>
                </span>
                <div class="ms-auto flex flex-wrap gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" @click="confirmClearAll()">
                        <x-lucide-eraser class="w-4 h-4" /> {{ __('Clear all') }}
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('horarios.bulk-professor.store', $professor) }}">
                @csrf

                <div class="overflow-x-auto">
                    <table class="table text-sm">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 100px">{{ __('Time') }}</th>
                                @foreach($diasLectivos as $diaNum)
                                    <th class="text-center">
                                        <div class="inline-flex items-center gap-1" x-data="{ open: false }" @click.outside="open = false">
                                            <span>{{ $diasSemana[$diaNum] }}</span>
                                            <button type="button" class="btn-link btn-link-muted text-xs" @click.prevent="open = !open" title="{{ __('Column actions') }}">
                                                <x-lucide-more-vertical class="w-4 h-4" />
                                            </button>
                                            <div x-show="open" x-cloak class="dropdown end-0 mt-6 text-start">
                                                <button type="button" class="dropdown-item w-full text-start" @click="copyColumn({{ $diaNum }}); open = false">{{ __('Copy column') }}</button>
                                                <button type="button" class="dropdown-item w-full text-start"
                                                        x-bind:disabled="clipboardType !== 'col'"
                                                        x-bind:class="clipboardType !== 'col' ? 'opacity-50 cursor-not-allowed' : ''"
                                                        @click="pasteColumn({{ $diaNum }}); open = false">{{ __('Paste column') }}</button>
                                                <button type="button" class="dropdown-item w-full text-start" @click="applyToAllDays({{ $diaNum }}); open = false">{{ __('Apply to all weekdays') }}</button>
                                                <div class="dropdown-divider"></div>
                                                <button type="button" class="dropdown-item w-full text-start text-danger" @click="clearColumn({{ $diaNum }}); open = false">{{ __('Clear column') }}</button>
                                            </div>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tempos as $tempoNum => [$ini, $fim])
                                <tr>
                                    <td class="text-center align-middle">
                                        <div class="inline-flex items-center gap-1" x-data="{ open: false }" @click.outside="open = false">
                                            <div class="text-center">
                                                <div class="font-bold text-navy">{{ $tempoNum }}º</div>
                                                <div class="text-[10px] text-muted font-mono">{{ $ini }}–{{ $fim }}</div>
                                            </div>
                                            <button type="button" class="btn-link btn-link-muted text-xs" @click.prevent="open = !open">
                                                <x-lucide-more-vertical class="w-4 h-4" />
                                            </button>
                                            <div x-show="open" x-cloak class="dropdown start-0 ms-8 text-start">
                                                <button type="button" class="dropdown-item w-full text-start" @click="copyRow({{ $tempoNum }}); open = false">{{ __('Copy row') }}</button>
                                                <button type="button" class="dropdown-item w-full text-start"
                                                        x-bind:disabled="clipboardType !== 'row'"
                                                        x-bind:class="clipboardType !== 'row' ? 'opacity-50 cursor-not-allowed' : ''"
                                                        @click="pasteRow({{ $tempoNum }}); open = false">{{ __('Paste row') }}</button>
                                                <div class="dropdown-divider"></div>
                                                <button type="button" class="dropdown-item w-full text-start text-danger" @click="clearRow({{ $tempoNum }}); open = false">{{ __('Clear row') }}</button>
                                            </div>
                                        </div>
                                    </td>
                                    @foreach($diasLectivos as $diaNum)
                                        <td class="align-top p-1"
                                            x-bind:style="cellStyle(slots[{{ $diaNum }}][{{ $tempoNum }}].atribuicao_id)">
                                            <select name="slots[{{ $diaNum }}][{{ $tempoNum }}][atribuicao_id]"
                                                    x-model="slots[{{ $diaNum }}][{{ $tempoNum }}].atribuicao_id"
                                                    class="form-select text-xs">
                                                <option value="">—</option>
                                                @foreach($atribuicoes as $a)
                                                    <option value="{{ $a->id }}">
                                                        [{{ $a->turma->classe->nome }}{{ $a->turma->nome }}] {{ $a->disciplina->sigla ?: \Illuminate\Support\Str::limit($a->disciplina->nome, 8) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="text"
                                                   name="slots[{{ $diaNum }}][{{ $tempoNum }}][sala]"
                                                   x-model="slots[{{ $diaNum }}][{{ $tempoNum }}].sala"
                                                   placeholder="{{ __('Room') }}"
                                                   class="form-input mt-1 text-xs" maxlength="20">
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
                    <x-btn variant="secondary" :href="route('horarios.professor', $professor)">{{ __('Cancel') }}</x-btn>
                    <span class="text-xs text-muted ms-auto">
                        <span x-text="filledCount"></span> / <span x-text="totalCount"></span> {{ __('slots filled') }}
                    </span>
                </div>
            </form>
        </x-card>

        {{-- Stats card (reactivo) --}}
        <x-card x-data="{}" :title="__('Workload summary')">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                <div>
                    <div class="text-xs text-muted uppercase tracking-wide mb-1">{{ __('Periods per week') }}</div>
                    <div class="text-3xl font-bold text-navy" x-text="$root.filledCount"></div>
                </div>
                <div>
                    <div class="text-xs text-muted uppercase tracking-wide mb-2">{{ __('By subject') }}</div>
                    <template x-for="(n, key) in $root.breakdownDisciplina" :key="'d-'+key">
                        <div class="flex justify-between items-center py-1 border-b border-gray-50 last:border-0">
                            <span class="text-navy" x-text="key"></span>
                            <x-badge variant="muted"><span x-text="n + ' ' + '{{ __('periods') }}'"></span></x-badge>
                        </div>
                    </template>
                    <p x-show="Object.keys($root.breakdownDisciplina).length === 0" class="text-muted italic text-xs">{{ __('No periods yet.') }}</p>
                </div>
                <div>
                    <div class="text-xs text-muted uppercase tracking-wide mb-2">{{ __('By class group') }}</div>
                    <template x-for="(info, tid) in $root.breakdownTurma" :key="'t-'+tid">
                        <div class="flex justify-between items-center py-1 border-b border-gray-50 last:border-0">
                            <span class="inline-flex items-center gap-2">
                                <span class="inline-block w-3 h-3 rounded-sm" x-bind:style="`background:${info.color}; border:1px solid ${info.border}`"></span>
                                <span class="text-navy" x-text="info.label"></span>
                            </span>
                            <x-badge variant="muted"><span x-text="info.count + ' ' + '{{ __('periods') }}'"></span></x-badge>
                        </div>
                    </template>
                    <p x-show="Object.keys($root.breakdownTurma).length === 0" class="text-muted italic text-xs">{{ __('No periods yet.') }}</p>
                </div>
            </div>
        </x-card>

        <x-card :title="__('Legend')">
            <p class="text-sm text-muted mb-3">{{ __('Available assignments for this teacher:') }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                @foreach($atribuicoes as $a)
                    @php($c = $turmaColors[$a->turma_id])
                    <div class="flex items-center gap-2 p-1 rounded" style="background: {{ $c['bg'] }}; border-left: 3px solid {{ $c['border'] }}">
                        <x-badge variant="info">{{ $a->turma->classe->nome }}{{ $a->turma->nome }}</x-badge>
                        <span class="text-navy">{{ $a->disciplina->nome }}</span>
                    </div>
                @endforeach
            </div>
        </x-card>
    @endif

    <script>
        function bulkProfessorEditor(initial, diasLectivos, atrPayload, turmaColors) {
            const CLIPBOARD_KEY = 'gestschool_horario_clipboard';
            const CLIPBOARD_TTL_MS = 24 * 60 * 60 * 1000;

            return {
                slots: initial,
                diasLectivos: diasLectivos,
                atrPayload: atrPayload,
                turmaColors: turmaColors,
                clipboard: null,
                clipboardType: null,
                clipboardOriginLabel: '',

                init() {
                    const stored = localStorage.getItem(CLIPBOARD_KEY);
                    if (! stored) return;
                    try {
                        const data = JSON.parse(stored);
                        if (Date.now() - data.timestamp < CLIPBOARD_TTL_MS) {
                            this.clipboard = data.value;
                            this.clipboardType = data.type;
                            this.clipboardOriginLabel = data.origin;
                        } else {
                            localStorage.removeItem(CLIPBOARD_KEY);
                        }
                    } catch (e) {
                        localStorage.removeItem(CLIPBOARD_KEY);
                    }
                },

                cellStyle(atrId) {
                    if (!atrId) return '';
                    const info = this.atrPayload[atrId];
                    if (!info) return '';
                    const c = this.turmaColors[info.turma_id];
                    if (!c) return '';
                    return `background:${c.bg}; border-left: 3px solid ${c.border}`;
                },

                get filledCount() {
                    let n = 0;
                    for (const dia of this.diasLectivos) {
                        for (const tempo in this.slots[dia]) {
                            if (this.slots[dia][tempo].atribuicao_id) n++;
                        }
                    }
                    return n;
                },

                get totalCount() {
                    if (! this.diasLectivos.length) return 0;
                    const d = this.diasLectivos[0];
                    return this.diasLectivos.length * Object.keys(this.slots[d] || {}).length;
                },

                get breakdownDisciplina() {
                    const out = {};
                    for (const dia of this.diasLectivos) {
                        for (const tempo in this.slots[dia]) {
                            const id = this.slots[dia][tempo].atribuicao_id;
                            if (!id) continue;
                            const info = this.atrPayload[id];
                            if (!info) continue;
                            out[info.disciplina_full] = (out[info.disciplina_full] || 0) + 1;
                        }
                    }
                    return out;
                },

                get breakdownTurma() {
                    const out = {};
                    for (const dia of this.diasLectivos) {
                        for (const tempo in this.slots[dia]) {
                            const id = this.slots[dia][tempo].atribuicao_id;
                            if (!id) continue;
                            const info = this.atrPayload[id];
                            if (!info) continue;
                            const c = this.turmaColors[info.turma_id] || {bg:'#eee', border:'#ccc'};
                            if (!out[info.turma_id]) {
                                out[info.turma_id] = { label: info.turma_label, color: c.bg, border: c.border, count: 0 };
                            }
                            out[info.turma_id].count++;
                        }
                    }
                    return out;
                },

                get clipboardLabel() {
                    if (!this.clipboard) return '';
                    return (this.clipboardType === 'col' ? '{{ __('column') }} ' : '{{ __('row') }} ') + this.clipboardOriginLabel;
                },

                persistClipboard(origin) {
                    this.clipboardOriginLabel = origin;
                    localStorage.setItem(CLIPBOARD_KEY, JSON.stringify({
                        value: this.clipboard,
                        type: this.clipboardType,
                        origin: origin,
                        timestamp: Date.now(),
                    }));
                },

                clearClipboard() {
                    this.clipboard = null;
                    this.clipboardType = null;
                    this.clipboardOriginLabel = '';
                    localStorage.removeItem(CLIPBOARD_KEY);
                },

                copyColumn(dia) {
                    this.clipboard = JSON.parse(JSON.stringify(this.slots[dia] || {}));
                    this.clipboardType = 'col';
                    this.persistClipboard(dia);
                },

                pasteColumn(dia) {
                    if (this.clipboardType !== 'col' || !this.clipboard) return;
                    this.slots[dia] = JSON.parse(JSON.stringify(this.clipboard));
                },

                clearColumn(dia) {
                    for (const tempo in this.slots[dia]) {
                        this.slots[dia][tempo].atribuicao_id = '';
                        this.slots[dia][tempo].sala = '';
                    }
                },

                applyToAllDays(dia) {
                    const source = JSON.parse(JSON.stringify(this.slots[dia] || {}));
                    for (const d of this.diasLectivos) {
                        if (d !== dia) {
                            this.slots[d] = JSON.parse(JSON.stringify(source));
                        }
                    }
                },

                copyRow(tempo) {
                    const row = {};
                    for (const d of this.diasLectivos) {
                        row[d] = JSON.parse(JSON.stringify(this.slots[d]?.[tempo] || {}));
                    }
                    this.clipboard = row;
                    this.clipboardType = 'row';
                    this.persistClipboard(tempo + 'º');
                },

                pasteRow(tempo) {
                    if (this.clipboardType !== 'row' || !this.clipboard) return;
                    for (const d of this.diasLectivos) {
                        if (this.clipboard[d]) {
                            this.slots[d][tempo] = JSON.parse(JSON.stringify(this.clipboard[d]));
                        }
                    }
                },

                clearRow(tempo) {
                    for (const d of this.diasLectivos) {
                        if (this.slots[d]?.[tempo]) {
                            this.slots[d][tempo].atribuicao_id = '';
                            this.slots[d][tempo].sala = '';
                        }
                    }
                },

                confirmClearAll() {
                    if (! confirm('{{ __('Clear the whole schedule?') }}')) return;
                    for (const d of this.diasLectivos) {
                        for (const tempo in this.slots[d]) {
                            this.slots[d][tempo].atribuicao_id = '';
                            this.slots[d][tempo].sala = '';
                        }
                    }
                },
            };
        }
    </script>
</x-app-layout>
