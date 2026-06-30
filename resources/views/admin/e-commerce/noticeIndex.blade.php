@extends('layouts.admin.app')

@section('title', 'Settings')


@section('content')

    {{-- Content Header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Setting - <small class="text-base font-normal text-slate-500">Header</small></h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:content-['/'] before:mx-1">My Profile</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>
        <x-ui.card header="Application Settings">

            <div class="flex justify-center">
                <div class="w-full md:w-2/3 mx-auto">

                    {{-- Inner card: card-success → green header --}}
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 bg-success px-4 py-3 font-medium text-white">
                            Setting - Custom Header Code
                        </div>

                        {{-- Help link (sits between header and form in the original) --}}
                        <div class="px-4 pt-3">
                            <a target="_blank"
                               class="inline-block rounded bg-yellow-400 px-2 py-1 text-sm font-medium text-slate-800 hover:bg-yellow-300"
                               href="https://getbootstrap.com/docs/4.5/components/alerts/">
                                Help for HTML snippet from Bootstrap V5.4.3
                            </a>
                        </div>

                        <form action="{{ routeHelper('setting') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="px-4 pb-4">

                                {{-- Below Slider section --}}
                                <div class="rounded border border-info py-2 px-4 mt-2 mb-4">
                                    <div class="mb-4">
                                        <label for="BELOW_SLIDER_HTML_CODE_STATUS"
                                               class="block text-sm font-medium capitalize text-slate-700 mb-1">
                                            Below Slider Custom HTML Code Status
                                        </label>
                                        <select name="BELOW_SLIDER_HTML_CODE_STATUS"
                                                id="BELOW_SLIDER_HTML_CODE_STATUS"
                                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                            @if ($BELOW_SLIDER_HTML_CODE_STATUS->value == 1)
                                                <option value="1">ON</option>
                                                <option value="0">OFF</option>
                                            @else
                                                <option value="0">OFF</option>
                                                <option value="1">ON</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label for="BELOW_SLIDER_HTML_CODE"
                                               class="block text-sm font-medium capitalize text-slate-700 mb-1">
                                            Custom Html Code
                                        </label>
                                        <textarea name="BELOW_SLIDER_HTML_CODE" id="BELOW_SLIDER_HTML_CODE" rows="4"
                                                  class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ $BELOW_SLIDER_HTML_CODE->value }}</textarea>
                                    </div>
                                </div>

                                {{-- Notice section --}}
                                <div class="rounded border border-info py-2 px-4 mt-2 mb-2">
                                    <input type="hidden" name="type" value="11">
                                    <div class="mb-4">
                                        <label for="NOTICE_STATUS"
                                               class="block text-sm font-medium capitalize text-slate-700 mb-1">
                                            NOTICE STATUS
                                        </label>
                                        <select name="NOTICE_STATUS" id="NOTICE_STATUS"
                                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                            @if ($NOTICE_STATUS->value == 1)
                                                <option value="1">ON</option>
                                                <option value="0">OFF</option>
                                            @else
                                                <option value="0">OFF</option>
                                                <option value="1">ON</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label for="CUSTOM_NOTICE"
                                               class="block text-sm font-medium capitalize text-slate-700 mb-1">
                                            CUSTOM NOTICE
                                        </label>
                                        <span class="inline-block rounded bg-yellow-400 px-1 py-0.5 text-xs text-slate-800 mb-1">It's under container of bootstrap</span>
                                        <textarea name="CUSTOM_NOTICE" id="CUSTOM_NOTICE" rows="4"
                                                  class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ $CUSTOM_NOTICE->value }}</textarea>
                                    </div>
                                </div>

                            </div>

                            {{-- Card footer: submit button inside form --}}
                            <div class="border-t border-slate-200 px-4 py-3">
                                <x-ui.button type="submit" variant="success">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                </x-ui.button>
                            </div>

                        </form>
                    </div>
                    {{-- /.inner card --}}

                </div>
            </div>

        </x-ui.card>
    </section>

@endsection
