<x-app-layout>
    <x-page-header :title="$comunicado->titulo" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('comunicados.update', $comunicado) }}">@csrf @method('PUT') @include('comunicados._form', ['comunicado' => $comunicado])</form>
    </x-card>
</x-app-layout>
