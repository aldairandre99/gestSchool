<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Edit') }} — {{ $professor->user->name }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <form method="POST" action="{{ route('professores.update', $professor) }}" class="bg-white shadow rounded-lg p-6">@csrf @method('PUT') @include('professores._form', ['professor' => $professor])</form>
    </div></div>
</x-app-layout>
