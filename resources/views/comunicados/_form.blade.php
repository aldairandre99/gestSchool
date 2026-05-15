@php($comunicado = $comunicado ?? null)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <x-input name="titulo" :label="__('Title')" :value="$comunicado?->titulo" required />
    </div>
    <div class="sm:col-span-2">
        <x-textarea name="conteudo" :label="__('Content')" :value="$comunicado?->conteudo" :rows="8" required />
    </div>
    <x-select name="alcance" :label="__('Audience')" required :placeholder="null">
        @foreach(['todos' => __('Everyone'), 'professores' => __('Teachers Only'), 'encarregados' => __('Guardians Only'), 'classe' => __('Specific Class'), 'turma' => __('Specific Group')] as $k => $label)
            <option value="{{ $k }}" @selected(old('alcance', $comunicado?->alcance ?? 'todos') === $k)>{{ $label }}</option>
        @endforeach
    </x-select>
    <x-input name="publicado_em" :label="__('Publish on')" type="datetime-local" :value="$comunicado?->publicado_em?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')" />
    <x-select name="classe_id" label="{{ __('Class (if audience = class)') }}">
        @foreach($classes as $c)<option value="{{ $c->id }}" @selected(old('classe_id', $comunicado?->classe_id) == $c->id)>{{ $c->nome }}</option>@endforeach
    </x-select>
    <x-select name="turma_id" label="{{ __('Group (if audience = group)') }}">
        @foreach($turmas as $t)<option value="{{ $t->id }}" @selected(old('turma_id', $comunicado?->turma_id) == $t->id)>{{ $t->display_label }} — {{ $t->anoLectivo->codigo }}</option>@endforeach
    </x-select>
</div>
<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('comunicados.index')">{{ __('Cancel') }}</x-btn>
</div>
