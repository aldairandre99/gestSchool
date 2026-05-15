<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Attendance') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600 mb-4">{{ __('Select a class group/subject to open the attendance sheet.') }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse($atribuicoes as $a)
                    <a href="{{ route('presencas.folha', $a) }}" class="block border rounded-lg p-4 hover:bg-gray-50">
                        <div class="font-medium text-gray-800">{{ $a->turma->classe->nome }} {{ $a->turma->nome }} — {{ $a->disciplina->nome }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $a->anoLectivo->codigo }}</div>
                    </a>
                @empty
                    <p class="text-sm text-gray-500">{{ __('No records found.') }}</p>
                @endforelse
            </div>
        </div>
    </div></div>
</x-app-layout>
