<x-error-page
    code="401"
    icon="lock"
    variant="warning"
    :title="__('Unauthorized')"
    :message="$exception?->getMessage() ?: __('You need to sign in to continue.')" />
