<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'logo' => 'logo.svg',
            'auth_logo' => 'auth_logo.png',
            'favicon' => 'favicon.svg',
            'TOP_HEADER_STYLE' => '1',
            'MAIN_MENU_STYLE' => '1',
            'CURRENCY_CODE' => 'BDT',
            'CURRENCY_CODE_MIN' => '৳',
            'CURRENCY_ICON' => '৳',
            'COUNTRY_SERVE' => 'Bangladesh',
            'SITE_INFO_PHONE' => '+8801749699156',
            'SITE_INFO_ADDRESS' => 'Dhaka, Bangladesh',
            'shipping_charge' => '60',
            'shipping_charge_out_of_range' => '120',
            'shipping_free_above' => '5000',
            'CHECKOUT_TYPE' => '1',
            'GUEST_CHECKOUT' => '1',
            'is_point' => '0',
            'Default_Point' => '0',
            'facebook' => 'https://facebook.com',
            'instagram' => 'https://instagram.com',
            'youtube' => 'https://youtube.com',
            'whatsapp' => '+8801749699156',
            'g_cod' => '1',
            'g_bkash' => '1',
            'g_nagad' => '1',
        ];

        foreach ($settings as $name => $value) {
            Setting::updateOrCreate(['name' => $name], ['value' => $value]);
        }
    }
}
