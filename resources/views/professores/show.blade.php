<x-app-layout>
    <x-page-header :title="$professor->user->name">
        <x-slot name="actions">
            <x-btn variant="primary" icon="pencil" :href="route('professores.edit', $professor)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('professores.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Email') }}</dt><dd class="text-navy">{{ $professor->user->email }}</dd></div>
            <div><dt class="form-label">{{ __('Phone') }}</dt><dd class="text-navy">{{ $professor->user->phone ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Process Number') }}</dt><dd class="text-navy">{{ $professor->numero_professor ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Qualification') }}</dt><dd class="text-navy">{{ $professor->habilitacoes ?? '—' }}</dd></div>
            <div class="sm:col-span-2"><dt class="form-label">{{ __('Subjects') }}</dt><dd class="text-navy">{{ $professor->disciplinas ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Hire Date') }}</dt><dd class="text-navy">{{ $professor->data_admissao?->format('d/m/Y') ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Assistant Teacher') }}</dt><dd>
                @if($professor->assistente)<x-badge variant="info">{{ __('Yes') }}</x-badge>@else<x-badge variant="muted">{{ __('No') }}</x-badge>@endif
            </dd></div>
        </dl>
    </x-card>
</x-app-layout>
