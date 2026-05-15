@php
    $user = auth()->user();
    $isAdmin = $user?->hasAnyRole(['director_geral', 'director_pedagogico', 'secretario']);
    $isProf = $user?->hasAnyRole(['professor', 'professor_assistente']);
    $isEnc = $user?->hasRole('encarregado');
@endphp

<aside class="sidebar scroll-thin">
    <nav class="py-4">
        <x-sidebar-link :href="route('dashboard')" icon="layout-dashboard" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-sidebar-link>

        @if($isAdmin)
            <div class="sidebar-section">Pessoas</div>
            <x-sidebar-link :href="route('users.index')" icon="users" :active="request()->routeIs('users.*')">{{ __('Users') }}</x-sidebar-link>
            <x-sidebar-link :href="route('funcionarios.index')" icon="briefcase" :active="request()->routeIs('funcionarios.*')">{{ __('Staff') }}</x-sidebar-link>
            <x-sidebar-link :href="route('professores.index')" icon="user-cog" :active="request()->routeIs('professores.*')">{{ __('Teachers') }}</x-sidebar-link>
            <x-sidebar-link :href="route('alunos.index')" icon="graduation-cap" :active="request()->routeIs('alunos.*')">{{ __('Students') }}</x-sidebar-link>
            <x-sidebar-link :href="route('encarregados.index')" icon="user-check" :active="request()->routeIs('encarregados.*')">{{ __('Guardians') }}</x-sidebar-link>

            <div class="sidebar-section">{{ __('Academic Structure') }}</div>
            <x-sidebar-link :href="route('anos.index')" icon="calendar" :active="request()->routeIs('anos.*')">{{ __('Academic Years') }}</x-sidebar-link>
            <x-sidebar-link :href="route('trimestres.index')" icon="calendar-clock" :active="request()->routeIs('trimestres.*')">{{ __('Terms') }}</x-sidebar-link>
            <x-sidebar-link :href="route('classes.index')" icon="layers" :active="request()->routeIs('classes.*')">{{ __('Classes') }}</x-sidebar-link>
            <x-sidebar-link :href="route('cursos.index')" icon="award" :active="request()->routeIs('cursos.*')">Cursos</x-sidebar-link>
            <x-sidebar-link :href="route('turmas.index')" icon="users-round" :active="request()->routeIs('turmas.*')">{{ __('Class Groups') }}</x-sidebar-link>
            <x-sidebar-link :href="route('disciplinas.index')" icon="book-open" :active="request()->routeIs('disciplinas.*')">{{ __('Subjects List') }}</x-sidebar-link>
            <x-sidebar-link :href="route('matriculas.index')" icon="file-text" :active="request()->routeIs('matriculas.*')">{{ __('Enrollments') }}</x-sidebar-link>
            <x-sidebar-link :href="route('atribuicoes.index')" icon="link" :active="request()->routeIs('atribuicoes.*')">{{ __('Assignments') }}</x-sidebar-link>
        @endif

        @if($isAdmin || $isProf)
            <div class="sidebar-section">{{ __('Operation') }}</div>
            <x-sidebar-link :href="route('aulas.index')" icon="clipboard-check" :active="request()->routeIs('aulas.*') || request()->routeIs('presencas.*')">
                Aulas / {{ __('Attendance') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('avaliacoes.index')" icon="clipboard-list" :active="request()->routeIs('avaliacoes.*') || request()->routeIs('notas.*')">
                {{ __('Evaluations') }}
            </x-sidebar-link>
            <x-sidebar-link :href="route('pautas.index')" icon="table-2" :active="request()->routeIs('pautas.*')">
                {{ __('Gradebook') }}
            </x-sidebar-link>
        @endif

        @if($isEnc)
            <div class="sidebar-section">{{ __('My Children') }}</div>
            <x-sidebar-link :href="route('meus-educandos.index')" icon="users-round" :active="request()->routeIs('meus-educandos.*')">
                {{ __('My Children') }}
            </x-sidebar-link>
        @endif

        <div class="sidebar-section">{{ __('Communication') }}</div>
        <x-sidebar-link :href="route('comunicados.index')" icon="megaphone" :active="request()->routeIs('comunicados.*')">
            {{ __('Announcements') }}
        </x-sidebar-link>
    </nav>
</aside>
