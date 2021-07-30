@component('mail::message')
# Hello I hope you are in good health 

Here is the registration link For Your Registration request Please 
click the link bellow and it will take you to register page

@php
    $baseurl = env('APP_BASE_API_URL');
    $registerurl = $baseurl.'/register';    
@endphp

@component('mail::button', ['url' =>$registerurl ])
Register Link
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
