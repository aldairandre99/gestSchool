<x-app-layout>
    <x-page-header :title="__('Schedules')" :subtitle="__('Choose a class group or a teacher to see the schedule')">
        @hasanyrole('director_geral|director_pedagogico|secretario')
            <x-slot name="actions">
                <x-btn variant="primary" icon="plus" :href="route('horarios.create')">{{ __('New') }}</x-btn>
            </x-slot>
        @endhasanyrole
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card :title="__('Schedule by class group')">
            <p class="text-sm text-muted mb-4">{{ __('Weekly grid view of one class group.') }}</p>
            <form x-data="{ turma: '' }"
                  x-on:submit.prevent="if (turma) window.location = '/horarios/turma/' + turma">
                <div class="form-group">
                    <label class="form-label">{{ __('Class Groups') }}</label>
                    <select x-model="turma" class="form-select">
                        <option value="">—</option>
                        @foreach($turmas as $t)<option value="{{ $t->id }}">{{ $t->display_label }}</option>@endforeach
                    </select>
                </div>
                <x-btn variant="primary" type="submit" icon="calendar-days">{{ __('Open schedule') }}</x-btn>
            </form>
        </x-card>

        <x-card :title="__('Schedule by teacher')">
            <p class="text-sm text-muted mb-4">{{ __('Weekly grid view of one teacher.') }}</p>
            <form x-data="{ professor: '' }"
                  x-on:submit.prevent="if (professor) window.location = '/horarios/professor/' + professor">
                <div class="form-group">
                    <label class="form-label">{{ __('Teacher') }}</label>
                    <select x-model="professor" class="form-select">
                        <option value="">—</option>
                        @foreach($professores as $p)<option value="{{ $p->id }}">{{ $p->user->name }}</option>@endforeach
                    </select>
                </div>
                <x-btn variant="primary" type="submit" icon="user-cog">{{ __('Open schedule') }}</x-btn>
            </form>
        </x-card>
    </div>
</x-app-layout>
