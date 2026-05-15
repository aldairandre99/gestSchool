<x-app-layout>
    @php
        $resumoActivo = $matriculaActiva ? ($resumos[$matriculaActiva->id] ?? null) : null;
        $anosNaEscola = $aluno->matriculas->pluck('ano_lectivo_id')->unique()->count();
    @endphp

    <x-page-header :title="$aluno->user->name" :subtitle="__('Process') . ': ' . $aluno->numero_processo">
        <x-slot name="actions">
            @if($matriculaActiva)
                <x-btn variant="primary" icon="file-text" :href="route('boletim.show', $matriculaActiva)">{{ __('Report Card') }}</x-btn>
                <x-btn variant="dark" icon="printer" :href="route('boletim.pdf', $matriculaActiva)">PDF</x-btn>
            @endif
            @hasanyrole('director_geral|director_pedagogico|secretario')
                <x-btn variant="secondary" icon="pencil" :href="route('alunos.edit', $aluno)">{{ __('Edit') }}</x-btn>
            @endhasanyrole
            <x-btn variant="secondary" icon="arrow-left" :href="route('alunos.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    {{-- ========== KPIs ========== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-stat-card
            :label="__('Current Class')"
            :value="$matriculaActiva ? ($matriculaActiva->turma->classe->nome . ' ' . $matriculaActiva->turma->nome) : '—'"
            icon="layers"
            variant="primary"
            :trend="$matriculaActiva?->turma?->curso?->sigla ?? __('Common Core')" />

        <x-stat-card
            :label="__('Current Average')"
            :value="$resumoActivo && $resumoActivo['media_anual'] !== null ? number_format($resumoActivo['media_anual'], 1) : '—'"
            icon="gauge"
            :variant="($resumoActivo['media_anual'] ?? 0) >= 10 ? 'success' : 'danger'"
            :trend="__('Out of 20')" />

        <x-stat-card
            :label="__('Attendance')"
            :value="($resumoActivo['presencas_pct'] ?? null) !== null ? $resumoActivo['presencas_pct'] . '%' : '—'"
            icon="clipboard-check"
            :variant="($resumoActivo['presencas_pct'] ?? 100) >= 85 ? 'success' : 'warning'"
            :trend="$resumoActivo ? ($resumoActivo['faltas'] . ' ' . __('absences')) : null" />

        <x-stat-card
            :label="__('Years at school')"
            :value="$anosNaEscola"
            icon="calendar"
            variant="info"
            :trend="$matriculaActiva?->anoLectivo?->codigo" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- ========== Coluna 1-2 ========== --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Percurso académico --}}
            <x-card :title="__('Academic Path')">
                <x-slot name="actions">
                    <span class="text-xs text-muted">{{ $aluno->matriculas->count() }} {{ __('enrollments') }}</span>
                </x-slot>

                @if($aluno->matriculas->isEmpty())
                    <x-empty icon="inbox" :title="__('No enrollments yet')" :description="__('No enrollment records found for this student.')" />
                @else
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('School Year') }}</th>
                                    <th>{{ __('Class Groups') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-right">{{ __('Average') }}</th>
                                    <th class="text-right">{{ __('Attendance') }}</th>
                                    <th class="text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($aluno->matriculas as $m)
                                    @php
                                        $r = $resumos[$m->id] ?? ['media_anual' => null, 'presencas_pct' => null, 'faltas' => 0];
                                        $estVar = match ($m->estado) {
                                            'activa' => 'success',
                                            'aprovado' => 'success',
                                            'reprovado' => 'danger',
                                            'transferido' => 'info',
                                            'desistente' => 'muted',
                                            default => 'muted',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="font-semibold text-navy">{{ $m->anoLectivo->codigo }}</td>
                                        <td>
                                            <div class="text-navy">{{ $m->turma->classe->nome }} {{ $m->turma->nome }}</div>
                                            <div class="text-xs text-muted">{{ $m->turma->curso?->nome ?? __('Common Core') }}</div>
                                        </td>
                                        <td>
                                            <x-badge :variant="$estVar">{{ __($m->estado) }}</x-badge>
                                        </td>
                                        <td class="text-right">
                                            @if($r['media_anual'] !== null)
                                                <span @class([
                                                    'font-semibold tabular-nums',
                                                    'text-danger' => $r['media_anual'] < 10,
                                                    'text-success' => $r['media_anual'] >= 14,
                                                    'text-navy' => $r['media_anual'] >= 10 && $r['media_anual'] < 14,
                                                ])>{{ number_format($r['media_anual'], 1) }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($r['presencas_pct'] !== null)
                                                <span @class([
                                                    'tabular-nums',
                                                    'text-warning' => $r['presencas_pct'] < 85,
                                                    'text-navy' => $r['presencas_pct'] >= 85,
                                                ])>{{ $r['presencas_pct'] }}%</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="table-actions">
                                            <x-btn-link :href="route('boletim.show', $m)">{{ __('View') }}</x-btn-link>
                                            <x-btn-link :href="route('boletim.pdf', $m)">PDF</x-btn-link>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-card>

            {{-- Médias por disciplina (matrícula activa) --}}
            @if($boletim && !empty($boletim['medias']))
                <x-card :title="__('Grades by subject') . ' · ' . $matriculaActiva->anoLectivo->codigo">
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Subject') }}</th>
                                    @foreach($boletim['trimestres'] as $t)
                                        <th class="text-right">T{{ $t->numero }}</th>
                                    @endforeach
                                    <th class="text-right">{{ __('Annual') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($boletim['medias'] as $discId => $info)
                                    <tr>
                                        <td class="font-semibold text-navy">{{ $info['nome'] }}</td>
                                        @foreach($boletim['trimestres'] as $t)
                                            @php $m = $info['trimestres'][$t->id] ?? null; @endphp
                                            <td class="text-right tabular-nums">
                                                @if($m !== null)
                                                    <span @class([
                                                        'text-danger' => $m < 10,
                                                        'text-success' => $m >= 14,
                                                        'text-navy' => $m >= 10 && $m < 14,
                                                    ])>{{ number_format($m, 2) }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="text-right tabular-nums font-semibold">
                                            @if($info['anual'] !== null)
                                                <span @class([
                                                    'text-danger' => $info['anual'] < 10,
                                                    'text-success' => $info['anual'] >= 14,
                                                    'text-navy' => $info['anual'] >= 10 && $info['anual'] < 14,
                                                ])>{{ number_format($info['anual'], 2) }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-card>
            @endif

            {{-- Dados pessoais --}}
            <x-card :title="__('Personal Data')">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <div>
                        <dt class="form-label">{{ __('BI Number') }}</dt>
                        <dd class="text-navy">{{ $aluno->bi ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="form-label">{{ __('Birth Date') }}</dt>
                        <dd class="text-navy">{{ $aluno->data_nascimento?->format('d/m/Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="form-label">{{ __('Gender') }}</dt>
                        <dd class="text-navy">{{ $aluno->sexo === 'M' ? __('Male') : ($aluno->sexo === 'F' ? __('Female') : '—') }}</dd>
                    </div>
                    <div>
                        <dt class="form-label">{{ __('Nationality') }}</dt>
                        <dd class="text-navy">{{ $aluno->nacionalidade }}</dd>
                    </div>
                    <div>
                        <dt class="form-label">{{ __('Place of Birth') }}</dt>
                        <dd class="text-navy">{{ $aluno->naturalidade ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="form-label">{{ __('Contact') }}</dt>
                        <dd class="text-navy">{{ $aluno->user->phone ?? $aluno->user->email ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="form-label">{{ __('Address') }}</dt>
                        <dd class="text-navy">{{ $aluno->morada ?? '—' }}</dd>
                    </div>
                    @if($aluno->observacoes)
                        <div class="sm:col-span-2">
                            <dt class="form-label">{{ __('Notes') }}</dt>
                            <dd class="text-navy whitespace-pre-line">{{ $aluno->observacoes }}</dd>
                        </div>
                    @endif
                </dl>
            </x-card>
        </div>

        {{-- ========== Coluna 3 ========== --}}
        <div class="space-y-6">
            {{-- Encarregados --}}
            <x-card :title="__('Guardians of this student')">
                @if($aluno->encarregados->isEmpty())
                    <x-empty icon="users" :title="__('No guardians')" />
                @else
                    <ul class="divide-y divide-gray-100 -my-2">
                        @foreach($aluno->encarregados as $e)
                            <li class="py-3 flex items-start gap-3">
                                <div class="w-9 h-9 rounded-full bg-primary-soft text-primary-600 font-bold flex items-center justify-center text-sm shrink-0">
                                    {{ collect(explode(' ', $e->user->name))->take(2)->map(fn($w) => mb_substr($w, 0, 1))->join('') }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-navy font-semibold truncate">{{ $e->user->name }}</div>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <x-badge variant="muted">{{ __($e->pivot->parentesco) }}</x-badge>
                                        @if($e->pivot->principal)
                                            <x-badge variant="primary">{{ __('Primary') }}</x-badge>
                                        @endif
                                    </div>
                                    <div class="text-xs text-muted mt-0.5 truncate">{{ $e->user->phone ?? $e->user->email }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-card>

            {{-- Atalhos da turma activa (apenas direcção/professor) --}}
            @if($matriculaActiva)
                @hasanyrole('director_geral|director_pedagogico|secretario|professor|professor_assistente')
                    <x-card :title="__('Class shortcuts')" compact>
                        @php $turma = $matriculaActiva->turma; @endphp
                        <p class="text-sm text-muted mb-1">{{ $turma->classe->nome }} {{ $turma->nome }}</p>
                        @if($turma->directorTurma)
                            <p class="text-xs text-muted mb-3">{{ __('Class Director') }}: {{ $turma->directorTurma->user->name }}</p>
                        @endif
                        <div class="space-y-2">
                            <a href="{{ route('pautas.turma-anual', $turma) }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-input border border-gray-200 hover:border-primary hover:bg-primary-soft/40 transition group">
                                <x-lucide-table class="w-4 h-4 text-muted group-hover:text-primary" />
                                <span class="text-sm text-navy">{{ __('Annual class grade sheet') }}</span>
                            </a>
                            <a href="{{ route('pautas.situacao', $turma) }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-input border border-gray-200 hover:border-primary hover:bg-primary-soft/40 transition group">
                                <x-lucide-chart-bar class="w-4 h-4 text-muted group-hover:text-primary" />
                                <span class="text-sm text-navy">{{ __('Class situation') }}</span>
                            </a>
                            <a href="{{ route('horarios.turma', $turma) }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-input border border-gray-200 hover:border-primary hover:bg-primary-soft/40 transition group">
                                <x-lucide-calendar-clock class="w-4 h-4 text-muted group-hover:text-primary" />
                                <span class="text-sm text-navy">{{ __('Class timetable') }}</span>
                            </a>
                        </div>
                    </x-card>
                @endhasanyrole
            @endif

            {{-- PDFs históricos --}}
            @if($aluno->matriculas->isNotEmpty())
                <x-card :title="__('Quick print')" compact>
                    <p class="text-sm text-muted mb-4">{{ __('Download report cards directly') }}.</p>
                    <div class="space-y-2">
                        @foreach($aluno->matriculas as $m)
                            <a href="{{ route('boletim.pdf', $m) }}"
                               class="flex items-center gap-3 px-3 py-2 rounded-input border border-gray-200 hover:border-primary hover:bg-primary-soft/40 transition group">
                                <x-lucide-file-down class="w-4 h-4 text-muted group-hover:text-primary" />
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-semibold text-navy truncate">{{ __('Report Card') }} {{ $m->anoLectivo->codigo }}</div>
                                    <div class="text-xs text-muted truncate">{{ $m->turma->classe->nome }} {{ $m->turma->nome }} · {{ __($m->estado) }}</div>
                                </div>
                                <x-lucide-arrow-down class="w-4 h-4 text-muted group-hover:text-primary" />
                            </a>
                        @endforeach
                    </div>
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>
