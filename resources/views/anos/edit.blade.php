<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Edit') }} — {{ $ano->codigo }}</h2></x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <form method="POST" action="{{ route('anos.update', $ano) }}" class="bg-white shadow rounded-lg p-6">@csrf @method('PUT') @include('anos._form', ['ano' => $ano])</form>
    </div></div>
</x-app-layout>
