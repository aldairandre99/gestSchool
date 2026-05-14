<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <x-flash />

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <a href="{{ route('users.index') }}" class="bg-white rounded-lg shadow p-5 hover:shadow-md transition">
                    <div class="text-xs uppercase text-gray-500">{{ __('Users') }}</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['users'] }}</div>
                </a>
                <a href="{{ route('funcionarios.index') }}" class="bg-white rounded-lg shadow p-5 hover:shadow-md transition">
                    <div class="text-xs uppercase text-gray-500">{{ __('Staff') }}</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['funcionarios'] }}</div>
                </a>
                <a href="{{ route('professores.index') }}" class="bg-white rounded-lg shadow p-5 hover:shadow-md transition">
                    <div class="text-xs uppercase text-gray-500">{{ __('Teachers') }}</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['professores'] }}</div>
                </a>
                <a href="{{ route('alunos.index') }}" class="bg-white rounded-lg shadow p-5 hover:shadow-md transition">
                    <div class="text-xs uppercase text-gray-500">{{ __('Students') }}</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['alunos'] }}</div>
                </a>
                <a href="{{ route('encarregados.index') }}" class="bg-white rounded-lg shadow p-5 hover:shadow-md transition">
                    <div class="text-xs uppercase text-gray-500">{{ __('Guardians') }}</div>
                    <div class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['encarregados'] }}</div>
                </a>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-800 mb-2">{{ __('School Management') }}</h3>
                <p class="text-sm text-gray-600">{{ __('Welcome') }}, {{ Auth::user()->name }}.</p>
            </div>
        </div>
    </div>
</x-app-layout>
