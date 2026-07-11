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
            'service_kulkas_image'  => $this->imageUrl($settings['service_kulkas_image']),
            'service_tv_image'      => $this->imageUrl($settings['service_tv_image']),
            'service_mesin_cuci_image' => $this->imageUrl($settings['service_mesin_cuci_image']),
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

        return Storage::disk('public')->url($path);
    }
}
