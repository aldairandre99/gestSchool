<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('New') }} — {{ __('Academic Years') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <form method="POST" action="{{ route('anos.store') }}" class="bg-white shadow rounded-lg p-6">@csrf @include('anos._form', ['ano' => null])</form>
    </div></div>
</x-app-layout>
