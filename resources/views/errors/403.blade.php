<x-error-page
    code="403"
    icon="shield-off"
    variant="danger"
    :title="__('Forbidden')"
    :message="$exception?->getMessage() ?: __('You do not have permission to view this resource.')" />
