<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageContent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LandingPageContentController extends Controller
{
    /**
     * Resolve the page config or 404 for an unknown slug.
     */
    private function config(string $page): array
    {
        $config = LandingPageContent::pageConfig($page);

        abort_if($config === null, 404);

        return $config;
    }

    /**
     * Management screen for a single landing page.
     */
    public function edit(string $page)
    {
        $config = $this->config($page);
        $content = LandingPageContent::forPage($page);

        return view('admin.e-commerce.landing.edit', compact('page', 'config', 'content'));
    }

    /**
     * Persist uploaded images / video URL for a landing page.
     */
    public function update(Request $request, string $page)
    {
        $config = $this->config($page);

        // Build validation rules from the field definitions.
        $rules = [];
        foreach ($config['fields'] as $key => $field) {
            if ($field['type'] === 'image') {
                $rules[$key] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096';
            } elseif ($field['type'] === 'youtube_url') {
                $rules[$key] = [
                    'nullable', 'string', 'max:255',
                    function (string $attribute, $value, $fail) {
                        if ($value && ! LandingPageContent::youtubeId($value)) {
                            $fail('Enter a valid YouTube URL (watch?v=…, youtu.be/…, or embed/…).');
                        }
                    },
                ];
            }
        }

        $request->validate($rules, [], $this->attributeNames($config));

        foreach ($config['fields'] as $key => $field) {
            if ($field['type'] === 'image') {
                if ($request->hasFile($key)) {
                    $this->storeImage($request, $page, $key);
                }
                // No new file selected → leave the existing image untouched.
            } elseif ($field['type'] === 'youtube_url') {
                LandingPageContent::updateOrCreate(
                    ['page_slug' => $page, 'section_key' => $key],
                    ['content_type' => 'youtube_url', 'value' => $request->input($key) ?: null],
                );
            }
        }

        notify()->success('Landing page content updated.', 'Saved');

        return redirect()->route('admin.landing.edit', $page);
    }

    /**
     * Remove a single image slot (deletes the file + clears the DB value).
     */
    public function deleteImage(string $page, string $key)
    {
        $config = $this->config($page);
        abort_if(! isset($config['fields'][$key]) || $config['fields'][$key]['type'] !== 'image', 404);

        $row = LandingPageContent::where('page_slug', $page)->where('section_key', $key)->first();

        if ($row) {
            $this->deleteFile($row->value);
            $row->update(['value' => null]);
        }

        notify()->success('Image removed.', 'Deleted');

        return back();
    }

    /**
     * Move an uploaded image into public/uploads/landing and record the filename.
     * Mirrors the existing BannerController upload convention.
     */
    private function storeImage(Request $request, string $page, string $key): void
    {
        $existing = LandingPageContent::where('page_slug', $page)->where('section_key', $key)->first();
        if ($existing) {
            $this->deleteFile($existing->value);
        }

        $image = $request->file($key);
        $filename = Carbon::now()->toDateString().'-'.uniqid().'.'.$image->getClientOriginalExtension();

        $dir = public_path(LandingPageContent::IMAGE_DIR);
        if (! file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $image->move($dir, $filename);

        LandingPageContent::updateOrCreate(
            ['page_slug' => $page, 'section_key' => $key],
            ['content_type' => 'image', 'value' => $filename],
        );
    }

    private function deleteFile(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $path = public_path(LandingPageContent::IMAGE_DIR.'/'.$filename);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    /**
     * Friendly field names for validation messages.
     */
    private function attributeNames(array $config): array
    {
        $names = [];
        foreach ($config['fields'] as $key => $field) {
            $names[$key] = $field['label'];
        }

        return $names;
    }
}
