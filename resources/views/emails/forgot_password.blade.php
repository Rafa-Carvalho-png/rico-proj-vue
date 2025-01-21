<x-mail::message>
# Forgot Password

We received a request to reset your password. Click the link below to choose a new one:

<x-mail::button :url="url('/reset-password?token=' . $token)">
Reset Password
</x-mail::button>

If you didn't request this, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
