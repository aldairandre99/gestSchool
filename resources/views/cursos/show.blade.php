<x-app-layout>
    <x-page-header :title="$curso->nome" :subtitle="$curso->sigla">
        <x-slot name="actions">
            <x-btn variant="primary" icon="pencil" :href="route('cursos.edit', $curso)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('cursos.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Abbreviation') }}</dt><dd class="font-mono text-navy">{{ $curso->sigla }}</dd></div>
            <div><dt class="form-label">{{ __('Status') }}</dt><dd>@if($curso->activo)<x-badge variant="success">{{ __('Active') }}</x-badge>@else<x-badge variant="muted">{{ __('Inactive') }}</x-badge>@endif</dd></div>
            <div><dt class="form-label">Duração</dt><dd>{{ $curso->classes->count() }} anos</dd></div>
            @if($curso->descricao)
                <div class="sm:col-span-3"><dt class="form-label">Descrição</dt><dd class="text-navy">{{ $curso->descricao }}</dd></div>
            @endif
        </dl>
    </x-card>

    <x-card title="Classes do curso">
        @if($curso->classes->isEmpty())
            <x-empty title="Sem classes associadas" />
        @else
            <ul class="space-y-1 text-sm">
                @foreach($curso->classes as $c)
                    <li><x-badge variant="primary">Ano {{ $c->pivot->ano }}</x-badge> <span class="font-semibold text-navy ms-2">{{ $c->nome }}</span></li>
                @endforeach
            </ul>
        @endif
    </x-card>

    <x-card title="Turmas activas">
        @if($curso->turmas->isEmpty())
            <x-empty title="Sem turmas para este curso" />
        @else
            <ul class="space-y-1 text-sm">
                @foreach($curso->turmas as $t)
                    <li><a href="{{ route('turmas.show', $t) }}" class="text-primary hover:underline">{{ $t->classe->nome }} {{ $t->nome }}</a></li>
                @endforeach
            </ul>
        @endif
    </x-card>
</x-app-layout>
