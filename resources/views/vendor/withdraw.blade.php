@extends('layouts.vendor.app')

@section('title', 'Withdraw')

@push('css')

@endpush

@section('content')

<section class="mb-6">
    <div class="flex justify-center">
        <div class="w-full md:w-1/2">
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                {{-- Card header --}}
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <h3 class="text-base font-semibold text-slate-900">Withdraw</h3>
                    <x-ui.button :href="route('vendor.withdraw.list')" variant="primary" size="sm">
                        History
                    </x-ui.button>
                </div>

                {{-- Form wraps body + footer so submit covers both --}}
                <form action="{{ route('vendor.withdraw.create') }}" method="POST">
                    @csrf

                    {{-- Card body --}}
                    <div class="p-4 space-y-4">
                        <div class="mb-4">
                            <x-ui.input
                                name="amount"
                                label="Withdraw Amount:"
                                type="number"
                                placeholder="amount"
                            />
                        </div>

                        <div class="mb-4 w-full md:w-1/2">
                            <x-ui.select name="method" label="Select Type:" required>
                                <option value="1">Bkash</option>
                                <option value="2">Nagad</option>
                                <option value="3">Rocket</option>
                                <option value="4">Bank</option>
                            </x-ui.select>
                            @error('method')
                                <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Card footer --}}
                    <div class="border-t border-slate-200 px-4 py-3">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-arrow-circle-up"></i>
                            Withdraw
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@push('js')

@endpush
