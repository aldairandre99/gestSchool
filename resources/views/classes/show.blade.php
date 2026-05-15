<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ $classe->nome }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6 text-sm">
            <dl class="grid grid-cols-3 gap-3">
                <div><dt class="text-gray-500">{{ __('Name') }}</dt><dd class="font-medium">{{ $classe->nome }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Order') }}</dt><dd>{{ $classe->ordem }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Level') }}</dt><dd>{{ $classe->nivel ?? '—' }}</dd></div>
            </dl>
            <h3 class="text-xs uppercase text-gray-500 mt-6 mb-2">{{ __('Subjects') }}</h3>
            <div class="flex flex-wrap gap-1">
                @foreach($classe->disciplinas as $d)
                    <span class="bg-gray-100 rounded px-2 py-0.5 text-xs">{{ $d->nome }}</span>
                @endforeach
            </div>
            <h3 class="text-xs uppercase text-gray-500 mt-6 mb-2">{{ __('Class Groups') }}</h3>
            <ul class="space-y-1">
                @foreach($classe->turmas as $t)
                    <li><a href="{{ route('turmas.show', $t) }}" class="text-blue-600 hover:underline">{{ $classe->nome }} {{ $t->nome }}</a> <span class="text-xs text-gray-500">— {{ $t->anoLectivo->codigo }}</span></li>
                @endforeach
            </ul>
            <div class="mt-6 flex gap-3"><a href="{{ route('classes.edit', $classe) }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Edit') }}</a><a href="{{ route('classes.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a></div>
        </div>
    </div></div>
</x-app-layout>
