<x-error-page
    code="429"
    icon="zap-off"
    variant="warning"
    :title="__('Too many requests')"
    :message="__('You have made too many requests. Please slow down.')" />
