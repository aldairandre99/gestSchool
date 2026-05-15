<x-app-layout>
    <x-page-header :title="$ano->codigo" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('anos.update', $ano) }}">@csrf @method('PUT') @include('anos._form', ['ano' => $ano])</form>
    </x-card>
</x-app-layout>
