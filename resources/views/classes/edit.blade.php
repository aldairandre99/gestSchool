<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Edit') }} — {{ $classe->nome }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <form method="POST" action="{{ route('classes.update', $classe) }}" class="bg-white shadow rounded-lg p-6">@csrf @method('PUT') @include('classes._form', ['classe' => $classe])</form>
    </div></div>
</x-app-layout>
