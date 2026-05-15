<x-app-layout>
    <x-page-header :title="__('Profile')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-card :title="__('Personal data')">
                @include('profile.partials.update-profile-information-form')
            </x-card>

            <x-card :title="__('Change password')">
                @include('profile.partials.update-password-form')
            </x-card>
        </div>

        <x-card :title="__('Delete Account')">
            @include('profile.partials.delete-user-form')
        </x-card>
    </div>
</x-app-layout>
