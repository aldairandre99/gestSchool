<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Edit') }} — {{ $disciplina->nome }}</h2></x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <form method="POST" action="{{ route('disciplinas.update', $disciplina) }}" class="bg-white shadow rounded-lg p-6">@csrf @method('PUT') @include('disciplinas._form', ['disciplina' => $disciplina])</form>
    </div></div>
</x-app-layout>
