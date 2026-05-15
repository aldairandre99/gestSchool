<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Event')" />
    <x-card>
        <form method="POST" action="{{ route('eventos.store') }}">@csrf @include('eventos._form', ['evento' => null])</form>
    </x-card>
</x-app-layout>
