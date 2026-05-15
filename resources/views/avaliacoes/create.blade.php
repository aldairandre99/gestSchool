<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('New') }} — {{ __('Evaluations') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <form method="POST" action="{{ route('avaliacoes.store') }}" class="bg-white shadow rounded-lg p-6">@csrf @include('avaliacoes._form', ['avaliacao' => null])</form>
    </div></div>
</x-app-layout>
