<x-error-page
    code="419"
    icon="clock"
    variant="warning"
    :title="__('Page expired')"
    :message="__('Your session expired due to inactivity. Please try again.')">

    <a href="{{ url()->previous() }}" class="btn-link">
        <x-lucide-refresh-cw class="w-4 h-4" />
        {{ __('Refresh page') }}
    </a>
</x-error-page>
