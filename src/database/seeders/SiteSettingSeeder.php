<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'site_name', 'value' => 'Voltfix', 'type' => 'text'],
            ['key' => 'logo', 'value' => null, 'type' => 'image'],
            ['key' => 'hero_image', 'value' => null, 'type' => 'image'],
            ['key' => 'step_1_image', 'value' => null, 'type' => 'image'],
            ['key' => 'step_2_image', 'value' => null, 'type' => 'image'],
            ['key' => 'step_3_image', 'value' => null, 'type' => 'image'],
            ['key' => 'service_tv_image', 'value' => null, 'type' => 'image'],
            ['key' => 'service_hp_image', 'value' => null, 'type' => 'image'],
            ['key' => 'service_laptop_image', 'value' => null, 'type' => 'image'],
            ['key' => 'cta_background_image', 'value' => null, 'type' => 'image'],
        ];

        foreach ($defaults as $setting) {
            SiteSetting::firstOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }
    }
}
