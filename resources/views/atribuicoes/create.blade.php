<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Assignment')" />
    <x-card>
        <form method="POST" action="{{ route('atribuicoes.store') }}">@csrf @include('atribuicoes._form', ['atribuicao' => null])</form>
    </x-card>
</x-app-layout>
