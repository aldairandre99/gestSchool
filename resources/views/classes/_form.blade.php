@php($classe = $classe ?? null)
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div><x-input-label for="nome" :value="__('Name')" /><x-text-input id="nome" name="nome" class="mt-1 block w-full" :value="old('nome', $classe?->nome)" required /></div>
    <div><x-input-label for="ordem" :value="__('Order')" /><x-text-input id="ordem" name="ordem" type="number" class="mt-1 block w-full" :value="old('ordem', $classe?->ordem ?? 0)" /></div>
    <div><x-input-label for="nivel" :value="__('Level')" /><x-text-input id="nivel" name="nivel" class="mt-1 block w-full" :value="old('nivel', $classe?->nivel)" /></div>
</div>
<div class="mt-4">
    <x-input-label :value="__('Subjects')" />
    <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-60 overflow-y-auto p-3 border rounded bg-gray-50">
        @php($selected = collect(old('disciplinas', $classe?->disciplinas->pluck('id')->all() ?? [])))
        @foreach($disciplinas as $d)
            <label class="inline-flex items-center text-sm">
                <input type="checkbox" name="disciplinas[]" value="{{ $d->id }}" {{ $selected->contains($d->id) ? 'checked' : '' }} class="rounded border-gray-300">
                <span class="ms-2">{{ $d->nome }}</span>
            </label>
        @endforeach
    </div>
</div>
<div class="mt-6 flex gap-3"><button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button><a href="{{ route('classes.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a></div>
