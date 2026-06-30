@extends('layouts.admin.app')

@section('title', 'Settings')


@push('css')
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            display: none !important
        }
    </style>
@endpush
@section('content')

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="">
            <div class="flex flex-wrap items-center justify-between mb-2">
                <div class="w-full sm:w-auto">
                    <h1 class="text-2xl font-semibold text-slate-800">Setting</h1>
                </div>
                <div class="w-full sm:w-auto">
                    <ol class="flex items-center gap-1 text-sm text-slate-500 sm:ml-auto">
                        <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                        <li><span class="mx-1">/</span></li>
                        <li class="text-slate-700">My Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="mb-6">
        <x-ui.card header="Application Settings">
            <div class="">
                <form action="{{ route('admin.setting_g') }}" method="POST">
                    @csrf
                    {{-- Manual/Offline Pay section --}}
                    <div class="flex flex-wrap -mx-2 mb-4">
                        <h1 class="w-full mx-2 mb-4 rounded bg-tile-success px-3 py-[10px] text-center text-[25px] text-white">Manual/Offline Pay</h1>
                        <div class="w-full px-2 md:w-1/2 mb-4">
                            <label class="flex text-[20px]"><input type="checkbox" name="bkash" class="mr-[5px]"
                                    @if (setting('g_bkash') == 'true') checked @endif> Bkash</label>
                            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="bkash" id="bkash"
                                value="{{ setting('bkash') ?? '01721*****' }}">
                        </div>
                        <div class="w-full px-2 md:w-1/2 mb-4">
                            <label class="flex text-[20px]"><input type="checkbox" name="nagad" class="mr-[5px]"
                                    @if (setting('g_nagad') == 'true') checked @endif> Nagad</label>
                            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="nagad" id="nagad"
                                value="{{ setting('nagad') ?? '01721*****' }}">
                        </div>
                        <div class="w-full px-2 md:w-1/2 mb-4">
                            <label class="flex text-[20px]"><input type="checkbox" name="rocket" class="mr-[5px]"
                                    @if (setting('g_rocket') == 'true') checked @endif> Rocket</label>
                            <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="rocket" id="rocket"
                                value="{{ setting('rocket') ?? '01721*****' }}">
                        </div>
                        <div class="w-full px-2 md:w-1/2 mb-4">
                            <label class="flex text-[20px]"><input type="checkbox" name="wallate" class="mr-[5px]"
                                    @if (setting('g_wallate') == 'true') checked @endif> Wallate</label>
                            <p>Wallet means, the store point withdrawn amount using for purchase proudcts</p>
                        </div>
                    </div>

                    <hr class="border-slate-200 mb-4">

                    {{-- Bank / COD section --}}
                    <div class="flex flex-wrap -mx-2 mb-4">
                        <div class="w-full px-2 md:w-1/2 mb-4">
                            <div class="bg-slate-200 py-4 px-3 h-full">
                                <label class="flex text-[20px]"><input type="checkbox" name="bank" class="mr-[5px]"
                                        @if (setting('g_bank') == 'true') checked @endif> Bank</label>
                                <label for="bank_name" class="flex text-[20px] capitalize">Bank Name</label>
                                <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary mb-4" type="text" name="bank_name" id="bank_name"
                                    value="{{ setting('bank_name') ?? 'Bangladesh Bank' }}">
                                <label for="bank_account" class="flex text-[20px] capitalize">Bank A/C Number</label>
                                <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary mb-4" type="number" name="bank_account" id="bank_account"
                                    value="{{ setting('bank_account') ?? '000000000' }}">
                                <label for="branch_name" class="flex text-[20px] capitalize">Branch Name</label>
                                <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary mb-4" type="text" name="branch_name" id="branch_name"
                                    value="{{ setting('branch_name') ?? 'Kishoreganj Sadar' }}">
                                <label for="holder_name" class="flex text-[20px] capitalize">Account Holder Name (Capital
                                    Letter)</label>
                                <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary mb-4" type="text" name="holder_name" id="holder_name"
                                    value="{{ setting('holder_name') ?? 'Kishoreganj Sadar' }}">
                                <label for="routing" class="flex text-[20px] capitalize">Routing</label>
                                <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" type="text" name="routing" id="routing"
                                    value="{{ setting('routing') ?? 'BDBL000' }}">
                            </div>
                        </div>

                        <div class="w-full px-2 md:w-1/2 mb-4">
                            <div class="bg-slate-200 py-4 px-3 h-full">
                                <label class="flex text-[20px]"><input type="checkbox" name="cod" class="mr-[5px]"
                                        @if (setting('g_cod') == 'true') checked @endif> COD&nbsp;<small>(Cash On
                                        Delivery)</small></label>
                                <hr class="border-slate-300 my-2">
                                <p class="text-warning">All Charges are not related with COD, it will effect also all
                                    payment getway</p>
                                <label for="shipping_free_above" class="flex text-[20px] capitalize">Shipping Charge Free Above
                                    Amount</label>
                                <input name="shipping_free_above" id="shipping_free_above" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary mb-4"
                                    type="text" value="{{ setting('shipping_free_above') ?? 10000 }}">

                                <label for="shipping_range_inside" class="flex text-[20px] capitalize">Text - Shipping in
                                    Range</label>
                                <input name="shipping_range_inside" id="shipping_range_inside" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary mb-4"
                                    type="text" value="{{ setting('shipping_range_inside') ?? 'Dhaka' }}">

                                <label for="shipping_charge" class="flex text-[20px] capitalize">Shipping Charge Inside
                                    Range</label>
                                <input name="shipping_charge" id="shipping_charge" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary mb-4"
                                    type="text" value="{{ setting('shipping_charge') ?? 80 }}">

                                <label for="shipping_charge_out_of_range" class="flex text-[20px] capitalize">Shipping Charge Out
                                    Of Range</label>
                                <input name="shipping_charge_out_of_range" id="shipping_charge_out_of_range"
                                    class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                                    type="text"
                                    value="{{ setting('shipping_charge_out_of_range') ?? 130 }}">
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-200 mb-4">

                    {{-- Online Pay section --}}
                    <div class="flex flex-wrap -mx-2 mb-4">
                        <div class="w-full px-2 sm:w-1/2 mb-4 bg-[#45b03c80]">
                            <h1 class="w-full mb-4 rounded bg-tile-success px-3 py-[10px] text-center text-[25px] text-white">Online Pay- aamarPay</h1>
                            <div class="mb-4">
                                <label class="flex text-[20px]">StoreID</label>
                                <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" name="astore"
                                    value="{{ setting('astore') }}">
                            </div>
                            <div class="mb-4">
                                <label class="flex text-[20px]">Signature key</label>
                                <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" name="akey"
                                    value="{{ setting('akey') }}">
                            </div>
                            <div class="mb-4">
                                <label class="flex text-[20px]"> Mode</label>
                                <select name="amode" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                    <option @if (setting('amode') == '1') selected @endif value="1">
                                        Sandbox
                                    </option>
                                    <option @if (setting('amode') == '2') selected @endif value="2">Live
                                    </option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="flex text-[20px]"><input type="checkbox" name="aamar" class="mr-[5px]"
                                        @if (setting('g_aamar') == 'true') checked @endif> is active</label>
                            </div>
                        </div>
                        <div class="w-full px-2 sm:w-1/2 mb-4 bg-[#80b8ea6e]">
                            <h1 class="w-full mb-4 rounded bg-[#2f84d0] px-3 py-[10px] text-center text-[25px] text-white">Online Pay- UddoktaPay</h1>
                            <div class="mb-4">
                                <label class="flex text-[20px]">API Key</label>
                                <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" name="uapi"
                                    value="{{ setting('uapi') }}">
                            </div>
                            <div class="mb-4">
                                <label class="flex text-[20px]">Base Url</label>
                                <input class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary" name="ubase"
                                    value="{{ setting('ubase') }}">
                            </div>
                            <div class="mb-4">
                                <label class="flex text-[20px]"> Mode</label>
                                <select name="umode" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary">
                                    <option @if (setting('umode') == '1') selected @endif value="1">
                                        Sandbox
                                    </option>
                                    <option @if (setting('umode') == '2') selected @endif value="2">Live
                                    </option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="flex text-[20px]"><input type="checkbox" name="uddok" class="mr-[5px]"
                                        @if (setting('g_uddok') == 'true') checked @endif> is active</label>
                            </div>
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
        </x-ui.card>
    </section>
    <!-- /.content -->

@endsection

@push('js')
    <script src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(function() {
            $('#cover_photo').dropify();
            $('.select2').select2();
        });
    </script>
@endpush
