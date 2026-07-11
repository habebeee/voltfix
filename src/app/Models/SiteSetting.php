<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    public static function get(string $key, ?string $default = null): ?string
    {
        return Cache::rememberForever("site_setting_{$key}", function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function set(string $key, ?string $value, string $type = 'text'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );

        Cache::forget("site_setting_{$key}");
        Cache::forget('site_settings_home');
    }

    public static function getImageUrl(string $key): ?string
    {
        $path = static::get($key);

        if (! $path) {
            return null;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    /** Prefer relative storage URL for multi-device access */
    public static function getPublicStorageUrl(string $key): ?string
    {
        $path = static::get($key);

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

    public static function getHomeSettings(): array
    {
        return Cache::rememberForever('site_settings_home', function () {
            $keys = [
                'site_name',
                'logo',
                'hero_image',
                'step_1_image',
                'step_2_image',
                'step_3_image',
                'service_tv_image',
                'service_hp_image',
                'service_laptop_image',
                'cta_background_image',
            ];

            $settings = static::whereIn('key', $keys)->pluck('value', 'key')->toArray();

            return array_merge(array_fill_keys($keys, null), $settings);
        });
    }
}
