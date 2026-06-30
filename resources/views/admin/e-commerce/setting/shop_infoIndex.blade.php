@extends('layouts.admin.app')
@section('title', 'Settings')

@section('content')
    {{-- Page Header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Setting &ndash; <small class="text-base font-normal text-slate-500">Credintial</small></h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mx-1 before:content-['/']">SMS | Mail | Login | Register Configureation</li>
            </ol>
        </div>
    </section>

    {{-- LOGIN / REG - OPTION --}}
    <section>
        <x-ui.card>
            <x-slot:header>Shop Information</x-slot:header>

            <div class="flex justify-center">
                <div class="w-full max-w-3xl">
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 px-4 py-3 font-medium text-slate-900">Setting - Shop Information</div>

                        <form id="email_config" action="{{ routeHelper('setting') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="type" value="8">

                            <div class="p-4">
                                <ul class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <li class="md:col-span-2">
                                        <x-ui.button variant="primary" :href="routeHelper('shop')">
                                            Update Main Information
                                            <i class="fas fa-caret-right"></i>
                                        </x-ui.button>
                                    </li>

                                    <li class="mb-2">
                                        <label for="SITE_INFO_ADDRESS" class="mb-1 block text-sm font-medium capitalize text-slate-700">Company full address
                                            <span class="text-danger">(*)</span></label>
                                        <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="SITE_INFO_ADDRESS"
                                            id="SITE_INFO_ADDRESS" placeholder="Company Full Address"
                                            value="{{ $SITE_INFO_ADDRESS->value }}" required>
                                    </li>

                                    <li class="mb-2">
                                        <label for="SITE_INFO_PHONE" class="mb-1 block text-sm font-medium capitalize text-slate-700">Company primary phone
                                            <span class="text-danger">(*)</span></label>
                                        <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="SITE_INFO_PHONE"
                                            id="SITE_INFO_PHONE" placeholder="Company Primary Phone"
                                            value="{{ $SITE_INFO_PHONE->value }}" required>
                                    </li>

                                    <li class="mb-2">
                                        <label for="SITE_INFO_SUPPORT_MAIL" class="mb-1 block text-sm font-medium capitalize text-slate-700">Company suupport mail
                                            <span class="text-danger">(*)</span></label>
                                        <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="SITE_INFO_SUPPORT_MAIL"
                                            id="SITE_INFO_SUPPORT_MAIL" placeholder="Company Support Email"
                                            value="{{ $SITE_INFO_SUPPORT_MAIL->value }}" required>
                                    </li>

                                    <li class="mb-2">
                                        <label for="copy_right_text" class="mb-1 block text-sm font-medium capitalize text-slate-700">Copyright Text
                                            <span class="text-danger">(*)</span></label>
                                        <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="copy_right_text"
                                            id="copy_right_text" placeholder="Company Support Email"
                                            value="{{ setting('copy_right_text') ?? '© Lems Copyright' }}" required>
                                    </li>

                                    <li class="mb-2 md:col-span-2">
                                        <label for="footer_description" class="mb-1 block text-sm font-medium capitalize text-slate-700">Footer Descripttion
                                            <span class="text-danger">(*)</span></label>
                                        <textarea name="footer_description" id="footer_description" rows="4"
                                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                            required>{{ setting('footer_description') ?? 'Footer Description, Example: This is Lems by Finvasoft' }}</textarea>
                                    </li>
                                </ul>

                                <hr class="my-4 border-slate-200">

                                <ul class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <li class="mb-2">
                                        <label for="email" class="mb-1 block text-sm font-medium capitalize text-slate-700">E-mail
                                            <span class="text-danger">(*)</span></label>
                                        <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="email" name="email" id="email"
                                            placeholder="info@finvasoft.com"
                                            value="{{ setting('email') ?? 'hello@asifulmamun.info.bd' }}" required>
                                    </li>

                                    <li class="mb-2">
                                        <label for="whatsapp" class="mb-1 block text-sm font-medium capitalize text-slate-700">WhatsApp</label>
                                        <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="number" name="whatsapp" id="whatsapp"
                                            placeholder="8801721600688"
                                            value="{{ setting('whatsapp') ?? '01721*****88' }}">
                                        <small class="text-danger">With Country Code (Without + sign)</small>
                                    </li>

                                    <li class="mb-2">
                                        <label for="facebook" class="mb-1 block text-sm font-medium capitalize text-slate-700">Facebook</label>
                                        <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="facebook"
                                            id="facebook" value="{{ setting('facebook') ?? '' }}">
                                    </li>

                                    <li class="mb-2">
                                        <label for="messanger" class="mb-1 block text-sm font-medium capitalize text-slate-700">Messanger</label>
                                        <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="messanger"
                                            id="messanger" value="{{ setting('messanger') ?? '' }}">
                                    </li>

                                    <li class="mb-2">
                                        <label for="linkedin" class="mb-1 block text-sm font-medium capitalize text-slate-700">Linkedin</label>
                                        <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="linkedin"
                                            id="linkedin" value="{{ setting('linkedin') ?? '' }}">
                                    </li>

                                    <li class="mb-2">
                                        <label for="twitter" class="mb-1 block text-sm font-medium capitalize text-slate-700">Twitter</label>
                                        <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="twitter" id="twitter"
                                            value="{{ setting('twitter') ?? '' }}">
                                    </li>

                                    <li class="mb-2">
                                        <label for="youtube" class="mb-1 block text-sm font-medium capitalize text-slate-700">Youtube</label>
                                        <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="youtube" id="youtube"
                                            value="{{ setting('youtube') ?? '' }}">
                                    </li>

                                    <li class="mb-2">
                                        <label for="instagram" class="mb-1 block text-sm font-medium capitalize text-slate-700">Instagram</label>
                                        <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="instagram"
                                            id="instagram" value="{{ setting('instagram') ?? '' }}">
                                    </li>
                                </ul>
                            </div>

                            <div class="border-t border-slate-200 px-4 py-3">
                                <x-ui.button type="submit" variant="success">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                </x-ui.button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </x-ui.card>
    </section>

@endsection
