@extends('layouts.admin.app')

@section('title', 'Settings')


@section('content')

    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Setting &ndash; <small class="text-base font-normal text-slate-500">Header</small></h1>
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
                <div class="w-full max-w-3xl">
                    <x-ui.card header="Setting - Custom Header Code">
                        <form action="{{ routeHelper('setting') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="type" value="2">

                            <div class="mb-4">
                                <label for="header_code" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Custom header code</label>
                                <textarea name="header_code" id="header_code" rows="4"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ $header_code->value }} </textarea>
                            </div>
                            <hr class="border-slate-200 my-4">

                            <div class="mb-4">
                                <label for="fb_pixel" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Facebook Pixel Code</label>
                                <textarea name="fb_pixel" id="fb_pixel" rows="4"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ $fb_pixel->value }}</textarea>
                            </div>
                            <hr class="border-slate-200 my-4">

                            <div class="mb-4">
                                <label for="body_code" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Body Code</label>
                                <textarea name="body_code" id="body_code" rows="4"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ setting('body_code') ?? '' }}</textarea>
                            </div>

                            <hr class="border-slate-200 my-4">
                            <div class="mb-4">
                                <label for="global_css" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Global CSS</label>
                                <textarea name="global_css" id="global_css" rows="4" placeholder="body{color:red;}"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ setting('global_css') ?? '' }}</textarea>
                            </div>
                            <hr class="border-slate-200 my-4">

                            <div class="mb-4">
                                <label for="global_js" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Global JS</label>
                                <textarea name="global_js" id="global_js" rows="4" placeholder="console.log('Hello Lems');"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ setting('global_js') ?? '' }}</textarea>
                            </div>
                            <hr class="border-slate-200 my-4">

                            <div class="mb-4">
                                <label for="override_css" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Bottom CSS - Override CSS</label>
                                <textarea name="override_css" id="override_css" rows="4" placeholder="a{color:red !important;}"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ setting('override_css') ?? '' }}</textarea>
                            </div>
                            <hr class="border-slate-200 my-4">

                            <div class="border border-info rounded py-4 mt-2 mb-4 px-4">
                                <div class="mb-4">
                                    <label for="BELOW_SLIDER_HTML_CODE_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Below Slider
                                        Custom HTML Code Status</label>
                                    <select name="BELOW_SLIDER_HTML_CODE_STATUS" id="BELOW_SLIDER_HTML_CODE_STATUS"
                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                        @if (setting('BELOW_SLIDER_HTML_CODE_STATUS') == 1)
                                            <option value="1">ON</option>
                                            <option value="0">OFF</option>
                                        @else
                                            <option value="0">OFF</option>
                                            <option value="1">ON</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="BELOW_SLIDER_HTML_CODE" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Custom Html
                                        Code</label>
                                    <textarea name="BELOW_SLIDER_HTML_CODE" id="BELOW_SLIDER_HTML_CODE" rows="4"
                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ setting('BELOW_SLIDER_HTML_CODE') ?? '<b>Hello www.asifulmamun.info.bd</b>' }}</textarea>
                                </div>
                            </div>

                            <div class="border border-info rounded py-4 mt-2 mb-4 px-4">
                                <div class="mb-4">
                                    <label for="NOTICE_STATUS" class="block text-sm font-medium text-slate-700 mb-1 capitalize">NOTICE STATUS</label>
                                    <select name="NOTICE_STATUS" id="NOTICE_STATUS"
                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                        @if (setting('NOTICE_STATUS') == 1)
                                            <option value="1">ON</option>
                                            <option value="0">OFF</option>
                                        @else
                                            <option value="0">OFF</option>
                                            <option value="1">ON</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="CUSTOM_NOTICE" class="block text-sm font-medium text-slate-700 mb-1 capitalize">CUSTOM NOTICE</label>
                                    <small class="inline-block bg-yellow-300 px-1 py-0.5 text-xs mb-1">It's under container of bootstrap</small>
                                    <textarea name="CUSTOM_NOTICE" id="CUSTOM_NOTICE" rows="4"
                                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ setting('CUSTOM_NOTICE') ?? 'Today is offer for 30%' }}</textarea>
                                </div>
                            </div>

                            <hr class="border-slate-200 my-4">
                            <div class="mb-4">
                                <label for="FOOTER_COL_4_HTML" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Footer Column 4</label>
                                <textarea name="FOOTER_COL_4_HTML" id="FOOTER_COL_4_HTML" rows="4"
                                    placeholder="Payment Support: Bkash, Roacket, Nagad"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">{{ setting('FOOTER_COL_4_HTML') ?? '' }}</textarea>
                            </div>
                            <small class="inline-block p-4 bg-slate-100 text-primary">
                                <a target="_blank" class="bg-yellow-300 p-2"
                                    href="https://getbootstrap.com/docs/4.5/components/alerts/">Help for HTML
                                    snippet from Bootstrap V5.4.3</a>
                            </small>

                            <div class="mt-4 mb-4">
                                <label for="android_app" class="block text-sm font-medium text-slate-700 mb-1 capitalize">Android App</label>
                                <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                    type="text" name="android_app" id="android_app"
                                    value="{{ setting('android_app') ?? '' }}"
                                    placeholder="https://play.googe.com/">
                            </div>

                            <div class="border-t border-slate-200 -mx-4 px-4 pt-3 mt-4">
                                <x-ui.button type="submit" variant="success">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                </x-ui.button>
                            </div>
                        </form>
                    </x-ui.card>
                </div>
            </div>
        </x-ui.card>
    </section>

@endsection
