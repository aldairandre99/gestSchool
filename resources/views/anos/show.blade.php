<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ $ano->codigo }}</h2></x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <div class="bg-white shadow rounded-lg p-6 text-sm">
            <dl class="grid grid-cols-2 gap-3">
                <div><dt class="text-gray-500">{{ __('Code') }}</dt><dd class="font-mono">{{ $ano->codigo }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Status') }}</dt><dd>{{ $ano->activo ? __('Active Year') : '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Start') }}</dt><dd>{{ $ano->inicio->format('d/m/Y') }}</dd></div>
                <div><dt class="text-gray-500">{{ __('End') }}</dt><dd>{{ $ano->fim->format('d/m/Y') }}</dd></div>
            </dl>
            <div class="mt-6 flex gap-3"><a href="{{ route('anos.edit', $ano) }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Edit') }}</a><a href="{{ route('anos.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a></div>
        </div>
    </div></div>
</x-app-layout>
