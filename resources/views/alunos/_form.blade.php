@php($aluno = $aluno ?? null)
@php($u = $aluno?->user)
@php($selectedEnc = collect(old('encarregados', $aluno?->encarregados->map(fn($e) => ['id' => $e->id, 'parentesco' => $e->pivot->parentesco, 'principal' => $e->pivot->principal])->all() ?? []))->keyBy('id'))
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div><x-input-label for="name" :value="__('Name')" /><x-text-input id="name" name="name" class="mt-1 block w-full" :value="old('name', $u?->name)" required /></div>
    <div><x-input-label for="numero_processo" :value="__('Process Number')" /><x-text-input id="numero_processo" name="numero_processo" class="mt-1 block w-full" :value="old('numero_processo', $aluno?->numero_processo)" required /></div>
    <div><x-input-label for="email" :value="__('Email')" /><x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $u?->email)" /></div>
    <div><x-input-label for="phone" :value="__('Phone')" /><x-text-input id="phone" name="phone" class="mt-1 block w-full" :value="old('phone', $u?->phone)" /></div>
    <div><x-input-label for="bi" :value="__('BI Number')" /><x-text-input id="bi" name="bi" class="mt-1 block w-full" :value="old('bi', $aluno?->bi)" /></div>
    <div><x-input-label for="data_nascimento" :value="__('Birth Date')" /><x-text-input id="data_nascimento" name="data_nascimento" type="date" class="mt-1 block w-full" :value="old('data_nascimento', $aluno?->data_nascimento?->format('Y-m-d'))" /></div>
    <div>
        <x-input-label for="sexo" :value="__('Gender')" />
        <select id="sexo" name="sexo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            <option value="M" @selected(old('sexo', $aluno?->sexo) === 'M')>{{ __('Male') }}</option>
            <option value="F" @selected(old('sexo', $aluno?->sexo) === 'F')>{{ __('Female') }}</option>
        </select>
    </div>
    <div><x-input-label for="classe" :value="__('Grade')" /><x-text-input id="classe" name="classe" class="mt-1 block w-full" :value="old('classe', $aluno?->classe)" /></div>
    <div><x-input-label for="turma" :value="__('Class')" /><x-text-input id="turma" name="turma" class="mt-1 block w-full" :value="old('turma', $aluno?->turma)" /></div>
    <div><x-input-label for="ano_lectivo" :value="__('School Year')" /><x-text-input id="ano_lectivo" name="ano_lectivo" class="mt-1 block w-full" :value="old('ano_lectivo', $aluno?->ano_lectivo)" placeholder="2026/2027" /></div>
    <div><x-input-label for="nacionalidade" value="Nacionalidade" /><x-text-input id="nacionalidade" name="nacionalidade" class="mt-1 block w-full" :value="old('nacionalidade', $aluno?->nacionalidade ?? 'Angolana')" /></div>
    <div><x-input-label for="naturalidade" value="Naturalidade" /><x-text-input id="naturalidade" name="naturalidade" class="mt-1 block w-full" :value="old('naturalidade', $aluno?->naturalidade)" /></div>
    <div class="sm:col-span-2"><x-input-label for="morada" :value="__('Address')" /><textarea id="morada" name="morada" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('morada', $aluno?->morada) }}</textarea></div>
    <div class="sm:col-span-2"><x-input-label for="observacoes" value="Observações" /><textarea id="observacoes" name="observacoes" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('observacoes', $aluno?->observacoes) }}</textarea></div>
    <div><x-input-label for="password" :value="__('Password')" /><x-text-input id="password" name="password" type="password" class="mt-1 block w-full" /></div>
    <div><x-input-label for="password_confirmation" :value="__('Confirm Password')" /><x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" /></div>
</div>

<div class="mt-6">
    <h3 class="font-medium text-gray-800 text-sm mb-2">{{ __('Guardians of this student') }}</h3>
    <div class="space-y-2 border rounded p-3 bg-gray-50">
        @forelse($encarregados as $enc)
            @php($checked = $selectedEnc->has($enc->id))
            @php($sel = $selectedEnc->get($enc->id))
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 items-center">
                <label class="inline-flex items-center text-sm">
                    <input type="checkbox" name="encarregados[{{ $enc->id }}][id]" value="{{ $enc->id }}" {{ $checked ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="ms-2">{{ $enc->user->name }}</span>
                </label>
                <select name="encarregados[{{ $enc->id }}][parentesco]" class="text-sm border-gray-300 rounded">
                    <option value="pai" @selected(($sel['parentesco'] ?? null) === 'pai')>{{ __('Father') }}</option>
                    <option value="mae" @selected(($sel['parentesco'] ?? null) === 'mae')>{{ __('Mother') }}</option>
                    <option value="tutor" @selected(($sel['parentesco'] ?? null) === 'tutor')>{{ __('Tutor') }}</option>
                    <option value="irmao" @selected(($sel['parentesco'] ?? null) === 'irmao')>{{ __('Sibling') }}</option>
                    <option value="outro" @selected(($sel['parentesco'] ?? null) === 'outro')>{{ __('Other') }}</option>
                </select>
                <label class="inline-flex items-center text-sm">
                    <input type="checkbox" name="encarregados[{{ $enc->id }}][principal]" value="1" {{ ! empty($sel['principal']) ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="ms-2">Principal</span>
                </label>
                <span class="text-xs text-gray-500">{{ $enc->user->email }}</span>
            </div>
        @empty
            <p class="text-sm text-gray-500">{{ __('No records found.') }} <a href="{{ route('encarregados.create') }}" class="text-blue-600 underline">{{ __('Add Guardian') }}</a></p>
        @endforelse
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button>
    <a href="{{ route('alunos.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a>
</div>
