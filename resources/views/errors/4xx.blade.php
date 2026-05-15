<x-error-page
    :code="$exception?->getStatusCode() ?? '4xx'"
    icon="alert-triangle"
    variant="warning"
    :title="$exception?->getMessage() ?: __('Unknown error')" />
