<x-app-layout>
    <x-page-header title="Aulas" subtitle="Livro de ponto digital" />

    @php($hoje = now()->toDateString())
    @php($hojeActivo = $dataDe === $hoje && $dataAte === $hoje)

    <x-data-table :createUrl="route('aulas.create')" :createLabel="__('New') . ' aula'">
        <x-slot name="filters">
            <div>
                <label class="form-label">{{ __('Class Groups') }}</label>
                <select name="turma_id" class="form-select">
                    <option value="">Todas</option>
                    @foreach($turmas as $t)<option value="{{ $t->id }}" @selected($turmaId == $t->id)>{{ $t->classe->nome }} {{ $t->nome }} — {{ $t->anoLectivo->codigo }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="form-label">{{ __('Subjects List') }}</label>
                <select name="disciplina_id" class="form-select">
                    <option value="">Todas</option>
                    @foreach($disciplinas as $d)<option value="{{ $d->id }}" @selected($disciplinaId == $d->id)>{{ $d->nome }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="form-label">De</label>
                <input type="date" name="data_de" value="{{ $dataDe }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Até</label>
                <input type="date" name="data_ate" value="{{ $dataAte }}" class="form-input">
            </div>
            <div class="self-end">
                <a href="{{ route('aulas.index', array_filter(['turma_id' => $turmaId, 'disciplina_id' => $disciplinaId, 'data_de' => $hoje, 'data_ate' => $hoje])) }}"
                   class="btn {{ $hojeActivo ? 'btn-dark' : 'btn-secondary' }} btn-sm">Hoje</a>
            </div>
        </x-slot>

        <thead>
            <tr>
                <th>{{ __('Date') }}</th>
                <th>Hora</th>
                <th>{{ __('Class Groups') }}</th>
                <th>{{ __('Subjects List') }}</th>
                <th>Nº</th>
                <th>Sumário</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($aulas as $a)
                <tr>
                    <td>{{ $a->data->format('d/m/Y') }}</td>
                    <td class="text-xs text-muted">{{ $a->hora_inicio ? \Carbon\Carbon::parse($a->hora_inicio)->format('H:i') : '—' }}@if($a->hora_fim) – {{ \Carbon\Carbon::parse($a->hora_fim)->format('H:i') }}@endif</td>
                    <td class="font-semibold text-navy">{{ $a->atribuicao->turma->classe->nome }} {{ $a->atribuicao->turma->nome }}</td>
                    <td>{{ $a->atribuicao->disciplina->nome }}</td>
                    <td class="text-muted">{{ $a->numero ?? '—' }}</td>
                    <td class="text-xs text-muted">{{ \Illuminate\Support\Str::limit($a->sumario, 60) ?: '—' }}</td>
                    <td class="table-actions">
                        <x-btn-link :href="route('presencas.folha', $a)">{{ __('Mark Attendance') }}</x-btn-link>
                        <x-btn-link variant="muted" :href="route('aulas.edit', $a)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('aulas.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar?');">
                            @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $aulas->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
