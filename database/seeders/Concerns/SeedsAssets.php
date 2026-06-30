<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\File;

trait SeedsAssets
{
    /** Filenames present in public/uploads/$dir (no path). */
    protected function imagePool(string $dir): array
    {
        $path = public_path('uploads/'.$dir);

        if (! File::isDirectory($path)) {
            return [];
        }

        return collect(File::files($path))
            ->map(fn ($f) => $f->getFilename())
            ->values()
            ->all();
    }

    /** A real filename from the pool, or the fallback when the dir is empty. */
    protected function pickImage(string $dir, string $fallback = 'default.png'): string
    {
        $pool = $this->imagePool($dir);

        return $pool === [] ? $fallback : $pool[array_rand($pool)];
    }
}
