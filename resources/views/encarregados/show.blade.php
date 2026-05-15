<x-app-layout>
    <x-page-header :title="$encarregado->user->name">
        <x-slot name="actions">
            <x-btn variant="primary" icon="pencil" :href="route('encarregados.edit', $encarregado)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('encarregados.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card title="Dados pessoais">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Email') }}</dt><dd class="text-navy">{{ $encarregado->user->email }}</dd></div>
            <div><dt class="form-label">{{ __('Phone') }}</dt><dd class="text-navy">{{ $encarregado->user->phone ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('BI Number') }}</dt><dd>{{ $encarregado->bi ?? '—' }}</dd></div>
            <div><dt class="form-label">Profissão</dt><dd>{{ $encarregado->profissao ?? '—' }}</dd></div>
            <div><dt class="form-label">Local de trabalho</dt><dd>{{ $encarregado->local_trabalho ?? '—' }}</dd></div>
            <div class="sm:col-span-2"><dt class="form-label">{{ __('Address') }}</dt><dd>{{ $encarregado->morada ?? '—' }}</dd></div>
        </dl>
    </x-card>

    <x-card :title="__('Linked Students')">
        @if($encarregado->alunos->isEmpty())
            <x-empty title="Sem alunos associados" />
        @else
            <ul class="space-y-2 text-sm">
                @foreach($encarregado->alunos as $a)
                    <li class="flex items-center gap-3">
                        <a href="{{ route('alunos.show', $a) }}" class="text-navy font-semibold hover:underline">{{ $a->user->name }}</a>
                        <span class="text-xs text-muted font-mono">{{ $a->numero_processo }}</span>
                        <x-badge variant="muted">{{ $a->classe ?? '—' }} / {{ $a->turma ?? '—' }}</x-badge>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-card>
</x-app-layout>
