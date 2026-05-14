@php($professor = $professor ?? null)
@php($u = $professor?->user)
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div><x-input-label for="name" :value="__('Name')" /><x-text-input id="name" name="name" class="mt-1 block w-full" :value="old('name', $u?->name)" required /></div>
    <div><x-input-label for="email" :value="__('Email')" /><x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $u?->email)" required /></div>
    <div><x-input-label for="phone" :value="__('Phone')" /><x-text-input id="phone" name="phone" class="mt-1 block w-full" :value="old('phone', $u?->phone)" /></div>
    <div><x-input-label for="numero_professor" :value="__('Process Number')" /><x-text-input id="numero_professor" name="numero_professor" class="mt-1 block w-full" :value="old('numero_professor', $professor?->numero_professor)" /></div>
    <div><x-input-label for="bi" :value="__('BI Number')" /><x-text-input id="bi" name="bi" class="mt-1 block w-full" :value="old('bi', $professor?->bi)" /></div>
    <div><x-input-label for="data_nascimento" :value="__('Birth Date')" /><x-text-input id="data_nascimento" name="data_nascimento" type="date" class="mt-1 block w-full" :value="old('data_nascimento', $professor?->data_nascimento?->format('Y-m-d'))" /></div>
    <div>
        <x-input-label for="sexo" :value="__('Gender')" />
        <select id="sexo" name="sexo" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">—</option>
            <option value="M" @selected(old('sexo', $professor?->sexo) === 'M')>{{ __('Male') }}</option>
            <option value="F" @selected(old('sexo', $professor?->sexo) === 'F')>{{ __('Female') }}</option>
        </select>
    </div>
    <div><x-input-label for="especialidade" :value="__('Subjects')" /><x-text-input id="especialidade" name="especialidade" class="mt-1 block w-full" :value="old('especialidade', $professor?->especialidade)" /></div>
    <div class="sm:col-span-2"><x-input-label for="habilitacoes" :value="__('Qualification')" /><x-text-input id="habilitacoes" name="habilitacoes" class="mt-1 block w-full" :value="old('habilitacoes', $professor?->habilitacoes)" /></div>
    <div class="sm:col-span-2"><x-input-label for="disciplinas" :value="__('Subjects')" /><textarea id="disciplinas" name="disciplinas" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('disciplinas', $professor?->disciplinas) }}</textarea></div>
    <div><x-input-label for="data_admissao" :value="__('Hire Date')" /><x-text-input id="data_admissao" name="data_admissao" type="date" class="mt-1 block w-full" :value="old('data_admissao', $professor?->data_admissao?->format('Y-m-d'))" /></div>
    <div class="flex items-end">
        <label class="inline-flex items-center text-sm">
            <input type="hidden" name="assistente" value="0">
            <input type="checkbox" name="assistente" value="1" {{ old('assistente', $professor?->assistente) ? 'checked' : '' }} class="rounded border-gray-300">
            <span class="ms-2">{{ __('Assistant Teacher') }}</span>
        </label>
    </div>
    <div class="sm:col-span-2"><x-input-label for="morada" :value="__('Address')" /><textarea id="morada" name="morada" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('morada', $professor?->morada) }}</textarea></div>
    <div><x-input-label for="password" :value="__('Password')" /><x-text-input id="password" name="password" type="password" class="mt-1 block w-full" /></div>
    <div><x-input-label for="password_confirmation" :value="__('Confirm Password')" /><x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" /></div>
</div>
<div class="mt-6 flex items-center gap-3">
    <button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save') }}</button>
    <a href="{{ route('professores.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Cancel') }}</a>
</div>
