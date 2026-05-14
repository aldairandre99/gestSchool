<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @role('director_geral|director_pedagogico|secretario')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            {{ __('Users') }}
                        </x-nav-link>
                        <x-nav-link :href="route('funcionarios.index')" :active="request()->routeIs('funcionarios.*')">
                            {{ __('Staff') }}
                        </x-nav-link>
                        <x-nav-link :href="route('professores.index')" :active="request()->routeIs('professores.*')">
                            {{ __('Teachers') }}
                        </x-nav-link>
                        <x-nav-link :href="route('alunos.index')" :active="request()->routeIs('alunos.*')">
                            {{ __('Students') }}
                        </x-nav-link>
                        <x-nav-link :href="route('encarregados.index')" :active="request()->routeIs('encarregados.*')">
                            {{ __('Guardians') }}
                        </x-nav-link>
                    @endrole

                    @role('professor|professor_assistente')
                        <x-nav-link :href="route('meus-alunos.index')" :active="request()->routeIs('meus-alunos.*')">
                            {{ __('Students') }}
                        </x-nav-link>
                    @endrole

                    @role('encarregado')
                        <x-nav-link :href="route('meus-educandos.index')" :active="request()->routeIs('meus-educandos.*')">
                            {{ __('My Children') }}
                        </x-nav-link>
                    @endrole
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-2">
                <div class="flex items-center text-xs gap-1">
                    <a href="{{ route('locale.switch', 'pt') }}"
                       class="px-2 py-1 rounded {{ app()->getLocale() === 'pt' ? 'bg-gray-800 text-white' : 'text-gray-500 hover:text-gray-700' }}">PT</a>
                    <a href="{{ route('locale.switch', 'en') }}"
                       class="px-2 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-gray-800 text-white' : 'text-gray-500 hover:text-gray-700' }}">EN</a>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 text-xs text-gray-500">
                            @foreach(Auth::user()->roles->pluck('name') as $r)
                                <span class="inline-block bg-gray-100 rounded px-2 py-0.5 mr-1">{{ str_replace('_', ' ', $r) }}</span>
                            @endforeach
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @role('director_geral|director_pedagogico|secretario')
                <x-responsive-nav-link :href="route('users.index')">{{ __('Users') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('funcionarios.index')">{{ __('Staff') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('professores.index')">{{ __('Teachers') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('alunos.index')">{{ __('Students') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('encarregados.index')">{{ __('Guardians') }}</x-responsive-nav-link>
            @endrole
            @role('professor|professor_assistente')
                <x-responsive-nav-link :href="route('meus-alunos.index')">{{ __('Students') }}</x-responsive-nav-link>
            @endrole
            @role('encarregado')
                <x-responsive-nav-link :href="route('meus-educandos.index')">{{ __('My Children') }}</x-responsive-nav-link>
            @endrole
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <div class="px-4 text-xs flex gap-2">
                    <a href="{{ route('locale.switch', 'pt') }}" class="px-2 py-1 rounded {{ app()->getLocale() === 'pt' ? 'bg-gray-800 text-white' : 'bg-gray-100' }}">PT</a>
                    <a href="{{ route('locale.switch', 'en') }}" class="px-2 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-gray-800 text-white' : 'bg-gray-100' }}">EN</a>
                </div>
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
