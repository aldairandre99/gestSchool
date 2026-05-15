<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Edit') }} — {{ __('Assignment') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <form method="POST" action="{{ route('atribuicoes.update', $atribuicao) }}" class="bg-white shadow rounded-lg p-6">@csrf @method('PUT') @include('atribuicoes._form', ['atribuicao' => $atribuicao])</form>
    </div></div>
</x-app-layout>
