@component('mail::message')
# Escalation Alert

{{ $messageText }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
