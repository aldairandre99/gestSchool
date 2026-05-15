<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Evaluations')" />
    <x-card>
        <form method="POST" action="{{ route('avaliacoes.store') }}">@csrf @include('avaliacoes._form', ['avaliacao' => null])</form>
    </x-card>
</x-app-layout>
