@component('mail::message')

You are successfully subscribed to the {{$plan->name}} plan. <br> please use the following link to create your password:
<br> {{ $reset_url }} <br>.

@component('mail::button', ['url' => $reset_url])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
