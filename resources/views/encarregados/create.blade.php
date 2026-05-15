<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Guardian')" />
    <x-card>
        <form method="POST" action="{{ route('encarregados.store') }}">@csrf @include('encarregados._form', ['encarregado' => null])</form>
    </x-card>
</x-app-layout>
