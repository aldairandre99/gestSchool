<x-app-layout>
    <x-page-header :title="__('My Children')" />

    <x-card>
        @if($alunos->isEmpty())
            <x-empty title="{{ __('No linked students') }}" />
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($alunos as $aluno)
                    <a href="{{ route('meus-educandos.show', $aluno) }}" class="block border border-gray-100 hover:border-primary hover:shadow-card-hover rounded p-5 transition">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-primary-soft text-primary inline-flex items-center justify-center font-semibold text-sm">
                                {{ collect(explode(' ', $aluno->user->name))->take(2)->map(fn($w) => substr($w, 0, 1))->join('') }}
                            </span>
                            <div>
                                <div class="font-semibold text-navy">{{ $aluno->user->name }}</div>
                                <div class="text-xs text-muted font-mono">{{ $aluno->numero_processo }}</div>
                            </div>
                        </div>
                        <div class="mt-3 text-xs text-muted flex items-center gap-3">
                            <span>{{ __('Grade') }}: {{ $aluno->classe ?? '—' }}</span>
                            <span>{{ __('Class') }}: {{ $aluno->turma ?? '—' }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </x-card>
</x-app-layout>
