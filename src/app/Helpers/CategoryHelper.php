<?php

namespace App\Helpers;

class CategoryHelper
{
    public const ALL = ['TV', 'HP', 'LAPTOP'];

    public static function label(string|null $category): string
    {
        return match ($category) {
            'TV'     => 'TV / Monitor',
            'HP'     => 'HP / Smartphone',
            'LAPTOP' => 'Laptop',
            default  => $category ?? '—',
        };
    }

    public static function shortLabel(string|null $category): string
    {
        return match ($category) {
            'TV'     => 'TV',
            'HP'     => 'HP',
            'LAPTOP' => 'Laptop',
            default  => $category ?? '—',
        };
    }

    public static function specialistLabel(string|null $category): string
    {
        return match ($category) {
            'TV'     => 'Spesialis TV & Monitor',
            'HP'     => 'Spesialis HP',
            'LAPTOP' => 'Spesialis Laptop',
            default  => $category ?? '—',
        };
    }

    public static function emoji(string|null $category): string
    {
        return match ($category) {
            'TV'     => '📺',
            'HP'     => '📱',
            'LAPTOP' => '💻',
            default  => '🔧',
        };
    }

    public static function filamentColor(string|null $category): string
    {
        return match ($category) {
            'TV'     => 'success',
            'HP'     => 'warning',
            'LAPTOP' => 'info',
            default  => 'gray',
        };
    }

    public static function filamentBadge(string|null $category): string
    {
        if (blank($category)) {
            return '—';
        }

        return self::emoji($category) . ' ' . self::shortLabel($category);
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return collect(self::ALL)
            ->mapWithKeys(fn (string $cat) => [$cat => self::label($cat)])
            ->all();
    }

    /** @return array{emoji: string, bg: string, text: string} */
    public static function meta(string $category): array
    {
        return match ($category) {
            'TV' => ['emoji' => '📺', 'bg' => 'bg-violet-50', 'text' => 'text-violet-600'],
            'HP' => ['emoji' => '📱', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
            'LAPTOP' => ['emoji' => '💻', 'bg' => 'bg-sky-50', 'text' => 'text-sky-600'],
            default => ['emoji' => '🔧', 'bg' => 'bg-gray-50', 'text' => 'text-gray-600'],
        };
    }

    /** @return array{bg: string, border: string, text: string} */
    public static function detailColors(string $category): array
    {
        return match ($category) {
            'TV' => ['bg' => '#F5F3FF', 'border' => '#DDD6FE', 'text' => '#6D28D9'],
            'HP' => ['bg' => '#FFFBEB', 'border' => '#FDE68A', 'text' => '#B45309'],
            'LAPTOP' => ['bg' => '#F0F9FF', 'border' => '#BAE6FD', 'text' => '#0369A1'],
            default => ['bg' => '#F9FAFB', 'border' => '#E5E7EB', 'text' => '#374151'],
        };
    }
}
