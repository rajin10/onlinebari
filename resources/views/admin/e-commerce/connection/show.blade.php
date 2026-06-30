@extends('layouts.admin.app')

@section('title', 'mails List')

@section('content')

    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">mails List</h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-slate-700">Home</a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-slate-700">mails List</li>
            </ol>
        </div>
    </section>

    <section>

        <x-ui.card>
            <table class="w-full text-sm text-slate-700">
                <tr class="border-b border-slate-100">
                    <th class="py-2 pr-4 text-left font-medium text-slate-900 w-32">Name :</th>
                    <td class="py-2">{{ $mail->name }}</td>
                </tr>
                <tr class="border-b border-slate-100">
                    <th class="py-2 pr-4 text-left font-medium text-slate-900 w-32">Email :</th>
                    <td class="py-2">{{ $mail->email }}</td>
                </tr>
                <tr class="border-b border-slate-100">
                    <th class="py-2 pr-4 text-left font-medium text-slate-900 w-32">Phone :</th>
                    <td class="py-2">{{ $mail->phone }}</td>
                </tr>
                <tr class="border-b border-slate-100">
                    <th class="py-2 pr-4 text-left font-medium text-slate-900 w-32">Subject :</th>
                    <td class="py-2">{{ $mail->title }}</td>
                </tr>
                <tr class="border-b border-slate-100">
                    <th class="py-2 pr-4 text-left font-medium text-slate-900 w-32">Body :</th>
                    <td class="py-2">{{ $mail->body }}</td>
                </tr>
                @if (!empty($mail->meet))
                    <tr class="border-b border-slate-100">
                        <th class="py-2 pr-4 text-left font-medium text-slate-900 w-32">Meet Time :</th>
                        <td class="py-2">{{ $mail->meet }}</td>
                    </tr>
                @endif
                <tr class="border-b border-slate-100">
                    <th class="py-2 pr-4 text-left font-medium text-slate-900 w-32">Photo :</th>
                    <td class="py-2"></td>
                </tr>
                <tr>
                    <td colspan="2" class="py-2">
                        <img src="{{ asset('/') }}uploads/contact/{{ $mail->documents }}">
                    </td>
                </tr>
            </table>
        </x-ui.card>

    </section>

@endsection
