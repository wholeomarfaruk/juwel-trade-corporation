<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Seeds default text settings so admin-editable values (like the bKash
     * receiving number shown at checkout) have a sane default instead of
     * being blank until an admin visits Site Settings.
     */
    public function run(): void
    {
        $defaults = [
            'bkash_number' => '01682963493',

            // Contact
            'phone'        => '8801329732724',
            'whatsapp'     => '8801329732724',
            'phone_second' => '',
            'messenger'    => '',
            'email'        => '',

            // Social
            'facebook'  => '',
            'instagram' => '',
            'youtube'   => '',
            'tiktok'    => '',
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::firstOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => 'text']
            );
        }
    }
}
