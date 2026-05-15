<x-app-layout>
    <x-page-header :title="$trimestre->numero . 'º ' . __('Term')" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('trimestres.update', $trimestre) }}">@csrf @method('PUT') @include('trimestres._form', ['trimestre' => $trimestre])</form>
    </x-card>
</x-app-layout>
