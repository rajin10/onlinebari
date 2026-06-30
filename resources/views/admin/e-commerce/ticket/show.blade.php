@extends('layouts.admin.app')

@section('title', 'Ticket List')

@section('content')

    <section class="py-4">
        <form action="{{ route('admin.ticket.update') }}" method="POST">
            @csrf
            <x-ui.card>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Username:</label>
                    <input type="text" id="name" name="name" value="{{ $tickets->user->username ?? '' }}"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                        readonly>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Description:</label>
                    <textarea id="description" cols="5" placeholder="Write size description"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                        readonly>{{ $tickets->body }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="replay" class="block text-sm font-medium text-slate-700 mb-1">Admin reply:</label>
                    <textarea id="replay" cols="5" name="replay" placeholder="Write  Replay"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
                    <input type="hidden" value="{{ $tickets->id }}" name="gurdmen">
                </div>

                <x-slot:footer>
                    <x-ui.button type="submit" variant="primary" class="mt-1">
                        <i class="fas fa-arrow-circle-up"></i>
                        Update
                    </x-ui.button>
                </x-slot:footer>
            </x-ui.card>
        </form>
    </section>

@endsection
