@component('mail::message')
    # Email Confirmation

    Please refer to the following link:

    @component('mail::button', ['url' => route('welcome')])
        Verify Email
    @endcomponent
    Thanks
    {{ config('app.name') }}

@endcomponent
