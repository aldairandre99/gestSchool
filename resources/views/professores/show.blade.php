<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ $professor->user->name }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <div class="bg-white shadow rounded-lg p-6 text-sm">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><dt class="text-gray-500">{{ __('Name') }}</dt><dd class="font-medium">{{ $professor->user->name }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Email') }}</dt><dd>{{ $professor->user->email }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Phone') }}</dt><dd>{{ $professor->user->phone ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Process Number') }}</dt><dd>{{ $professor->numero_professor ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Qualification') }}</dt><dd>{{ $professor->habilitacoes ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Subjects') }}</dt><dd>{{ $professor->disciplinas ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Hire Date') }}</dt><dd>{{ $professor->data_admissao?->format('d/m/Y') ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Assistant Teacher') }}</dt><dd>{{ $professor->assistente ? __('Yes') : __('No') }}</dd></div>
            </dl>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('professores.edit', $professor) }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Edit') }}</a>
                <a href="{{ route('professores.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a>
            </div>
        </div>
    </div></div>
</x-app-layout>
