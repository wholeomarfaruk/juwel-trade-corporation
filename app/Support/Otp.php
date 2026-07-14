<?php

namespace App\Support;

use App\Models\OtpCode;
use App\Support\Sms\SmsGatewayFactory;

class Otp
{
    private const PURPOSE_LOGIN = 'login';
    private const EXPIRY_MINUTES = 5;
    private const RESEND_COOLDOWN_SECONDS = 60;
    private const MAX_ATTEMPTS = 5;

    /**
     * Generate a fresh code for this phone and send it via the configured
     * SMS gateway. Returns false without sending if a code was already sent
     * within the resend cooldown window (caller should surface this as
     * "please wait before requesting another code").
     */
    public static function generateAndSend(string $phone): bool
    {
        $latest = OtpCode::where('phone', $phone)
            ->where('purpose', self::PURPOSE_LOGIN)
            ->latest()
            ->first();

        if ($latest && $latest->created_at->diffInSeconds(now()) < self::RESEND_COOLDOWN_SECONDS) {
            return false;
        }

        $code = (string) random_int(100000, 999999);

        OtpCode::create([
            'phone'      => $phone,
            'code'       => $code,
            'purpose'    => self::PURPOSE_LOGIN,
            'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
        ]);

        SmsGatewayFactory::make()->send($phone, "Your Juwel Trade Corporation verification code is {$code}. It expires in " . self::EXPIRY_MINUTES . ' minutes.');

        return true;
    }

    /**
     * Verify a submitted code for this phone. Consumes the code on success
     * so it cannot be reused. Locks out further attempts on this code once
     * MAX_ATTEMPTS is exceeded.
     */
    public static function verify(string $phone, string $code): bool
    {
        $otp = OtpCode::where('phone', $phone)
            ->where('purpose', self::PURPOSE_LOGIN)
            ->whereNull('consumed_at')
            ->latest()
            ->first();

        if (!$otp || $otp->isExpired() || $otp->attempts >= self::MAX_ATTEMPTS) {
            return false;
        }

        $otp->increment('attempts');

        if (!hash_equals($otp->code, $code)) {
            return false;
        }

        $otp->consumed_at = now();
        $otp->save();

        return true;
    }
}
