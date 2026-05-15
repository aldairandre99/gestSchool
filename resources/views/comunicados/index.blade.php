<x-app-layout>
    <x-page-header :title="__('Announcements')">
        @if($podeGerir)
            <x-slot name="actions">
                <x-btn variant="primary" icon="plus" :href="route('comunicados.create')">{{ __('New') }}</x-btn>
            </x-slot>
        @endif
    </x-page-header>

    @if($comunicados->isEmpty())
        <x-card>
            <x-empty title="{{ __('No records') }}" />
        </x-card>
    @else
        <div class="space-y-4">
            @foreach($comunicados as $c)
                <x-card>
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <a href="{{ route('comunicados.show', $c) }}" class="block">
                                <h3 class="text-lg font-semibold text-navy hover:text-primary transition">{{ $c->titulo }}</h3>
                            </a>
                            <div class="flex items-center gap-2 mt-2 text-xs text-muted flex-wrap">
                                <span>{{ $c->autor?->name }}</span>
                                <span>·</span>
                                <span>{{ $c->publicado_em ? $c->publicado_em->format('d/m/Y H:i') : __('draft') }}</span>
                                <x-badge variant="muted">{{ str_replace('_', ' ', $c->alcance) }}</x-badge>
                            </div>
                            <p class="text-sm text-muted mt-3">{{ \Illuminate\Support\Str::limit(strip_tags($c->conteudo), 200) }}</p>
                        </div>
                        @if($podeGerir)
                            <div class="shrink-0 text-right">
                                <x-btn-link variant="muted" :href="route('comunicados.edit', $c)">{{ __('Edit') }}</x-btn-link>
                                <form action="{{ route('comunicados.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Delete?') }}');">
                                    @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </x-card>
            @endforeach
        </div>
        <div class="mt-4">{{ $comunicados->links() }}</div>
    @endif
</x-app-layout>
