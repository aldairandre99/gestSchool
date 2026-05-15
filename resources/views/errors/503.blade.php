@php($msg = (isset($exception) && $exception?->getMessage()) ? $exception->getMessage() : __('We are temporarily down for maintenance. Please come back shortly.'))
<x-error-page
    code="503"
    icon="hard-hat"
    variant="warning"
    :title="__('Service unavailable')"
    :message="$msg" />
