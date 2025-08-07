@component('mail::message')
# Escalation Alert

{{ $messageText }}

@component('mail::button', ['url' => url('/occurrence')])
View Occurrence
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent
