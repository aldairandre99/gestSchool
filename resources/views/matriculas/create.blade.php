<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Enrollment')" />
    <x-card>
        <form method="POST" action="{{ route('matriculas.store') }}">@csrf @include('matriculas._form', ['matricula' => null])</form>
    </x-card>
</x-app-layout>
