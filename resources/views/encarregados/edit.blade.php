<x-app-layout>
    <x-page-header :title="$encarregado->user->name" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('encarregados.update', $encarregado) }}">@csrf @method('PUT') @include('encarregados._form', ['encarregado' => $encarregado])</form>
    </x-card>
</x-app-layout>
