<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Edit') }} — {{ $avaliacao->titulo }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <form method="POST" action="{{ route('avaliacoes.update', $avaliacao) }}" class="bg-white shadow rounded-lg p-6">@csrf @method('PUT') @include('avaliacoes._form', ['avaliacao' => $avaliacao])</form>
    </div></div>
</x-app-layout>
