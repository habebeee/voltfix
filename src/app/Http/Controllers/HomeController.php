<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getHomeSettings();

        $images = [
            'logo'                  => $this->imageUrl($settings['logo']),
            'hero_image'            => $this->imageUrl($settings['hero_image']),
            'step_1_image'          => $this->imageUrl($settings['step_1_image']),
            'step_2_image'          => $this->imageUrl($settings['step_2_image']),
            'step_3_image'          => $this->imageUrl($settings['step_3_image']),
            'service_tv_image'       => $this->imageUrl($settings['service_tv_image']),
            'service_hp_image'       => $this->imageUrl($settings['service_hp_image']),
            'service_laptop_image'   => $this->imageUrl($settings['service_laptop_image']),
            'cta_background_image'  => $this->imageUrl($settings['cta_background_image']),
        ];

        return view('welcome', [
            'siteName' => $settings['site_name'] ?? 'Voltfix',
            'images'   => $images,
        ]);
    }

    private function imageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $path = trim($path);
        if (str_starts_with($path, '[')) {
            $decoded = json_decode($path, true);
            $path = is_array($decoded) ? (string) ($decoded[0] ?? '') : $path;
        }
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        if ($path === '' || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return '/storage/' . $path;
    }
}
