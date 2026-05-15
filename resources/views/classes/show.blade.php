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
            <div><dt class="form-label">{{ __('Level') }}</dt><dd>{{ $classe->nivel ?? '—' }}</dd></div>
        </dl>
    </x-card>

    <x-card :title="__('Subjects')">
        <div class="flex flex-wrap gap-2">
            @foreach($classe->disciplinas as $d)<x-badge variant="muted">{{ $d->nome }}</x-badge>@endforeach
        </div>
    </x-card>

    <x-card :title="__('Class Groups')">
        <ul class="space-y-1 text-sm">
            @forelse($classe->turmas as $t)
                <li><a href="{{ route('turmas.show', $t) }}" class="text-primary hover:underline">{{ $classe->nome }} {{ $t->nome }}</a> <span class="text-xs text-muted ms-2">— {{ $t->anoLectivo->codigo }}</span></li>
            @empty
                <li class="text-muted">{{ __('No records found.') }}</li>
            @endforelse
        </ul>
    </x-card>
</x-app-layout>
