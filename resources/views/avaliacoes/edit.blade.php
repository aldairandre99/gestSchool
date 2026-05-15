<x-app-layout>
    <x-page-header :title="$avaliacao->titulo" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('avaliacoes.update', $avaliacao) }}">@csrf @method('PUT') @include('avaliacoes._form', ['avaliacao' => $avaliacao])</form>
    </x-card>
</x-app-layout>
