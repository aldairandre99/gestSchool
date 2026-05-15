<x-app-layout>
    <x-page-header :title="__('Enrollments')" />

    <x-data-table
        :searchPlaceholder="__('Search') . ' processo ou nome'"
        :searchValue="$q ?? ''"
        :createUrl="route('matriculas.create')">
        <x-slot name="filters">
            <div>
                <label class="form-label">{{ __('School Year') }}</label>
                <select name="ano_lectivo_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($anos as $a)<option value="{{ $a->id }}" @selected($anoId == $a->id)>{{ $a->codigo }}</option>@endforeach
                </select>
            </div>
            <div>
                <label class="form-label">{{ __('Class Groups') }}</label>
                <select name="turma_id" class="form-select">
                    <option value="">Todas</option>
                    @foreach($turmas as $t)<option value="{{ $t->id }}" @selected($turmaId == $t->id)>{{ $t->display_label }}</option>@endforeach
                </select>
            </div>
        </x-slot>

        <thead>
            <tr>
                <th>{{ __('Nº') ?? 'Nº' }}</th>
                <th>{{ __('Student') }}</th>
                <th>{{ __('Class Groups') }}</th>
                <th>{{ __('School Year') }}</th>
                <th>{{ __('Status') }}</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @php($estadoCor = ['activa' => 'success', 'aprovado' => 'success', 'transferido' => 'info', 'desistente' => 'muted', 'reprovado' => 'danger'])
            @forelse($matriculas as $m)
                <tr>
                    <td class="font-mono text-xs">{{ $m->numero_matricula }}</td>
                    <td class="font-semibold text-navy">{{ $m->aluno->user->name }}</td>
                    <td><x-turma-label :turma="$m->turma" /></td>
                    <td>{{ $m->anoLectivo->codigo }}</td>
                    <td><x-badge :variant="$estadoCor[$m->estado] ?? 'muted'">{{ __($m->estado) }}</x-badge></td>
                    <td class="table-actions">
                        <x-btn-link :href="route('boletim.show', $m)">{{ __('Report Card') }}</x-btn-link>
                        <x-btn-link variant="muted" :href="route('matriculas.edit', $m)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('matriculas.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Delete?') }}');">
                            @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $matriculas->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
