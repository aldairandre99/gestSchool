<x-app-layout>
    <x-page-header :title="__('Report Card')" :subtitle="$matricula->aluno->user->name">
        <x-slot name="actions">
            <x-btn variant="danger" icon="file-down" :href="route('boletim.pdf', $matricula)">{{ __('Export PDF') }}</x-btn>
            <x-btn variant="primary" icon="printer" href="javascript:print()">{{ __('Print') }}</x-btn>
            <x-btn variant="secondary" :href="url()->previous()">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm mb-6">
            <div><dt class="form-label">{{ __('Student') }}</dt><dd class="text-navy font-semibold">{{ $matricula->aluno->user->name }}</dd></div>
            <div><dt class="form-label">{{ __('Class Groups') }}</dt><dd>{{ $matricula->turma->classe->nome }} {{ $matricula->turma->nome }}</dd></div>
            <div><dt class="form-label">{{ __('School Year') }}</dt><dd>{{ $matricula->anoLectivo->codigo }}</dd></div>
        </div>

        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Subjects List') }}</th>
                        @foreach($trimestres as $t)<th class="text-center">{{ $t->numero }}º {{ __('Term') }}</th>@endforeach
                        <th class="text-center !text-navy font-bold">{{ __('Annual Average') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medias as $info)
                        <tr>
                            <td class="font-semibold text-navy">{{ $info['nome'] }}</td>
                            @foreach($trimestres as $t)
                                @php($m = $info['trimestres'][$t->id] ?? null)
                                <td class="text-center {{ $m !== null && $m < 10 ? 'text-danger' : '' }}">{{ $m !== null ? $m : '—' }}</td>
                            @endforeach
                            <td class="text-center font-bold {{ ($info['anual'] ?? null) !== null && $info['anual'] < 10 ? 'text-danger' : 'text-navy' }}">
                                {{ $info['anual'] !== null ? $info['anual'] : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ 2 + count($trimestres) }}" class="table-empty">{{ __('No records found.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
