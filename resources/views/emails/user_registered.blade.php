@component('mail::message')
# Welcome {{ $user->name }}

You have been successfully registered on **{{ config('app.name') }}**.

Your login email is: **{{ $user->email }}**  

Please login and update your profile information.

@component('mail::button', ['url' => url('/login')])
Login Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
