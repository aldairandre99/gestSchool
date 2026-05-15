<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Announcements')" />
    <x-card>
        <form method="POST" action="{{ route('comunicados.store') }}">@csrf @include('comunicados._form', ['comunicado' => null])</form>
    </x-card>
</x-app-layout>
