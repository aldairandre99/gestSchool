<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ $encarregado->user->name }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6 text-sm">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div><dt class="text-gray-500">{{ __('Email') }}</dt><dd>{{ $encarregado->user->email }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Phone') }}</dt><dd>{{ $encarregado->user->phone ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('BI Number') }}</dt><dd>{{ $encarregado->bi ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Profissão</dt><dd>{{ $encarregado->profissao ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Local de Trabalho</dt><dd>{{ $encarregado->local_trabalho ?? '—' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-gray-500">{{ __('Address') }}</dt><dd>{{ $encarregado->morada ?? '—' }}</dd></div>
            </dl>

            <h3 class="text-xs uppercase text-gray-500 mt-6 mb-2">{{ __('Linked Students') }}</h3>
            <ul class="space-y-1">
                @forelse($encarregado->alunos as $a)
                    <li class="flex items-center gap-3">
                        <a href="{{ route('alunos.show', $a) }}" class="font-medium text-blue-600 hover:underline">{{ $a->user->name }}</a>
                        <span class="text-xs text-gray-500">{{ $a->numero_processo }} · {{ $a->classe ?? '—' }} / {{ $a->turma ?? '—' }}</span>
                    </li>
                @empty
                    <li class="text-gray-500">—</li>
                @endforelse
            </ul>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('encarregados.edit', $encarregado) }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Edit') }}</a>
                <a href="{{ route('encarregados.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a>
            </div>
        </div>
    </div></div>
</x-app-layout>
