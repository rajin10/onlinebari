@extends('layouts.admin.app')
@section('title', 'Settings')

@section('content')
    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Setting - <small class="text-base font-normal text-slate-500">Credintial</small></h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mx-1 before:content-['/']">SMS | Mail | Login | Register Configureation</li>
            </ol>
        </div>
    </section>

    {{-- LOGIN / REG - OPTION --}}
    <section class="mb-6">
        <x-ui.card header="LOGIN REGISTRATION OPTION CHOOSE">
            <div class="mx-auto w-full md:w-2/3">
                {{-- Inner success card --}}
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="rounded-t-lg bg-success px-4 py-3 font-normal text-white">
                        Setting - Login Registration Option Choose
                    </div>
                    <form id="email_config" action="{{ routeHelper('setting') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="p-4">
                            <div class="w-full">
                                <input type="hidden" name="type" value="7">
                                <ul class="space-y-3 list-none p-0 m-0">
                                    <li>
                                        <label for="regVerify" class="block text-sm font-medium text-slate-700 capitalize">Registration verify With</label>
                                        <select name="regVerify" id="regVerify" class="m-2">
                                            @if (setting('regVerify') == 'email')
                                                <option value="email">Only Email</option>
                                                <option value="sms">Only SMS</option>
                                            @else
                                                <option value="sms">Only SMS</option>
                                                <option value="email">Only Email</option>
                                            @endif
                                        </select>
                                        <small class="text-blue-600">Selected: {{ Str::upper(setting('regVerify')) }}</small>
                                    </li>
                                    <li>
                                        <label for="recovrAC" class="block text-sm font-medium text-slate-700 capitalize">Account recover with</label>
                                        <select name="recovrAC" id="recovrAC" class="m-2">
                                            @if (setting('recovrAC') == 'email')
                                                <option value="email">Only Email</option>
                                                <option value="sms">Only SMS</option>
                                                <option value="emailsms">Email & SMS both</option>
                                            @elseif (setting('recovrAC') == 'sms')
                                                <option value="sms">Only SMS</option>
                                                <option value="email">Only Email</option>
                                                <option value="emailsms">Email & SMS both</option>
                                            @else
                                                <option value="emailsms">Email & SMS both</option>
                                                <option value="email">Only Email</option>
                                                <option value="sms">Only SMS</option>
                                            @endif
                                        </select>
                                        <small class="text-blue-600">Selected: {{ Str::upper(setting('recovrAC')) }}</small>
                                    </li>
                                </ul>
                            </div>
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
        </x-ui.card>
    </section>

    {{-- Mail config --}}
    <section class="mb-6">
        <x-ui.card header="Mail Configuration">
            <div class="mx-auto w-full md:w-2/3">
                {{-- Inner success card --}}
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="rounded-t-lg bg-success px-4 py-3 font-normal text-white">
                        Setting - Mail configuration
                    </div>
                    <form id="email_config" action="{{ routeHelper('setting') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="p-4">
                            <div class="w-full">
                                <input type="hidden" name="type" value="5">
                                <input type="hidden" name="MAIL_DRIVER" value="smtp">
                                <ul class="space-y-3 list-none p-0 m-0">
                                    <li>
                                        <label for="mail_config" class="block text-sm font-medium text-slate-700 capitalize">Mail Configuration</label>
                                        <select name="mail_config" id="mail_config" class="m-2">
                                            @if (setting('mail_config') == 1)
                                                <option value="1">ON</option>
                                                <option value="0">OFF</option>
                                            @else
                                                <option value="0">OFF</option>
                                                <option value="1">ON</option>
                                            @endif
                                        </select>
                                    </li>
                                    <li>
                                        <label for="MAIL_HOST" class="block text-sm font-medium text-slate-700 capitalize">Email Host</label>
                                        <input type="text" name="MAIL_HOST" id="MAIL_HOST"
                                            value="{{ setting('MAIL_HOST') ?? '' }}"
                                            class="mt-2 mr-2 border border-[#ccc] p-[0.4rem]">
                                    </li>
                                    <li>
                                        <label for="MAIL_PORT" class="block text-sm font-medium text-slate-700 capitalize">Port</label>
                                        <input type="text" name="MAIL_PORT" id="MAIL_PORT"
                                            value="{{ setting('MAIL_PORT') ?? '' }}"
                                            class="mt-2 mr-2 border border-[#ccc] p-[0.4rem]">
                                    </li>
                                    <li>
                                        <label for="MAIL_USERNAME" class="block text-sm font-medium text-slate-700 capitalize">Username</label>
                                        <input type="text" name="MAIL_USERNAME" id="MAIL_USERNAME"
                                            value="{{ setting('MAIL_USERNAME') ?? '' }}"
                                            class="mt-2 mr-2 border border-[#ccc] p-[0.4rem]">
                                    </li>
                                    <li>
                                        <label for="MAIL_PASSWORD" class="block text-sm font-medium text-slate-700 capitalize">Password</label>
                                        <input type="text" name="MAIL_PASSWORD" id="MAIL_PASSWORD"
                                            value="{{ setting('MAIL_PASSWORD') ?? '' }}"
                                            class="mt-2 mr-2 border border-[#ccc] p-[0.4rem]">
                                    </li>
                                    <li>
                                        <label for="MAIL_ENCRYPTION" class="block text-sm font-medium text-slate-700 capitalize">Encryption Type</label>
                                        <select name="MAIL_ENCRYPTION" id="MAIL_ENCRYPTION" class="m-2">
                                            @if (setting('MAIL_ENCRYPTION') == 'tls')
                                                <option value="tls">TLS</option>
                                                <option value="ssl">SSL</option>
                                            @else
                                                <option value="ssl">SSL</option>
                                                <option value="tls">TLS</option>
                                            @endif
                                        </select>
                                        <small class="text-blue-600">Selected: {{ Str::upper(setting('MAIL_ENCRYPTION')) }}</small>
                                    </li>
                                    <li>
                                        <label for="MAIL_FROM_ADDRESS" class="block text-sm font-medium text-slate-700 capitalize">Mail From</label>
                                        <input type="email" name="MAIL_FROM_ADDRESS" id="MAIL_FROM_ADDRESS"
                                            value="{{ setting('MAIL_FROM_ADDRESS') ?? '' }}"
                                            class="mt-2 mr-2 border border-[#ccc] p-[0.4rem]">
                                    </li>
                                    <li>
                                        <label for="MAIL_FROM_NAME" class="block text-sm font-medium text-slate-700 capitalize">From Name</label>
                                        <input type="text" name="MAIL_FROM_NAME" id="MAIL_FROM_NAME"
                                            value="{{ setting('MAIL_FROM_NAME') }}"
                                            class="mt-2 mr-2 border border-[#ccc] p-[0.4rem]">
                                    </li>
                                </ul>
                            </div>
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
        </x-ui.card>
    </section>

    {{-- SMS Config --}}
    <section class="mb-6">
        <x-ui.card header="SMS Configuration">
            <div class="mx-auto w-full md:w-2/3">
                {{-- Inner success card --}}
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="rounded-t-lg bg-success px-4 py-3 font-normal text-white">
                        Setting - SMS configuration
                    </div>
                    <form id="sms_config" action="{{ routeHelper('setting') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="p-4">
                            <div class="w-full">
                                <input type="hidden" name="type" value="6">
                                <ul class="space-y-3 list-none p-0 m-0">
                                    <li>
                                        <small class="text-red-600">Purchase SMS and configure from: <a
                                                href="https://bulksmsbd.com/" target="_blank"
                                                rel="noopener noreferrer">www.bulksmsbd.com</a></small>
                                    </li>
                                    <li>
                                        <label for="sms_config_status" class="block text-sm font-medium text-slate-700 capitalize">SMS Configuration</label>
                                        <select name="sms_config_status" id="sms_config_status" class="m-2">
                                            @if (setting('sms_config_status') == 1)
                                                <option value="1">ON</option>
                                                <option value="0">OFF</option>
                                            @else
                                                <option value="0">OFF</option>
                                                <option value="1">ON</option>
                                            @endif
                                        </select>
                                    </li>
                                    <li>
                                        <label for="SMS_API_URL" class="block text-sm font-medium text-slate-700 capitalize">SMS API URL</label>
                                        <input type="text" name="SMS_API_URL" id="SMS_API_URL"
                                            value="{{ setting('SMS_API_URL') ?? '' }}"
                                            class="mt-2 mr-2 border border-[#ccc] p-[0.4rem]">
                                    </li>
                                    <li>
                                        <label for="SMS_API_KEY" class="block text-sm font-medium text-slate-700 capitalize">SMS API KEY</label>
                                        <input type="text" name="SMS_API_KEY" id="SMS_API_KEY"
                                            value="{{ setting('SMS_API_KEY') ?? '' }}"
                                            class="mt-2 mr-2 border border-[#ccc] p-[0.4rem]">
                                    </li>
                                    <li>
                                        <label for="SMS_API_SENDER_ID" class="block text-sm font-medium text-slate-700 capitalize">SENDER ID</label>
                                        <input type="text" name="SMS_API_SENDER_ID" id="SMS_API_SENDER_ID"
                                            value="{{ setting('SMS_API_SENDER_ID') }}"
                                            class="mt-2 mr-2 border border-[#ccc] p-[0.4rem]">
                                    </li>
                                </ul>
                            </div>
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
        </x-ui.card>
    </section>
@endsection
