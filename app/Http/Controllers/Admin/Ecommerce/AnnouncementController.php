<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Setting;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Listing + global bar / WhatsApp / trust settings panel.
     */
    public function index()
    {
        $announcements = Announcement::orderBy('sort_order')->orderByDesc('id')->get();

        return view('admin.e-commerce.announcement.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.e-commerce.announcement.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        Announcement::create($data);

        notify()->success('Announcement successfully added', 'Added');

        return redirect()->to(routeHelper('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.e-commerce.announcement.form', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        $announcement->update($data);

        notify()->success('Announcement successfully updated', 'Updated');

        return redirect()->to(routeHelper('announcement'));
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        notify()->success('Announcement successfully deleted', 'Deleted');

        return back();
    }

    /**
     * Quick on/off toggle from the list.
     */
    public function toggle(Announcement $announcement)
    {
        $announcement->update(['is_active' => ! $announcement->is_active]);

        notify()->success('Announcement status updated', 'Updated');

        return back();
    }

    /**
     * Save the global announcement-bar, floating WhatsApp button and trust-bar settings.
     */
    public function saveSettings(Request $request)
    {
        $keys = [
            // Announcement bar
            'ANNOUNCEMENT_BAR_STATUS' => $request->boolean('ANNOUNCEMENT_BAR_STATUS') ? '1' : '0',
            'ANNOUNCEMENT_BAR_SPEED' => (string) max(1500, (int) $request->input('ANNOUNCEMENT_BAR_SPEED', 4000)),
            'ANNOUNCEMENT_BAR_BG' => $request->input('ANNOUNCEMENT_BAR_BG', '#0f172a'),
            'ANNOUNCEMENT_BAR_TEXT' => $request->input('ANNOUNCEMENT_BAR_TEXT', '#ffffff'),
            // Floating WhatsApp button
            'WHATSAPP_FLOAT_STATUS' => $request->boolean('WHATSAPP_FLOAT_STATUS') ? '1' : '0',
            'WHATSAPP_FLOAT_NUMBER' => preg_replace('/[^0-9+]/', '', (string) $request->input('WHATSAPP_FLOAT_NUMBER', '')),
            'WHATSAPP_FLOAT_MESSAGE' => $request->input('WHATSAPP_FLOAT_MESSAGE', ''),
            'WHATSAPP_FLOAT_TOOLTIP' => $request->input('WHATSAPP_FLOAT_TOOLTIP', 'Chat with us on WhatsApp'),
            'WHATSAPP_FLOAT_DELAY' => (string) max(0, (int) $request->input('WHATSAPP_FLOAT_DELAY', 3)),
            'WHATSAPP_FLOAT_BADGE' => $request->boolean('WHATSAPP_FLOAT_BADGE') ? '1' : '0',
            // Trust bar
            'TRUST_BAR_STATUS' => $request->boolean('TRUST_BAR_STATUS') ? '1' : '0',
            'TRUST_BAR_ITEM_1' => $request->input('TRUST_BAR_ITEM_1', ''),
            'TRUST_BAR_ITEM_2' => $request->input('TRUST_BAR_ITEM_2', ''),
            'TRUST_BAR_ITEM_3' => $request->input('TRUST_BAR_ITEM_3', ''),
        ];

        foreach ($keys as $name => $value) {
            Setting::updateOrCreate(['name' => $name], ['value' => $value]);
        }

        notify()->success('Settings saved successfully', 'Saved');

        return back();
    }

    private function validateData(Request $request): array
    {
        return $this->validate($request, [
            'message' => 'required|string|max:255',
            'icon' => 'nullable|string|max:30',
            'cta_text' => 'nullable|string|max:60',
            'cta_link' => 'nullable|string|max:255',
            'urgency_label' => 'nullable|string|max:40',
            'bg_color' => 'nullable|string|max:30',
            'text_color' => 'nullable|string|max:30',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'sort_order' => 'nullable|integer|min:0',
        ]);
    }
}
