@extends('layouts.admin.app')

@section('title')
    {{ isset($announcement) ? 'Edit Announcement' : 'Add Announcement' }}
@endsection

@php
    $icons = ['' => 'No icon', 'whatsapp' => 'WhatsApp', 'phone' => 'Phone', 'offer' => 'Offer / Tag', 'fire' => 'Hot / Fire', 'truck' => 'Delivery', 'shield' => 'Secure', 'star' => 'Star', 'clock' => 'Clock'];
    $fmt = fn ($d) => $d ? \Illuminate\Support\Carbon::parse($d)->format('Y-m-d\TH:i') : '';
@endphp

@section('content')
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">
                {{ isset($announcement) ? 'Edit Announcement' : 'Add Announcement' }}
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li>/</li>
                <li><a href="{{ routeHelper('announcement') }}" class="hover:text-primary">Announcements</a></li>
                <li>/</li>
                <li class="text-slate-700">{{ isset($announcement) ? 'Edit' : 'Add' }}</li>
            </ol>
        </div>
    </section>

    <section>
        <div class="mx-auto max-w-3xl">
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <h3 class="font-semibold text-slate-800">
                        {{ isset($announcement) ? 'Edit Announcement' : 'Add New Announcement' }}
                    </h3>
                    <x-ui.button variant="danger" size="sm" :href="routeHelper('announcement')">
                        <i class="fas fa-long-arrow-alt-left"></i> Back to List
                    </x-ui.button>
                </div>

                <form
                    action="{{ isset($announcement) ? routeHelper('announcement/' . $announcement->id) : routeHelper('announcement') }}"
                    method="POST">
                    @csrf
                    @isset($announcement)
                        @method('PUT')
                    @endisset

                    <div class="space-y-4 p-4">
                        <x-ui.input name="message" label="Message:" type="text"
                            :value="$announcement->message ?? old('message')"
                            placeholder="e.g. WhatsApp এ অর্ডার করুন 01624109210" required autocomplete="off" />

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <x-ui.select name="icon" label="Icon:">
                                @foreach ($icons as $value => $text)
                                    <option value="{{ $value }}"
                                        @selected(($announcement->icon ?? old('icon')) === $value)>{{ $text }}</option>
                                @endforeach
                            </x-ui.select>

                            <x-ui.input name="urgency_label" label="Urgency label (optional):" type="text"
                                :value="$announcement->urgency_label ?? old('urgency_label')"
                                placeholder="Limited Time / Almost Gone" autocomplete="off" />
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <x-ui.input name="cta_text" label="Button text (optional):" type="text"
                                :value="$announcement->cta_text ?? old('cta_text')"
                                placeholder="Order on WhatsApp" autocomplete="off" />

                            <x-ui.input name="cta_link" label="Button link (optional):" type="text"
                                :value="$announcement->cta_link ?? old('cta_link')"
                                placeholder="https://wa.me/8801624109210" autocomplete="off" />
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Start date/time (optional)</label>
                                <input type="datetime-local" name="starts_at"
                                    value="{{ $fmt($announcement->starts_at ?? old('starts_at')) }}"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">End date/time (optional)</label>
                                <input type="datetime-local" name="ends_at"
                                    value="{{ $fmt($announcement->ends_at ?? old('ends_at')) }}"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <x-ui.input name="sort_order" label="Sort order:" type="number"
                                :value="$announcement->sort_order ?? old('sort_order', 0)" />

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Item background (optional)</label>
                                <input type="text" name="bg_color"
                                    value="{{ $announcement->bg_color ?? old('bg_color') }}" placeholder="#0f172a"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Item text colour (optional)</label>
                                <input type="text" name="text_color"
                                    value="{{ $announcement->text_color ?? old('text_color') }}" placeholder="#ffffff"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="sr-only peer" name="is_active" value="1"
                                    @checked(old('is_active', $announcement->is_active ?? true))>
                                <div class="relative w-10 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:border-slate-300 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                <span class="text-sm font-medium text-slate-700">Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 px-4 py-3">
                        <x-ui.button variant="primary" type="submit">
                            @isset($announcement)
                                <i class="fas fa-arrow-circle-up"></i> Update
                            @else
                                <i class="fas fa-plus-circle"></i> Submit
                            @endisset
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
