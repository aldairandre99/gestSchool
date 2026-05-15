<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Attendance Sheet') }} —
            {{ $aula->atribuicao->turma->classe->nome }} {{ $aula->atribuicao->turma->nome }} ·
            {{ $aula->atribuicao->disciplina->nome }} ·
            {{ $aula->data->format('d/m/Y') }}
            @if($aula->hora_inicio) <span class="text-sm text-gray-500 ms-2">{{ \Carbon\Carbon::parse($aula->hora_inicio)->format('H:i') }}</span> @endif
        </h2>
    </x-slot>

    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6"
             x-data="folhaPresencas({{ json_encode($matriculas->map(function ($m) use ($existentes) {
                 return [
                     'id' => $m->id,
                     'nome' => $m->aluno->user->name,
                     'numero' => $m->numero_matricula,
                     'estado' => $existentes[$m->id]?->estado ?? 'presente',
                     'observacao' => $existentes[$m->id]?->observacao ?? '',
                 ];
             })->values()) }}) ">

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-center text-xs mb-6">
                <div class="bg-gray-50 rounded p-2"><div class="text-gray-500">Total</div><div class="text-2xl font-bold" x-text="alunos.length"></div></div>
                <div class="bg-green-50 rounded p-2 cursor-pointer hover:bg-green-100" @click="marcarTodos('presente')">
                    <div class="text-green-700">Presentes</div>
                    <div class="text-2xl font-bold text-green-800" x-text="counts.presente"></div>
                </div>
                <div class="bg-red-50 rounded p-2 cursor-pointer hover:bg-red-100" @click="marcarTodos('falta')">
                    <div class="text-red-700">Faltas</div>
                    <div class="text-2xl font-bold text-red-800" x-text="counts.falta"></div>
                </div>
                <div class="bg-yellow-50 rounded p-2 cursor-pointer hover:bg-yellow-100" @click="marcarTodos('falta_justificada')">
                    <div class="text-yellow-700">Faltas just.</div>
                    <div class="text-2xl font-bold text-yellow-800" x-text="counts.falta_justificada"></div>
                </div>
                <div class="bg-orange-50 rounded p-2 cursor-pointer hover:bg-orange-100" @click="marcarTodos('atraso')">
                    <div class="text-orange-700">Atrasos</div>
                    <div class="text-2xl font-bold text-orange-800" x-text="counts.atraso"></div>
                </div>
            </div>

            <p class="text-xs text-gray-500 mb-3">Clica num cabeçalho colorido para marcar todos com esse estado.</p>

            <form method="POST" action="{{ route('presencas.gravar', $aula) }}">
                @csrf
                <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="text-gray-500 border-b">
                        <tr>
                            <th class="py-2 px-3 text-left">{{ __('Student') }}</th>
                            <th class="py-2 px-3 text-center bg-green-50 text-green-700">P</th>
                            <th class="py-2 px-3 text-center bg-red-50 text-red-700">F</th>
                            <th class="py-2 px-3 text-center bg-yellow-50 text-yellow-700">FJ</th>
                            <th class="py-2 px-3 text-center bg-orange-50 text-orange-700">A</th>
                            <th class="py-2 px-3 text-left">Observação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="a in alunos" :key="a.id">
                            <tr class="border-b last:border-0 hover:bg-gray-50">
                                <td class="py-2 px-3">
                                    <div class="font-medium text-gray-800" x-text="a.nome"></div>
                                    <div class="text-xs text-gray-500 font-mono" x-text="a.numero"></div>
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <input type="radio" :name="'estados[' + a.id + ']'" value="presente"
                                           :checked="a.estado === 'presente'" @change="a.estado = 'presente'"
                                           class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500">
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <input type="radio" :name="'estados[' + a.id + ']'" value="falta"
                                           :checked="a.estado === 'falta'" @change="a.estado = 'falta'"
                                           class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500">
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <input type="radio" :name="'estados[' + a.id + ']'" value="falta_justificada"
                                           :checked="a.estado === 'falta_justificada'" @change="a.estado = 'falta_justificada'"
                                           class="w-5 h-5 text-yellow-600 border-gray-300 focus:ring-yellow-500">
                                </td>
                                <td class="py-2 px-3 text-center">
                                    <input type="radio" :name="'estados[' + a.id + ']'" value="atraso"
                                           :checked="a.estado === 'atraso'" @change="a.estado = 'atraso'"
                                           class="w-5 h-5 text-orange-600 border-gray-300 focus:ring-orange-500">
                                </td>
                                <td class="py-2 px-3">
                                    <input type="text" :name="'observacoes[' + a.id + ']'" x-model="a.observacao"
                                           class="w-full border-gray-300 rounded-md text-sm"
                                           :placeholder="a.estado === 'presente' ? '' : 'Justificação / nota'">
                                </td>
                            </tr>
                        </template>
                        <template x-if="alunos.length === 0">
                            <tr><td colspan="6" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                        </template>
                    </tbody>
                </table>
                </div>

                <div class="mt-6 flex gap-3">
                    <button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save Attendance') }}</button>
                    <a href="{{ route('aulas.show', $aula) }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a>
                </div>
            </form>
        </div>
    </div></div>

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
