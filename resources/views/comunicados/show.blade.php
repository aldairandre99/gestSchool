<x-app-layout>
    <x-page-header :title="$comunicado->titulo">
        <x-slot name="actions">
            <x-btn variant="secondary" :href="route('comunicados.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <div class="flex items-center gap-2 flex-wrap text-xs text-muted mb-6 pb-4 border-b border-gray-100">
            <span><x-lucide-user class="inline w-3 h-3" /> {{ $comunicado->autor?->name }}</span>
            <span>·</span>
            <span><x-lucide-clock class="inline w-3 h-3" /> {{ $comunicado->publicado_em ? $comunicado->publicado_em->format('d/m/Y H:i') : __('draft') }}</span>
            <x-badge variant="muted">{{ str_replace('_', ' ', $comunicado->alcance) }}</x-badge>
            @if($comunicado->classe)<x-badge variant="info">{{ $comunicado->classe->nome }}</x-badge>@endif
            @if($comunicado->turma)<x-turma-label :turma="$comunicado->turma" />@endif
        </div>
        <div class="prose max-w-none text-sm text-navy whitespace-pre-line leading-relaxed">{{ $comunicado->conteudo }}</div>
    </x-card>
</x-app-layout>
