<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPageContent extends Model
{
    protected $guarded = ['id'];

    /**
     * Where landing-page image uploads are stored (under public/).
     */
    public const IMAGE_DIR = 'uploads/landing';

    /**
     * Single source of truth for both the public views and the admin editor:
     * which pages exist and which media slots each one exposes.
     *
     * Keep the outer keys in sync with the {page} route parameter and page_slug.
     */
    public static function pages(): array
    {
        return [
            'lice-comb' => [
                'label' => 'Lice Comb Landing',
                'route' => 'landing.lice-comb',
                'fields' => [
                    'hero_image' => [
                        'type' => 'image',
                        'section' => 'Hero',
                        'label' => 'Hero product image',
                        'help' => 'Main product photo shown at the top of the page (replaces the “প্রোডাক্টের হিরো ছবি” placeholder).',
                    ],
                    'demo_video' => [
                        'type' => 'youtube_url',
                        'section' => 'Demo Video ("লাইভ ব্যবহার দেখুন")',
                        'label' => 'Demo video — YouTube URL',
                        'help' => 'The “live use” demo video. Paste a YouTube link (watch?v=…, youtu.be/…, or embed/…).',
                    ],
                ],
            ],
            'rust-removals' => [
                'label' => 'Rust Removal Landing',
                'route' => 'landing.rust-removals',
                'fields' => [
                    'before_image' => [
                        'type' => 'image',
                        'section' => 'Before / After ("প্রয়োগের আগে ও পরে")',
                        'label' => 'Before image (rusted rod)',
                        'help' => 'Left photo in the before/after comparison.',
                    ],
                    'after_image' => [
                        'type' => 'image',
                        'section' => 'Before / After ("প্রয়োগের আগে ও পরে")',
                        'label' => 'After image (protected rod)',
                        'help' => 'Right photo in the before/after comparison.',
                    ],
                ],
            ],
        ];
    }

    /**
     * Return the config for a page, or null if the slug is unknown.
     */
    public static function pageConfig(string $page): ?array
    {
        return static::pages()[$page] ?? null;
    }

    /**
     * All stored values for a page, keyed by section_key.
     */
    public static function forPage(string $page): array
    {
        return static::where('page_slug', $page)
            ->pluck('value', 'section_key')
            ->toArray();
    }

    /**
     * Public URL for a stored image filename, or null when empty.
     */
    public static function imageUrl(?string $filename): ?string
    {
        return $filename ? asset(self::IMAGE_DIR.'/'.$filename) : null;
    }

    /**
     * Extract the 11-char video id from any common YouTube URL format.
     * Returns null when the URL is empty or not a recognisable YouTube link.
     */
    public static function youtubeId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $pattern = '~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~';

        return preg_match($pattern, $url, $m) ? $m[1] : null;
    }
}
