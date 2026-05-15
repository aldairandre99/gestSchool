<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('New') }} — {{ __('Term') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <form method="POST" action="{{ route('trimestres.store') }}" class="bg-white shadow rounded-lg p-6">@csrf @include('trimestres._form', ['trimestre' => null])</form>
    </div></div>
</x-app-layout>
