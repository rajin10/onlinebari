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
                    'product_slug' => [
                        'type' => 'text',
                        'section' => 'Order Buttons',
                        'label' => 'Buy-now product slug',
                        'help' => 'Every “অর্ডার করুন” button sends the customer straight to this product’s checkout. Use the product slug, e.g. ukun-dur-korar-bishesh-chiruni-1.',
                        'default' => 'ukun-dur-korar-bishesh-chiruni-1',
                    ],
                ],
            ],
            'rust-removals' => [
                'label' => 'Rust Removal Landing',
                'route' => 'landing.rust-removals',
                'fields' => [
                    'hero_before_image' => [
                        'type' => 'image',
                        'section' => 'Hero (top before / after)',
                        'label' => 'Hero — before image (rusted)',
                        'help' => 'Left panel of the top hero comparison. Falls back to the warning icon when empty.',
                    ],
                    'hero_after_image' => [
                        'type' => 'image',
                        'section' => 'Hero (top before / after)',
                        'label' => 'Hero — after image (protected)',
                        'help' => 'Right panel of the top hero comparison. Falls back to the shield icon when empty.',
                    ],
                    'before_image' => [
                        'type' => 'image',
                        'section' => 'Before / After ("প্রয়োগের আগে ও পরে")',
                        'label' => 'Before image (rusted rod)',
                        'help' => 'Left photo in the lower before/after comparison.',
                    ],
                    'after_image' => [
                        'type' => 'image',
                        'section' => 'Before / After ("প্রয়োগের আগে ও পরে")',
                        'label' => 'After image (protected rod)',
                        'help' => 'Right photo in the lower before/after comparison.',
                    ],
                    'product_slug' => [
                        'type' => 'text',
                        'section' => 'Order Buttons',
                        'label' => 'Buy-now product slug',
                        'help' => 'Every “অর্ডার করুন” button sends the customer straight to this product’s checkout. Create the rust-removal product, then paste its slug here.',
                        'default' => 'rust-removal',
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

    /**
     * Resolve a page's "buy now" target to a direct-checkout URL.
     *
     * Uses the admin-set product slug (falling back to the field default),
     * looks up the product, and builds the existing buy-now route
     * (GET /buy/product?id=…&qty=1 → adds to cart → checkout).
     * Returns null when no slug is set or no matching product exists, so the
     * views can fall back gracefully instead of linking to a dead URL.
     */
    public static function buyUrl(string $page, array $content): ?string
    {
        $config = static::pageConfig($page);
        $slug = $content['product_slug'] ?? ($config['fields']['product_slug']['default'] ?? null);

        if (! $slug) {
            return null;
        }

        $product = Product::where('slug', $slug)->first();

        return $product ? route('buy.product', ['id' => $product->id, 'qty' => 1]) : null;
    }
}
