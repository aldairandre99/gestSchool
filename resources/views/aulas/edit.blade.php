<x-app-layout>
    <x-page-header :title="'Editar aula — ' . $aula->data->format('d/m/Y')" />
    <x-card>
        <form method="POST" action="{{ route('aulas.update', $aula) }}">@csrf @method('PUT') @include('aulas._form', ['aula' => $aula])</form>
    </x-card>
</x-app-layout>
