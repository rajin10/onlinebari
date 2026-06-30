<?php

// Guards migrated surfaces against regressing to inline styling.
//
// Scope (deliberately narrow so the guard stays honest and parity-safe):
//   - Flags STATIC inline `style="..."` attributes — the presentational styling
//     that belongs in Tailwind utilities. These are what the migration removes.
//   - ALLOWS inline styles whose value is Blade-interpolated ({{ }} / {!! !!}),
//     e.g. `style="background: {{ $color->code }}"` — a runtime value that cannot
//     be expressed as a static utility class.
//   - IGNORES <style> blocks. On the storefront/auth surfaces these hold the
//     preserved theme + component CSS (media queries, .select2 / third-party
//     selectors) that intentionally stays as CSS, not utilities.
//   - IGNORES commented-out markup (HTML <!-- --> and Blade {{-- --}}); dead code
//     renders nothing, so it is not a styling regression.
//
// As each surface reaches "no static inline styles", add its directory
// (relative to resources/views) to $migratedDirs.
$migratedDirs = [
    'auth',
];

// Self-contained documents that load no Tailwind (email bodies, printable
// invoices) — inline styles are mandatory there, so they are exempt even when
// they live under a migrated directory.
$inlineStyleAllowlist = [
    'frontend/contact-mail.blade.php',
    'frontend/invoice-mail.blade.php',
    'frontend/invoice.blade.php',
    'frontend/pass-mail.blade.php',
];

it('keeps migrated view directories free of static inline styles', function () use ($migratedDirs, $inlineStyleAllowlist) {
    $offenders = [];

    foreach ($migratedDirs as $dir) {
        $base = resource_path('views/'.$dir);
        if (! is_dir($base)) {
            continue;
        }

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
        foreach ($files as $file) {
            if (! $file->isFile() || ! str_ends_with($file->getFilename(), '.blade.php')) {
                continue;
            }

            $relative = str_replace(resource_path('views').'/', '', $file->getPathname());
            if (in_array($relative, $inlineStyleAllowlist, true)) {
                continue;
            }

            $content = file_get_contents($file->getPathname());
            // Drop commented-out markup before scanning.
            $content = preg_replace('/\{\{--.*?--\}\}/s', '', $content);
            $content = preg_replace('/<!--.*?-->/s', '', $content);

            preg_match_all('/style\s*=\s*"([^"]*)"/i', $content, $matches);
            foreach ($matches[1] as $value) {
                // Allow genuinely dynamic, Blade-interpolated values.
                if (str_contains($value, '{{') || str_contains($value, '{!!')) {
                    continue;
                }
                $offenders[] = $relative.' → style="'.$value.'"';
            }
        }
    }

    expect($offenders)->toBeEmpty();
});
