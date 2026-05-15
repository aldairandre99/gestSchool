<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Edit') }} — {{ $matricula->numero_matricula }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <form method="POST" action="{{ route('matriculas.update', $matricula) }}" class="bg-white shadow rounded-lg p-6">@csrf @method('PUT') @include('matriculas._form', ['matricula' => $matricula])</form>
    </div></div>
</x-app-layout>
