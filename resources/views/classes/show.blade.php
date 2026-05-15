<x-app-layout>
    <x-page-header :title="$classe->nome">
        <x-slot name="actions">
            <x-btn variant="primary" icon="pencil" :href="route('classes.edit', $classe)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('classes.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-3 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Name') }}</dt><dd class="text-navy font-semibold">{{ $classe->nome }}</dd></div>
            <div><dt class="form-label">{{ __('Order') }}</dt><dd>{{ $classe->ordem }}</dd></div>
            <div><dt class="form-label">{{ __('Level') }}</dt><dd>
                @if($classe->nivel === 'ensino_medio')<x-badge variant="info">{{ __('Secondary Education') }}</x-badge>
                @else<x-badge variant="muted">{{ __('Basic Education') }}</x-badge>@endif
            </dd></div>
        </dl>
    </x-card>

    @if($classe->nivel === 'ensino_base')
        <x-card :title="__('Compulsory subjects (basic education)')">
            @if($classe->disciplinas->isEmpty())
                <x-empty title="{{ __('No subjects') }}" description="{{ __('All students of this class will have these subjects.') }}" />
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($classe->disciplinas as $d)<x-badge variant="muted">{{ $d->nome }}</x-badge>@endforeach
                </div>
            @endif
        </x-card>
    @else
        <x-card :title="__('Classes that include this class')">
            @if($classe->cursos->isEmpty())
                <x-empty title="{{ __('No course associated to this class.') }}" />
            @else
                <ul class="space-y-1 text-sm">
                    @foreach($classe->cursos as $c)
                        <li><x-badge variant="primary">{{ __('Year') }} {{ $c->pivot->ano }}</x-badge> <a href="{{ route('cursos.show', $c) }}" class="text-navy font-semibold hover:underline ms-2">{{ $c->sigla }} — {{ $c->nome }}</a></li>
                    @endforeach
                </ul>
            @endif
        </x-card>
    @endif

    <x-card :title="__('Class Groups')">
        <ul class="space-y-1 text-sm">
            @forelse($classe->turmas as $t)
                <li><a href="{{ route('turmas.show', $t) }}" class="hover:underline"><x-turma-label :turma="$t" :showAno="true" /></a></li>
            @empty
                <li class="text-muted">{{ __('No records found.') }}</li>
            @endforelse
        </ul>
    </x-card>
</x-app-layout>
