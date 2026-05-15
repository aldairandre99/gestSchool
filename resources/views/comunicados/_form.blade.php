@php($comunicado = $comunicado ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2"><x-input-label for="titulo" :value="__('Title')" /><x-text-input id="titulo" name="titulo" class="mt-1 block w-full" :value="old('titulo', $comunicado?->titulo)" required /></div>
    <div class="sm:col-span-2">
        <x-input-label for="conteudo" :value="__('Content')" />
        <textarea id="conteudo" name="conteudo" rows="8" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ old('conteudo', $comunicado?->conteudo) }}</textarea>
    </div>
    <div>
        <x-input-label for="alcance" :value="__('Audience')" />
        <select id="alcance" name="alcance" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach([
                'todos' => __('Everyone'),
                'professores' => __('Teachers Only'),
                'encarregados' => __('Guardians Only'),
                'classe' => __('Specific Class'),
                'turma' => __('Specific Group'),
            ] as $k => $label)
                <option value="{{ $k }}" @selected(old('alcance', $comunicado?->alcance ?? 'todos') === $k)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div><x-input-label for="publicado_em" :value="__('Publish on')" /><x-text-input id="publicado_em" name="publicado_em" type="datetime-local" class="mt-1 block w-full" :value="old('publicado_em', $comunicado?->publicado_em?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i'))" /></div>
    <div>
        <x-input-label for="classe_id" value="Classe (se alcance = classe)" />
        <select id="classe_id" name="classe_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($classes as $c)<option value="{{ $c->id }}" @selected(old('classe_id', $comunicado?->classe_id) == $c->id)>{{ $c->nome }}</option>@endforeach
        </select>
    </div>
    <div>
        <x-input-label for="turma_id" value="Turma (se alcance = turma)" />
        <select id="turma_id" name="turma_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            @foreach($turmas as $t)<option value="{{ $t->id }}" @selected(old('turma_id', $comunicado?->turma_id) == $t->id)>{{ $t->classe->nome }} {{ $t->nome }} — {{ $t->anoLectivo->codigo }}</option>@endforeach
        </select>
    </div>
</div>
<div class="mt-6 flex gap-3"><button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button><a href="{{ route('comunicados.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a></div>
