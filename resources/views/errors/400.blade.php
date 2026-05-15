<x-error-page
    code="400"
    icon="alert-circle"
    variant="warning"
    :title="__('Bad request')"
    :message="$exception?->getMessage() ?: __('The submitted data is invalid.')" />
