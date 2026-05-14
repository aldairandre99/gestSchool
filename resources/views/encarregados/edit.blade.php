<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Edit') }} — {{ $encarregado->user->name }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <form method="POST" action="{{ route('encarregados.update', $encarregado) }}" class="bg-white shadow rounded-lg p-6">@csrf @method('PUT') @include('encarregados._form', ['encarregado' => $encarregado])</form>
    </div></div>
</x-app-layout>
