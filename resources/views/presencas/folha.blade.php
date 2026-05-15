<x-app-layout>
    <x-page-header
        :title="__('Attendance Sheet')"
        :subtitle="$aula->atribuicao->turma->classe->nome . ' ' . $aula->atribuicao->turma->nome . ' · ' . $aula->atribuicao->disciplina->nome . ' · ' . $aula->data->format('d/m/Y') . ($aula->hora_inicio ? ' · ' . \Carbon\Carbon::parse($aula->hora_inicio)->format('H:i') : '')">
        <x-slot name="actions">
            <x-btn variant="secondary" :href="route('aulas.show', $aula)">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card x-data="folhaPresencas({{ json_encode($matriculas->map(function ($m) use ($existentes) {
        return [
            'id' => $m->id,
            'nome' => $m->aluno->user->name,
            'numero' => $m->numero_matricula,
            'estado' => $existentes[$m->id]?->estado ?? 'presente',
            'observacao' => $existentes[$m->id]?->observacao ?? '',
        ];
    })->values()) }})">

        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
            <div class="bg-gray-50 rounded p-4 text-center">
                <p class="stat-label">Total</p>
                <p class="text-2xl font-bold text-navy" x-text="alunos.length"></p>
            </div>
            <button type="button" @click="marcarTodos('presente')" class="bg-success-soft rounded p-4 text-center hover:brightness-95">
                <p class="stat-label text-success">Presentes</p>
                <p class="text-2xl font-bold text-success" x-text="counts.presente"></p>
            </button>
            <button type="button" @click="marcarTodos('falta')" class="bg-danger-soft rounded p-4 text-center hover:brightness-95">
                <p class="stat-label text-danger">Faltas</p>
                <p class="text-2xl font-bold text-danger" x-text="counts.falta"></p>
            </button>
            <button type="button" @click="marcarTodos('falta_justificada')" class="bg-warning-soft rounded p-4 text-center hover:brightness-95">
                <p class="stat-label text-yellow-800">Faltas just.</p>
                <p class="text-2xl font-bold text-yellow-800" x-text="counts.falta_justificada"></p>
            </button>
            <button type="button" @click="marcarTodos('atraso')" class="rounded p-4 text-center hover:brightness-95" style="background:#fff1e3">
                <p class="stat-label" style="color:#b86a14">Atrasos</p>
                <p class="text-2xl font-bold" style="color:#b86a14" x-text="counts.atraso"></p>
            </button>
        </div>

        <p class="text-xs text-muted mb-4">Clica num cabeçalho colorido para marcar todos os alunos com esse estado.</p>

        <form method="POST" action="{{ route('presencas.gravar', $aula) }}">
            @csrf
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Student') }}</th>
                            <th class="text-center bg-success-soft !text-success">P</th>
                            <th class="text-center bg-danger-soft !text-danger">F</th>
                            <th class="text-center bg-warning-soft !text-yellow-800">FJ</th>
                            <th class="text-center !text-orange-700" style="background:#fff1e3">A</th>
                            <th>Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="a in alunos" :key="a.id">
                            <tr>
                                <td>
                                    <div class="font-semibold text-navy" x-text="a.nome"></div>
                                    <div class="text-xs text-muted font-mono" x-text="a.numero"></div>
                                </td>
                                <td class="text-center">
                                    <input type="radio" :name="'estados[' + a.id + ']'" value="presente"
                                           :checked="a.estado === 'presente'" @change="a.estado = 'presente'"
                                           class="w-5 h-5 text-success border-gray-300 focus:ring-success/30">
                                </td>
                                <td class="text-center">
                                    <input type="radio" :name="'estados[' + a.id + ']'" value="falta"
                                           :checked="a.estado === 'falta'" @change="a.estado = 'falta'"
                                           class="w-5 h-5 text-danger border-gray-300 focus:ring-danger/30">
                                </td>
                                <td class="text-center">
                                    <input type="radio" :name="'estados[' + a.id + ']'" value="falta_justificada"
                                           :checked="a.estado === 'falta_justificada'" @change="a.estado = 'falta_justificada'"
                                           class="w-5 h-5 text-warning border-gray-300 focus:ring-warning/30">
                                </td>
                                <td class="text-center">
                                    <input type="radio" :name="'estados[' + a.id + ']'" value="atraso"
                                           :checked="a.estado === 'atraso'" @change="a.estado = 'atraso'"
                                           class="w-5 h-5 text-accent border-gray-300 focus:ring-accent/30">
                                </td>
                                <td>
                                    <input type="text" :name="'observacoes[' + a.id + ']'" x-model="a.observacao"
                                           class="form-input"
                                           :placeholder="a.estado === 'presente' ? '' : 'Justificação / nota'">
                                </td>
                            </tr>
                        </template>
                        <template x-if="alunos.length === 0">
                            <tr><td colspan="6" class="table-empty">{{ __('No records found.') }}</td></tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex gap-3">
                <x-btn variant="primary" type="submit" icon="check">{{ __('Save Attendance') }}</x-btn>
                <x-btn variant="secondary" :href="route('aulas.show', $aula)">{{ __('Cancel') }}</x-btn>
            </div>
        </form>
    </x-card>

    <script>
        function folhaPresencas(initial) {
            return {
                alunos: initial,
                get counts() {
                    return {
                        presente: this.alunos.filter(a => a.estado === 'presente').length,
                        falta: this.alunos.filter(a => a.estado === 'falta').length,
                        falta_justificada: this.alunos.filter(a => a.estado === 'falta_justificada').length,
                        atraso: this.alunos.filter(a => a.estado === 'atraso').length,
                    }
                },
                marcarTodos(estado) {
                    this.alunos = this.alunos.map(a => ({ ...a, estado }));
                },
            }
        }
    </script>
</x-app-layout>
