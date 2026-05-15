<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ $comunicado->titulo }}</h2></x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <article class="bg-white shadow rounded-lg p-6">
            <p class="text-xs text-gray-500 mb-4">
                {{ $comunicado->autor?->name }} ·
                {{ $comunicado->publicado_em ? $comunicado->publicado_em->format('d/m/Y H:i') : 'rascunho' }} ·
                <span class="bg-gray-100 rounded px-2 py-0.5">{{ str_replace('_', ' ', $comunicado->alcance) }}</span>
                @if($comunicado->classe) · {{ $comunicado->classe->nome }} @endif
                @if($comunicado->turma) · {{ $comunicado->turma->classe->nome }} {{ $comunicado->turma->nome }} @endif
            </p>
            <div class="prose max-w-none text-sm whitespace-pre-line">{{ $comunicado->conteudo }}</div>
            <div class="mt-6"><a href="{{ route('comunicados.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a></div>
        </article>
    </div></div>
</x-app-layout>
