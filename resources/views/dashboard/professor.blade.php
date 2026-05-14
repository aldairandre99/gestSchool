<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Dashboard') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <x-flash />
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800">{{ __('Welcome') }}, {{ Auth::user()->name }}</h3>
                @if($professor)
                    <dl class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-gray-500">{{ __('Process Number') }}</dt><dd class="font-medium">{{ $professor->numero_professor ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500">{{ __('Qualification') }}</dt><dd class="font-medium">{{ $professor->habilitacoes ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500">{{ __('Subjects') }}</dt><dd class="font-medium">{{ $professor->disciplinas ?? '—' }}</dd></div>
                    </dl>
                @endif
                <a href="{{ route('meus-alunos.index') }}" class="inline-block mt-4 px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Students') }}</a>
            </div>
        </div>
    </div>
</x-app-layout>
