<?php

namespace App\Support\Sms;

interface SmsGatewayInterface
{
    /**
     * Send a plain-text SMS to a normalized Bangladeshi phone number
     * (11 digits, starting with 0 — see App\Support\Phone::normalizeBd).
     * Returns true on a successful hand-off to the gateway, false otherwise.
     * Must never throw — failures are logged and reported via the return value.
     */
    public function send(string $phone, string $message): bool;
}
