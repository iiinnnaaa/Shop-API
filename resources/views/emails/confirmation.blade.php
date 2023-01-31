<x-mail::message>
    Dear {{ $user }}, this is your verification code.Code will expire after <strong>1 hour</strong>.

<x-mail::panel>
        {{ $verification_code }}
</x-mail::panel>

    Regards,
    {{ config('app.name') }} Team
</x-mail::message>
