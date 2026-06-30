@extends('layouts.admin.app')

@section('title', 'Settings')


@section('content')

    {{-- Page header --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <h1 class="text-2xl font-semibold text-slate-800">Setting &ndash; <small class="text-base font-normal text-slate-500">Color</small></h1>
        <ol class="flex items-center gap-1 text-sm text-slate-500">
            <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
            <li class="before:mx-1 before:content-['/']">My Profile</li>
        </ol>
    </div>

    {{-- Main content --}}
    <x-ui.card :header="'Application Settings'">
        <div class="flex justify-center">
            <div class="w-full max-w-2xl">
                {{-- Inner card with form spanning body + footer --}}
                <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-200 px-4 py-3 font-medium text-slate-900">
                        Setting &ndash; Color Change
                    </div>

                    <form id="color_form" action="{{ routeHelper('setting') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="p-4">
                            <input type="hidden" name="type" value="4">

                            <ul class="list-none space-y-4 p-0">
                                <li class="flex flex-wrap items-center gap-3">
                                    <label for="PRIMARY_COLOR" class="w-64 text-sm font-medium capitalize text-slate-700">Primary Color:</label>
                                    <input type="color" id="PRIMARY_COLOR_CHOOSER"
                                        value="{{ $PRIMARY_COLOR->value }}"
                                        class="h-9 w-12 cursor-pointer rounded border border-slate-300 p-0.5">
                                    <input type="text" id="PRIMARY_COLOR" name="PRIMARY_COLOR"
                                        value="{{ $PRIMARY_COLOR->value }}"
                                        class="w-32 rounded border border-slate-300 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                </li>
                                <li class="flex flex-wrap items-center gap-3">
                                    <label for="PRIMARY_BG_TEXT_COLOR" class="w-64 text-sm font-medium capitalize text-slate-700">Primary Background Text Color:</label>
                                    <input type="color" id="PRIMARY_BG_TEXT_COLOR_CHOOSER"
                                        value="{{ $PRIMARY_BG_TEXT_COLOR->value }}"
                                        class="h-9 w-12 cursor-pointer rounded border border-slate-300 p-0.5">
                                    <input type="text" id="PRIMARY_BG_TEXT_COLOR" name="PRIMARY_BG_TEXT_COLOR"
                                        value="{{ $PRIMARY_BG_TEXT_COLOR->value }}"
                                        class="w-32 rounded border border-slate-300 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                </li>
                                <li class="flex flex-wrap items-center gap-3">
                                    <label for="SECONDARY_COLOR" class="w-64 text-sm font-medium capitalize text-slate-700">Secondary Color:</label>
                                    <input type="color" id="SECONDARY_COLOR_CHOOSER"
                                        value="{{ $SECONDARY_COLOR->value }}"
                                        class="h-9 w-12 cursor-pointer rounded border border-slate-300 p-0.5">
                                    <input type="text" id="SECONDARY_COLOR" name="SECONDARY_COLOR"
                                        value="{{ $SECONDARY_COLOR->value }}"
                                        class="w-32 rounded border border-slate-300 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                </li>
                                <li class="flex flex-wrap items-center gap-3">
                                    <label for="OPTIONAL_COLOR" class="w-64 text-sm font-medium capitalize text-slate-700">Optional Color:</label>
                                    <input type="color" id="OPTIONAL_COLOR_CHOOSER"
                                        value="{{ $OPTIONAL_COLOR->value }}"
                                        class="h-9 w-12 cursor-pointer rounded border border-slate-300 p-0.5">
                                    <input type="text" id="OPTIONAL_COLOR" name="OPTIONAL_COLOR"
                                        value="{{ $OPTIONAL_COLOR->value }}"
                                        class="w-32 rounded border border-slate-300 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                </li>
                                <li class="flex flex-wrap items-center gap-3">
                                    <label for="OPTIONAL_BG_TEXT_COLOR" class="w-64 text-sm font-medium capitalize text-slate-700">Optional Background Text Color:</label>
                                    <input type="color" id="OPTIONAL_BG_TEXT_COLOR_CHOOSER"
                                        value="{{ $OPTIONAL_BG_TEXT_COLOR->value }}"
                                        class="h-9 w-12 cursor-pointer rounded border border-slate-300 p-0.5">
                                    <input type="text" id="OPTIONAL_BG_TEXT_COLOR" name="OPTIONAL_BG_TEXT_COLOR"
                                        value="{{ $OPTIONAL_BG_TEXT_COLOR->value }}"
                                        class="w-32 rounded border border-slate-300 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                </li>
                                <li class="flex flex-wrap items-center gap-3">
                                    <label for="MAIN_MENU_BG" class="w-64 text-sm font-medium capitalize text-slate-700">Main menu background:</label>
                                    <input type="color" id="MAIN_MENU_BG_CHOOSER"
                                        value="{{ $MAIN_MENU_BG->value }}"
                                        class="h-9 w-12 cursor-pointer rounded border border-slate-300 p-0.5">
                                    <input type="text" id="MAIN_MENU_BG" name="MAIN_MENU_BG"
                                        value="{{ $MAIN_MENU_BG->value }}"
                                        class="w-32 rounded border border-slate-300 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                </li>
                                <li class="flex flex-wrap items-center gap-3">
                                    <label for="MAIN_MENU_ul_li_color" class="w-64 text-sm font-medium capitalize text-slate-700">Main menu item color:</label>
                                    <input type="color" id="MAIN_MENU_ul_li_color_CHOOSER"
                                        value="{{ $MAIN_MENU_ul_li_color->value }}"
                                        class="h-9 w-12 cursor-pointer rounded border border-slate-300 p-0.5">
                                    <input type="text" id="MAIN_MENU_ul_li_color" name="MAIN_MENU_ul_li_color"
                                        value="{{ $MAIN_MENU_ul_li_color->value }}"
                                        class="w-32 rounded border border-slate-300 px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50">
                                </li>
                            </ul>

                            <hr class="mt-4 border-slate-200">
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

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $("#PRIMARY_COLOR_CHOOSER").on("input", function() {
                $("#PRIMARY_COLOR").val($(this).val());
            });
            $("#PRIMARY_COLOR").on("keyup", function() {
                $("#PRIMARY_COLOR_CHOOSER").val($(this).val());
            });

            $("#PRIMARY_BG_TEXT_COLOR_CHOOSER").on("input", function() {
                $("#PRIMARY_BG_TEXT_COLOR").val($(this).val());
            });
            $("#PRIMARY_BG_TEXT_COLOR").on("keyup", function() {
                $("#PRIMARY_BG_TEXT_COLOR_CHOOSER").val($(this).val());
            });

            $("#SECONDARY_COLOR_CHOOSER").on("input", function() {
                $("#SECONDARY_COLOR").val($(this).val());
            });
            $("#SECONDARY_COLOR").on("keyup", function() {
                $("#SECONDARY_COLOR_CHOOSER").val($(this).val());
            });

            $("#OPTIONAL_COLOR_CHOOSER").on("input", function() {
                $("#OPTIONAL_COLOR").val($(this).val());
            });
            $("#OPTIONAL_COLOR").on("keyup", function() {
                $("#OPTIONAL_COLOR_CHOOSER").val($(this).val());
            });

            $("#OPTIONAL_BG_TEXT_COLOR_CHOOSER").on("input", function() {
                $("#OPTIONAL_BG_TEXT_COLOR").val($(this).val());
            });
            $("#OPTIONAL_BG_TEXT_COLOR").on("keyup", function() {
                $("#OPTIONAL_BG_TEXT_COLOR_CHOOSER").val($(this).val());
            });

            $("#MAIN_MENU_BG_CHOOSER").on("input", function() {
                $("#MAIN_MENU_BG").val($(this).val());
            });
            $("#MAIN_MENU_BG").on("keyup", function() {
                $("#MAIN_MENU_BG_CHOOSER").val($(this).val());
            });

            $("#MAIN_MENU_ul_li_color_CHOOSER").on("input", function() {
                $("#MAIN_MENU_ul_li_color").val($(this).val());
            });
            $("#MAIN_MENU_ul_li_color").on("keyup", function() {
                $("#MAIN_MENU_ul_li_color_CHOOSER").val($(this).val());
            });


        });
    </script>
@endpush
