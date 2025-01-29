<x-mail::message>
# Introduction

<h2>Hello,</h2>

A new request as been made for template {{$templateName}}({{$templateId}}) from {{$sendername}} with an email {{$email}}


{{-- Thanks,<br> --}}
{{ config('app.name') }}
</x-mail::message>
