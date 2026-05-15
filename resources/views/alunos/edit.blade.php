<x-app-layout>
    <x-page-header :title="$aluno->user->name" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('alunos.update', $aluno) }}">@csrf @method('PUT') @include('alunos._form', ['aluno' => $aluno])</form>
    </x-card>
</x-app-layout>
