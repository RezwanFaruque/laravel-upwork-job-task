@component('mail::message')
# Here is your 6 Digit Pin number 

Your 6 Digit pin number is {{$pinnumber}}
Click the buttion bellow and then confirm confirm with this number 
@php
    $baseurl = env('APP_BASE_API_URL');
    $confirmpin = $baseurl.'/confirmpin';
@endphp

@component('mail::button', ['url' => $confirmpin])
 Confirm Pin
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
