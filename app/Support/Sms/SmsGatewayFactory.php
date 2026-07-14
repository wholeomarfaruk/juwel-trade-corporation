<?php

namespace App\Support\Sms;

use App\Models\SiteSetting;

class SmsGatewayFactory
{
    public static function make(): SmsGatewayInterface
    {
        return match (SiteSetting::get('sms_gateway', 'alpha')) {
            'alpha' => new AlphaSmsGateway(),
            default => new AlphaSmsGateway(),
        };
    }
}
