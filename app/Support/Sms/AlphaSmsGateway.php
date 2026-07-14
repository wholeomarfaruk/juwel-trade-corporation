<?php

namespace App\Support\Sms;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Alpha SMS (sms.net.bd) HTTP API gateway.
 * Credentials are admin-editable via Site Settings > SMS Gateway, not .env,
 * so they can be rotated without a redeploy.
 */
class AlphaSmsGateway implements SmsGatewayInterface
{
    private const ENDPOINT = 'https://api.sms.net.bd/sendsms';

    public function send(string $phone, string $message): bool
    {
        $apiKey = SiteSetting::get('alpha_sms_api_key');
        $senderId = SiteSetting::get('alpha_sms_sender_id');

        if (!$apiKey) {
            Log::warning('AlphaSmsGateway: send skipped, no API key configured.');
            return false;
        }

        try {
            $response = Http::asForm()->post(self::ENDPOINT, [
                'api_key' => $apiKey,
                'msg'     => $message,
                'to'      => $phone,
                'from'    => $senderId,
            ]);

            if (!$response->successful()) {
                Log::warning('AlphaSmsGateway: send failed', [
                    'phone'  => $phone,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('AlphaSmsGateway: send threw', [
                'phone'   => $phone,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
