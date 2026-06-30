@extends('layouts.admin.app')

@section('title', 'Announcement Bar')

@section('content')

    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Announcement Bar</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="text-slate-400">/</li>
                <li class="text-slate-700">Announcement Bar</li>
            </ol>
        </div>
    </section>

    {{-- Global controls: bar / WhatsApp button / trust strip --}}
    <section class="mb-6">
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-4 py-3 font-semibold text-slate-800">
                Bar, WhatsApp & Trust Settings
            </div>

            <form action="{{ routeHelper('announcement/save-settings') }}" method="POST" class="p-4">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

                    {{-- Announcement bar --}}
                    <div class="space-y-3">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Announcement Bar</h3>

                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="sr-only peer" name="ANNOUNCEMENT_BAR_STATUS" value="1"
                                @checked(setting('ANNOUNCEMENT_BAR_STATUS', '1') == '1')>
                            <div class="relative w-10 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-slate-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            <span class="text-sm font-medium text-slate-700">Enable bar</span>
                        </label>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Rotation speed (ms)</label>
                            <input type="number" name="ANNOUNCEMENT_BAR_SPEED" min="1500" step="500"
                                value="{{ setting('ANNOUNCEMENT_BAR_SPEED', '4000') }}"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm">
                        </div>

                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Background</label>
                                <input type="color" name="ANNOUNCEMENT_BAR_BG"
                                    value="{{ setting('ANNOUNCEMENT_BAR_BG', '#0f172a') }}"
                                    class="h-10 w-full rounded-md border border-slate-300">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Text</label>
                                <input type="color" name="ANNOUNCEMENT_BAR_TEXT"
                                    value="{{ setting('ANNOUNCEMENT_BAR_TEXT', '#ffffff') }}"
                                    class="h-10 w-full rounded-md border border-slate-300">
                            </div>
                        </div>
                    </div>

                    {{-- WhatsApp floating button --}}
                    <div class="space-y-3">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Floating WhatsApp</h3>

                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="sr-only peer" name="WHATSAPP_FLOAT_STATUS" value="1"
                                @checked(setting('WHATSAPP_FLOAT_STATUS', '1') == '1')>
                            <div class="relative w-10 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-slate-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            <span class="text-sm font-medium text-slate-700">Enable button</span>
                        </label>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Phone number</label>
                            <input type="text" name="WHATSAPP_FLOAT_NUMBER"
                                value="{{ setting('WHATSAPP_FLOAT_NUMBER', setting('whatsapp', '01624109210')) }}"
                                placeholder="01624109210"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Pre-filled message</label>
                            <input type="text" name="WHATSAPP_FLOAT_MESSAGE"
                                value="{{ setting('WHATSAPP_FLOAT_MESSAGE', 'আমি একটি প্রোডাক্ট অর্ডার করতে চাই।') }}"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm">
                        </div>

                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tooltip</label>
                                <input type="text" name="WHATSAPP_FLOAT_TOOLTIP"
                                    value="{{ setting('WHATSAPP_FLOAT_TOOLTIP', 'Chat with us on WhatsApp') }}"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm">
                            </div>
                            <div class="w-24">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Delay (s)</label>
                                <input type="number" name="WHATSAPP_FLOAT_DELAY" min="0"
                                    value="{{ setting('WHATSAPP_FLOAT_DELAY', '3') }}"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm">
                            </div>
                        </div>

                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="sr-only peer" name="WHATSAPP_FLOAT_BADGE" value="1"
                                @checked(setting('WHATSAPP_FLOAT_BADGE', '1') == '1')>
                            <div class="relative w-10 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-slate-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            <span class="text-sm font-medium text-slate-700">Show unread badge</span>
                        </label>
                    </div>

                    {{-- Trust strip --}}
                    <div class="space-y-3">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Trust Strip</h3>

                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" class="sr-only peer" name="TRUST_BAR_STATUS" value="1"
                                @checked(setting('TRUST_BAR_STATUS', '1') == '1')>
                            <div class="relative w-10 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-slate-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            <span class="text-sm font-medium text-slate-700">Enable strip</span>
                        </label>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Item 1</label>
                            <input type="text" name="TRUST_BAR_ITEM_1"
                                value="{{ setting('TRUST_BAR_ITEM_1', 'Cash on Delivery Available') }}"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Item 2</label>
                            <input type="text" name="TRUST_BAR_ITEM_2"
                                value="{{ setting('TRUST_BAR_ITEM_2', 'Trusted by 10,000+ Customers') }}"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Item 3</label>
                            <input type="text" name="TRUST_BAR_ITEM_3"
                                value="{{ setting('TRUST_BAR_ITEM_3', 'Fast Delivery in 24–48 Hours') }}"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm">
                        </div>
                    </div>

                </div>

                <div class="mt-5 border-t border-slate-200 pt-4">
                    <x-ui.button variant="primary" type="submit">
                        <i class="fas fa-save"></i> Save Settings
                    </x-ui.button>
                </div>
            </form>
        </div>
    </section>

    {{-- Announcement list --}}
    <section>
        <x-ui.card>
            <x-slot:header>
                <div class="flex items-center justify-between">
                    <span class="text-base font-semibold text-slate-800">Announcements</span>
                    <x-ui.button variant="success" :href="routeHelper('announcement/create')">
                        <i class="fas fa-plus-circle"></i> Add Announcement
                    </x-ui.button>
                </div>
            </x-slot:header>

            <x-ui.table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Message</th>
                        <th>Icon</th>
                        <th>Urgency</th>
                        <th>Schedule</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($announcements as $data)
                        <tr>
                            <td>{{ $data->sort_order }}</td>
                            <td class="max-w-md">{{ Str::limit($data->message, 80) }}</td>
                            <td>{{ $data->icon ?? '—' }}</td>
                            <td>{{ $data->urgency_label ?? '—' }}</td>
                            <td class="whitespace-nowrap text-xs">
                                {{ $data->starts_at?->format('d M y H:i') ?? 'Always' }}
                                <br>→ {{ $data->ends_at?->format('d M y H:i') ?? 'No end' }}
                            </td>
                            <td>
                                <form action="{{ routeHelper('announcement/' . $data->id . '/toggle') }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="cursor-pointer border-0 bg-transparent p-0">
                                        @if ($data->is_active)
                                            <x-ui.badge variant="success">Active</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="danger">Off</x-ui.badge>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td>
                                <x-ui.button variant="info" size="sm" :href="routeHelper('announcement/' . $data->id . '/edit')">
                                    <i class="fas fa-edit"></i>
                                </x-ui.button>
                                <x-ui.button variant="danger" size="sm" href="javascript:void(0)"
                                    data-id="{{ $data->id }}" id="deleteData">
                                    <i class="fas fa-trash-alt"></i>
                                </x-ui.button>
                                <form id="delete-data-form-{{ $data->id }}"
                                    action="{{ routeHelper('announcement/' . $data->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-500">No announcements yet. Add your first one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-ui.table>
        </x-ui.card>
    </section>

@endsection
