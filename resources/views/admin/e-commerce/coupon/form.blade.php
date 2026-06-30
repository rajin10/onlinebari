@extends('layouts.admin.app')

@section('title')
    @isset($coupon)
        Edit Coupon
    @else
        Add Coupon
    @endisset
@endsection

@section('content')
    {{-- Page header --}}
    <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">
            @isset($coupon)
                Edit Coupon
            @else
                Add Coupon
            @endisset
        </h1>
        <ol class="flex items-center gap-1 text-sm text-slate-500">
            <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
            <li class="before:mr-1 before:content-['/']">
                @isset($coupon)
                    Edit Coupon
                @else
                    Add Coupon
                @endisset
            </li>
        </ol>
    </div>

    {{-- Main content --}}
    <div class="mx-auto w-full md:w-2/3">
        {{-- Card shell — built as plain div so <form> can span body + footer --}}
        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">

            {{-- Card header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                <h3 class="font-medium text-slate-900">
                    @isset($coupon)
                        Edit Coupon
                    @else
                        Add New Coupon
                    @endisset
                </h3>
                <div class="flex items-center gap-2">
                    @isset($coupon)
                        <x-ui.button variant="info" size="sm" :href="routeHelper('coupon/' . $coupon->id)">
                            <i class="fas fa-eye"></i>
                            Show
                        </x-ui.button>
                    @endisset
                    <x-ui.button variant="danger" size="sm" :href="routeHelper('coupon')">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>
            </div>

            {{-- Form spanning body + footer --}}
            <form action="{{ isset($coupon) ? routeHelper('coupon/' . $coupon->id) : routeHelper('coupon') }}"
                method="POST">
                @csrf
                @isset($coupon)
                    @method('PUT')
                @endisset

                {{-- Card body --}}
                <div class="p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        {{-- Coupon Code --}}
                        <x-ui.input
                            name="code"
                            label="Coupon Code:"
                            type="text"
                            placeholder="Write coupon code"
                            :value="$coupon->code ?? old('code')"
                            required
                            autocomplete="off"
                        />

                        {{-- Discount Type --}}
                        <x-ui.select name="discount_type" label="Discount Type:">
                            <option value="percent">Percent</option>
                            <option value="amount">Fixed Amount</option>
                        </x-ui.select>

                        {{-- Discount Amount/Percent --}}
                        <x-ui.input
                            name="discount"
                            label="Discount Amount/Percent:"
                            type="text"
                            placeholder="Write discount amount/percent"
                            :value="$coupon->discount ?? old('discount')"
                            required
                        />

                        {{-- Use Limit Per User --}}
                        <x-ui.input
                            name="limit_per_user"
                            label="Use Limit Per User:"
                            type="number"
                            placeholder="Enter use limit per user"
                            :value="$coupon->limit_per_user ?? old('limit_per_user')"
                            required
                        />

                        {{-- Total Use Limit --}}
                        <x-ui.input
                            name="total_use_limit"
                            label="Total Use Limit:"
                            type="number"
                            placeholder="Enter total use limit"
                            :value="$coupon->total_use_limit ?? old('total_use_limit')"
                            required
                        />

                        {{-- Expire Date --}}
                        <x-ui.input
                            name="expire_date"
                            label="Expire Date:"
                            type="date"
                            :value="$coupon->expire_date ?? old('expire_date')"
                            required
                        />
                    </div>

                    {{-- Description --}}
                    <div class="mt-4">
                        <x-ui.textarea
                            name="description"
                            label="Description:"
                            rows="2"
                            placeholder="Write coupon description"
                        >{{ $coupon->description ?? old('description') }}</x-ui.textarea>
                    </div>

                    {{-- Status toggle --}}
                    <div class="mt-2">
                        <label for="status" class="inline-flex cursor-pointer items-center gap-3">
                            <div class="relative">
                                <input
                                    type="checkbox"
                                    class="peer sr-only"
                                    name="status"
                                    id="status"
                                    @isset($size) {{ $size->status ? 'checked' : '' }} @else checked @endisset
                                >
                                {{-- track --}}
                                <div class="h-6 w-11 rounded-full bg-slate-300 transition-colors peer-checked:bg-primary peer-focus-visible:ring-2 peer-focus-visible:ring-primary/50"></div>
                                {{-- knob --}}
                                <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></div>
                            </div>
                            <span class="text-sm font-medium text-slate-700">Status</span>
                        </label>
                        @error('status')
                            <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Card footer --}}
                <div class="border-t border-slate-200 px-4 py-3">
                    <x-ui.button type="submit" variant="primary">
                        @isset($tag)
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
                        @else
                            <i class="fas fa-plus-circle"></i>
                            Submit
                        @endisset
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>
@endsection
