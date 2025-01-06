@component('mail::message')
# Rest Your Password

Your otp is Your otp is {{ substr($otp, 0, 3) }},{{ substr($otp, 3, 3) }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
