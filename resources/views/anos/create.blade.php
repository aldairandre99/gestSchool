<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Academic Years')" />
    <x-card>
        <form method="POST" action="{{ route('anos.store') }}">@csrf @include('anos._form', ['ano' => null])</form>
    </x-card>
</x-app-layout>
