<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Report Card') }} — {{ $matricula->aluno->user->name }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                <div><dt class="text-gray-500 text-xs">{{ __('Student') }}</dt><dd class="font-medium">{{ $matricula->aluno->user->name }}</dd></div>
                <div><dt class="text-gray-500 text-xs">{{ __('Class Groups') }}</dt><dd>{{ $matricula->turma->classe->nome }} {{ $matricula->turma->nome }}</dd></div>
                <div><dt class="text-gray-500 text-xs">{{ __('School Year') }}</dt><dd>{{ $matricula->anoLectivo->codigo }}</dd></div>
            </div>
            <table class="min-w-full text-sm">
                <thead class="text-left text-gray-500 border-b">
                    <tr>
                        <th class="py-2 pr-3">{{ __('Subjects List') }}</th>
                        @foreach($trimestres as $t)<th class="py-2 pr-3 text-center">{{ $t->numero }}º {{ __('Term') }}</th>@endforeach
                        <th class="py-2 pr-3 text-center font-semibold">{{ __('Annual Average') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medias as $info)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-3 font-medium">{{ $info['nome'] }}</td>
                            @foreach($trimestres as $t)
                                @php($m = $info['trimestres'][$t->id] ?? null)
                                <td class="py-2 pr-3 text-center {{ $m !== null && $m < 10 ? 'text-red-600' : '' }}">{{ $m !== null ? $m : '—' }}</td>
                            @endforeach
                            <td class="py-2 pr-3 text-center font-semibold {{ ($info['anual'] ?? null) !== null && $info['anual'] < 10 ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $info['anual'] !== null ? $info['anual'] : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ 2 + count($trimestres) }}" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-6 flex gap-3">
                <a href="javascript:print()" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">Imprimir</a>
                <a href="{{ url()->previous() }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a>
            </div>
        </div>
    </div></div>
</x-app-layout>
