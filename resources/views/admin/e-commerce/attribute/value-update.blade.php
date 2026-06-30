@extends('layouts.admin.app')

@section('title')
    Update attribute Value
@endsection

@section('content')
    {{-- Page header --}}
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">Update attribute value</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                <li class="before:mr-1 before:content-['/']">Update attribute value</li>
            </ol>
        </div>
    </section>

    {{-- Main content --}}
    <section>
        <div class="mx-auto max-w-3xl">
            <x-ui.card header="Update New attribute value">
                <form action="{{ route('admin.attribute.value.update') }}" method="POST">
                    @csrf
                    <input type="hidden" value="{{ $value->id }}" name="att">

                    <div class="mb-4">
                        <x-ui.input
                            name="name"
                            label="Value Name:"
                            type="text"
                            :value="$value->name ?? old('name')"
                            placeholder="Write attribute value name"
                            required
                            autocomplete="off"
                        />
                    </div>

                    <div class="border-t border-slate-200 pt-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-plus-circle"></i>
                            Update
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </section>
@endsection
