<x-mail::message>
# Introduction

The body of your message.

Here is your requested OTP {{$this->otp}} OTP expires at {{$this->otp_expire_time}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
