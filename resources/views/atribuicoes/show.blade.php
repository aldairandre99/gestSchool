<x-app-layout>
    <x-page-header :title="__('Assignment')">
        <x-slot name="actions">
            <x-btn variant="primary" icon="pencil" :href="route('atribuicoes.edit', $atribuicao)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('atribuicoes.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-2 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Teacher') }}</dt><dd class="text-navy font-semibold">{{ $atribuicao->professor->user->name }}</dd></div>
            <div><dt class="form-label">{{ __('Class Groups') }}</dt><dd><x-turma-label :turma="$atribuicao->turma" /></dd></div>
            <div><dt class="form-label">{{ __('Subjects List') }}</dt><dd>{{ $atribuicao->disciplina->nome }}</dd></div>
            <div><dt class="form-label">{{ __('School Year') }}</dt><dd>{{ $atribuicao->anoLectivo->codigo }}</dd></div>
        </dl>
    </x-card>
</x-app-layout>
