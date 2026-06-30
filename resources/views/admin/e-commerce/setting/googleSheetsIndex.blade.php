@extends('layouts.admin.app')
@section('title', 'Google Sheets')

@section('content')
    {{-- Page Header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Setting - <small class="text-base font-normal text-slate-500">Google Sheets Order Log</small></h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">Google Sheets</li>
            </ol>
        </div>
    </section>

    <section>
        <x-ui.card header="Google Sheets — Auto-log every order">
            <div class="mx-auto w-full max-w-2xl flex flex-col gap-6">

                {{-- Settings form --}}
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="rounded-t-lg bg-success px-4 py-3 font-medium text-white">
                        Connection
                    </div>

                    <form action="{{ routeHelper('setting') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" value="20">

                        <div class="p-4">
                            <ul class="space-y-4">
                                <li>
                                    <label for="GOOGLE_SHEETS_ENABLED" class="block text-sm font-medium text-slate-700 mb-1">Logging</label>
                                    @php $gsEnabled = setting('GOOGLE_SHEETS_ENABLED', '1'); @endphp
                                    <select name="GOOGLE_SHEETS_ENABLED" id="GOOGLE_SHEETS_ENABLED"
                                        class="rounded border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                        <option value="1" @selected(strtolower((string) $gsEnabled) !== '0' && strtolower((string) $gsEnabled) !== 'false')>ON</option>
                                        <option value="0" @selected(strtolower((string) $gsEnabled) === '0' || strtolower((string) $gsEnabled) === 'false')>OFF</option>
                                    </select>
                                </li>
                                <li>
                                    <label for="GOOGLE_SHEETS_WEBHOOK_URL" class="block text-sm font-medium text-slate-700 mb-1">Web App URL (Apps Script /exec link)</label>
                                    <input type="url" name="GOOGLE_SHEETS_WEBHOOK_URL" id="GOOGLE_SHEETS_WEBHOOK_URL"
                                        value="{{ setting('GOOGLE_SHEETS_WEBHOOK_URL') ?? '' }}"
                                        placeholder="https://script.google.com/macros/s/XXXX/exec"
                                        class="w-full rounded border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                    <small class="text-slate-500">Paste the deployment URL ending in <code>/exec</code> — not the spreadsheet link.</small>
                                </li>
                                <li>
                                    <label for="GOOGLE_SHEETS_SECRET" class="block text-sm font-medium text-slate-700 mb-1">Secret</label>
                                    <input type="text" name="GOOGLE_SHEETS_SECRET" id="GOOGLE_SHEETS_SECRET"
                                        value="{{ setting('GOOGLE_SHEETS_SECRET') ?? '' }}"
                                        placeholder="Same long random string set inside the script"
                                        class="w-full rounded border border-slate-300 px-2 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                    <small class="text-slate-500">Must match the <code>SECRET</code> value in your Apps Script.</small>
                                </li>
                            </ul>
                        </div>

                        <div class="flex items-center gap-3 border-t border-slate-200 px-4 py-3">
                            <x-ui.button type="submit" variant="success">
                                <i class="fas fa-arrow-circle-up"></i> Save
                            </x-ui.button>
                        </div>
                    </form>

                    {{-- Test row (separate form so it isn't tied to Save) --}}
                    <form action="{{ routeHelper('setting/google-sheets/test') }}" method="POST" class="border-t border-slate-200 px-4 py-3">
                        @csrf
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-vial"></i> Send test row
                        </x-ui.button>
                        <small class="ml-2 text-slate-500">Sends a sample row so you can confirm it reaches your sheet.</small>
                    </form>
                </div>

                {{-- Setup walkthrough --}}
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="rounded-t-lg bg-primary px-4 py-3 font-medium text-white">
                        One-time setup (≈ 2 minutes)
                    </div>
                    <div class="p-4 text-sm text-slate-700">
                        <ol class="list-decimal space-y-2 pl-5">
                            <li>Open your Google Sheet → <b>Extensions → Apps Script</b>.</li>
                            <li>Delete the sample code, paste the script from <code>docs/google-sheets-webhook.md</code>, and set a long random <code>SECRET</code>.</li>
                            <li><b>Deploy → New deployment → Web app</b>. Execute as <b>Me</b>; Who has access <b>Anyone</b>.</li>
                            <li>Copy the <code>/exec</code> URL it gives you, paste it above with the same secret, and hit <b>Save</b>.</li>
                            <li>Click <b>Send test row</b> — a test line should appear in the sheet.</li>
                        </ol>
                        <p class="mt-3 text-slate-500">Every successful order then auto-appends (name, phone, address, products, total, plus fraud columns: total / successful / pending / cancelled / success rate / risk level). Duplicates are skipped by Order ID.</p>
                    </div>
                </div>

            </div>
        </x-ui.card>
    </section>
@endsection
