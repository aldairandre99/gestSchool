@php($aluno = $aluno ?? null)
@php($u = $aluno?->user)
@php($selectedEnc = collect(old('encarregados', $aluno?->encarregados->map(fn($e) => ['id' => $e->id, 'parentesco' => $e->pivot->parentesco, 'principal' => $e->pivot->principal])->all() ?? []))->keyBy('id'))

<div class="card-section">
    <h4 class="card-title">Dados pessoais</h4>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-input name="name" :label="__('Name')" :value="$u?->name" required />
        <x-input name="numero_processo" :label="__('Process Number')" :value="$aluno?->numero_processo" required />
        <x-input name="email" :label="__('Email')" type="email" :value="$u?->email" />
        <x-input name="phone" :label="__('Phone')" :value="$u?->phone" />
        <x-input name="bi" :label="__('BI Number')" :value="$aluno?->bi" />
        <x-input name="data_nascimento" :label="__('Birth Date')" type="date" :value="$aluno?->data_nascimento?->format('Y-m-d')" />
        <x-select name="sexo" :label="__('Gender')">
            <option value="M" @selected(old('sexo', $aluno?->sexo) === 'M')>{{ __('Male') }}</option>
            <option value="F" @selected(old('sexo', $aluno?->sexo) === 'F')>{{ __('Female') }}</option>
        </x-select>
        <x-input name="nacionalidade" label="Nacionalidade" :value="$aluno?->nacionalidade ?? 'Angolana'" />
        <x-input name="naturalidade" label="Naturalidade" :value="$aluno?->naturalidade" />
        <div class="sm:col-span-2"><x-textarea name="morada" :label="__('Address')" :value="$aluno?->morada" :rows="2" /></div>
    </div>
</div>

<div class="card-section">
    <h4 class="card-title">Dados académicos</h4>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <x-input name="classe" :label="__('Grade')" :value="$aluno?->classe" />
        <x-input name="turma" :label="__('Class')" :value="$aluno?->turma" />
        <x-input name="ano_lectivo" :label="__('School Year')" :value="$aluno?->ano_lectivo" placeholder="2026/2027" />
        <div class="sm:col-span-3"><x-textarea name="observacoes" label="Observações" :value="$aluno?->observacoes" :rows="2" /></div>
    </div>
</div>

<div class="card-section">
    <h4 class="card-title">{{ __('Guardians of this student') }}</h4>
    <div class="space-y-2">
        @forelse($encarregados as $enc)
            @php($checked = $selectedEnc->has($enc->id))
            @php($sel = $selectedEnc->get($enc->id))
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-center border border-gray-100 rounded p-3 bg-gray-50">
                <label class="form-check">
                    <input type="checkbox" name="encarregados[{{ $enc->id }}][id]" value="{{ $enc->id }}" {{ $checked ? 'checked' : '' }}>
                    <span class="text-navy font-semibold">{{ $enc->user->name }}</span>
                </label>
                <select name="encarregados[{{ $enc->id }}][parentesco]" class="form-select text-xs">
                    <option value="pai" @selected(($sel['parentesco'] ?? null) === 'pai')>{{ __('Father') }}</option>
                    <option value="mae" @selected(($sel['parentesco'] ?? null) === 'mae')>{{ __('Mother') }}</option>
                    <option value="tutor" @selected(($sel['parentesco'] ?? null) === 'tutor')>{{ __('Tutor') }}</option>
                    <option value="irmao" @selected(($sel['parentesco'] ?? null) === 'irmao')>{{ __('Sibling') }}</option>
                    <option value="outro" @selected(($sel['parentesco'] ?? null) === 'outro')>{{ __('Other') }}</option>
                </select>
                <label class="form-check">
                    <input type="checkbox" name="encarregados[{{ $enc->id }}][principal]" value="1" {{ ! empty($sel['principal']) ? 'checked' : '' }}>
                    <span>Principal</span>
                </label>
                <span class="text-xs text-muted">{{ $enc->user->email }}</span>
            </div>
        @empty
            <x-empty title="Sem encarregados">
                <x-btn variant="primary" size="sm" :href="route('encarregados.create')">{{ __('Add Guardian') }}</x-btn>
            </x-empty>
        @endforelse
    </div>
</div>

<div class="card-section">
    <h4 class="card-title">Acesso (opcional)</h4>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-input name="password" :label="__('Password')" type="password" />
        <x-input name="password_confirmation" :label="__('Confirm Password')" type="password" />
    </div>
</div>

<div class="flex items-center gap-3 mt-6">
    <x-btn variant="primary" type="submit">{{ __('Save') }}</x-btn>
    <x-btn variant="secondary" :href="route('alunos.index')">{{ __('Cancel') }}</x-btn>
</div>
