<x-app-layout>
    <x-page-header :title="$avaliacao->titulo">
        <x-slot name="actions">
            <x-btn variant="primary" icon="clipboard-list" :href="route('notas.folha', $avaliacao)">{{ __('Launch Grades') }}</x-btn>
            <x-btn variant="secondary" :href="route('avaliacoes.edit', $avaliacao)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('avaliacoes.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Class Groups') }}</dt><dd>{{ $avaliacao->atribuicao->turma->classe->nome }} {{ $avaliacao->atribuicao->turma->nome }}</dd></div>
            <div><dt class="form-label">{{ __('Subjects List') }}</dt><dd>{{ $avaliacao->atribuicao->disciplina->nome }}</dd></div>
            <div><dt class="form-label">{{ __('Term') }}</dt><dd>{{ $avaliacao->trimestre->numero }}º</dd></div>
            <div><dt class="form-label">{{ __('Type') }}</dt><dd>{{ ucfirst(str_replace('_', ' ', $avaliacao->tipo)) }}</dd></div>
            <div><dt class="form-label">{{ __('Date') }}</dt><dd>{{ $avaliacao->data?->format('d/m/Y') ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Weight') }}</dt><dd>{{ $avaliacao->peso }}</dd></div>
            <div><dt class="form-label">{{ __('Max Score') }}</dt><dd>{{ $avaliacao->max_nota }}</dd></div>
        </dl>
    </x-card>
</x-app-layout>
