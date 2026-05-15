<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('New') }} — {{ __('Class Groups') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <form method="POST" action="{{ route('turmas.store') }}" class="bg-white shadow rounded-lg p-6">@csrf @include('turmas._form', ['turma' => null])</form>
    </div></div>
</x-app-layout>
