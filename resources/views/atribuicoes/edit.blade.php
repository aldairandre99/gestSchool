<x-app-layout>
    <x-page-header :title="__('Edit') . ' — ' . __('Assignment')" />
    <x-card>
        <form method="POST" action="{{ route('atribuicoes.update', $atribuicao) }}">@csrf @method('PUT') @include('atribuicoes._form', ['atribuicao' => $atribuicao])</form>
    </x-card>
</x-app-layout>
