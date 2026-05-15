<x-app-layout>
    <x-page-header :title="$matricula->numero_matricula" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('matriculas.update', $matricula) }}">@csrf @method('PUT') @include('matriculas._form', ['matricula' => $matricula])</form>
    </x-card>
</x-app-layout>
