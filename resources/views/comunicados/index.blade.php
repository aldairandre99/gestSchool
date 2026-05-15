<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Announcements') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            @if($podeGerir)
                <div class="flex justify-end mb-3"><a href="{{ route('comunicados.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('New') }}</a></div>
            @endif
            @forelse($comunicados as $c)
                <article class="border-b last:border-0 py-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800"><a href="{{ route('comunicados.show', $c) }}" class="hover:underline">{{ $c->titulo }}</a></h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $c->autor?->name }} · {{ $c->publicado_em ? $c->publicado_em->format('d/m/Y H:i') : 'rascunho' }}
                                · <span class="bg-gray-100 rounded px-2 py-0.5">{{ str_replace('_', ' ', $c->alcance) }}</span>
                            </p>
                            <p class="text-sm text-gray-600 mt-2">{{ \Illuminate\Support\Str::limit(strip_tags($c->conteudo), 200) }}</p>
                        </div>
                        @if($podeGerir)
                            <div class="text-xs">
                                <a href="{{ route('comunicados.edit', $c) }}" class="text-gray-700">{{ __('Edit') }}</a>
                                <form action="{{ route('comunicados.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('?');">@csrf @method('DELETE')<button class="text-red-600 ms-2">{{ __('Delete') }}</button></form>
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <p class="text-sm text-gray-500 py-6 text-center">{{ __('No records found.') }}</p>
            @endforelse
            <div class="mt-4">{{ $comunicados->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
