<?php

namespace App\Support;

class Phone
{
    /**
     * Normalize a Bangladeshi phone number: strip everything but digits,
     * drop a leading "88" country code, and require the result to be
     * exactly 11 digits starting with "0" (e.g. 017XXXXXXXX). Returns null
     * if the input can't be normalized to that shape.
     */
    public static function normalizeBd(?string $raw): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $raw);

        if (str_starts_with($digits, '88') && strlen($digits) === 13) {
            $digits = substr($digits, 2);
        }

        if (strlen($digits) === 11 && $digits[0] === '0') {
            return $digits;
        }

        return null;
    }
}
