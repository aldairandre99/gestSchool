<x-error-page
    :code="$exception?->getStatusCode() ?? '5xx'"
    icon="server-crash"
    variant="danger"
    :title="__('Server error')"
    :message="__('Something went wrong on our side. We are working on it.')" />
