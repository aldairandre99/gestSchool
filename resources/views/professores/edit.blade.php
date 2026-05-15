<x-app-layout>
    <x-page-header :title="$professor->user->name" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('professores.update', $professor) }}">@csrf @method('PUT') @include('professores._form', ['professor' => $professor])</form>
    </x-card>
</x-app-layout>
