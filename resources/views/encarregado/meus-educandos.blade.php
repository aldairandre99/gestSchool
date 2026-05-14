<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('My Children') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($alunos as $aluno)
                    <a href="{{ route('meus-educandos.show', $aluno) }}" class="block border rounded-lg p-4 hover:bg-gray-50">
                        <div class="font-semibold text-gray-800">{{ $aluno->user->name }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ __('Process Number') }}: {{ $aluno->numero_processo }}</div>
                        <div class="text-xs text-gray-500">{{ __('Grade') }}: {{ $aluno->classe ?? '—' }} · {{ __('Class') }}: {{ $aluno->turma ?? '—' }}</div>
                    </a>
                @empty
                    <p class="text-sm text-gray-500 col-span-2">{{ __('No records found.') }}</p>
                @endforelse
            </div>
        </div>
    </div></div>
</x-app-layout>
