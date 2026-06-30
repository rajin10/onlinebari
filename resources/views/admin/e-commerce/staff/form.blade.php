@extends('layouts.admin.app')

@section('title')
    @isset($staff)
        Edit staff
    @else
        Add staff
    @endisset
@endsection


@section('content')
    <!-- Content Header -->
    <section class="mb-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h1 class="text-2xl font-semibold text-slate-800">
                @isset($staff)
                    Edit staff
                @else
                    Add staff
                @endisset
            </h1>
            <ol class="flex items-center gap-1 text-sm text-slate-500">
                <li><a href="{{ routeHelper('dashboard') }}" class="hover:underline">Home</a></li>
                <li class="before:content-['/'] before:mx-1">
                    @isset($staff)
                        Edit staff
                    @else
                        Add staff
                    @endisset
                </li>
            </ol>
        </div>
    </section>

    <!-- Main content -->
    <section>
        @if ($errors->any())
            <div class="mb-4">
                @foreach ($errors->all() as $error)
                    <x-ui.alert variant="danger" class="mb-2">{{ $error }}</x-ui.alert>
                @endforeach
            </div>
        @endif

        <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <!-- Card Header -->
            <div class="border-b border-slate-200 px-4 py-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-slate-800">
                        @isset($staff)
                            Edit staff
                        @else
                            Add New staff
                        @endisset
                    </h3>
                    <x-ui.button variant="danger" :href="routeHelper('staff')">
                        <i class="fas fa-long-arrow-alt-left"></i>
                        Back to List
                    </x-ui.button>
                </div>
            </div>

            <!-- Form wraps body + footer -->
            <form action="{{ isset($staff) ? routeHelper('staff/' . $staff->id) : route('admin.staff.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Card Body -->
                <div class="p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <x-ui.input name="name" label="Name:" type="text"
                            placeholder="Write staff name"
                            :value="$staff->name ?? old('name')"
                            required />

                        <x-ui.input name="username" label="Username (unique):" type="text"
                            placeholder="Write staff username"
                            :value="$staff->username ?? old('username')"
                            required />

                        <x-ui.input name="email" label="Email:" type="email"
                            placeholder="example@gmail.com"
                            :value="$staff->email ?? old('email')"
                            required />

                        <x-ui.input name="phone" label="Phone:" type="text"
                            placeholder="write staff phone number"
                            :value="$staff->phone ?? old('phone')"
                            required />

                        <div class="space-y-1">
                            <label for="profile" class="block text-sm font-medium text-slate-700">Profile:</label>
                            <input type="file" name="profile" id="profile"
                                class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm file:mr-3 file:rounded file:border-0 file:bg-slate-100 file:px-3 file:py-1 file:text-sm focus:border-primary focus:ring-1 focus:ring-primary @error('profile') border-danger @enderror"
                                required>
                            @error('profile')
                                <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <x-ui.select name="position" label="Select Role:" required>
                                <option value="1">Admin</option>
                                <option value="2">Manager</option>
                                <option value="3">Product Manager</option>
                                <option value="4">Delivery Manager</option>
                            </x-ui.select>
                            @error('method')
                                <p class="text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!--   Commented-out fields (country, city, street, post_code) preserved below -->
                        <!--   <div class="space-y-1">
                                <label for="country" class="block text-sm font-medium text-slate-700">Country:</label>
                                <input type="text" name="country" id="country" placeholder="write staff country name" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('country') border-danger @enderror" value="{{ $staff->staff_info->country ?? old('country') }}" required>
                                @error('country')
                                    <p class="text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div> -->
                        <!--  <div class="space-y-1">
                                <label for="city" class="block text-sm font-medium text-slate-700">City:</label>
                                <input type="text" name="city" id="city" placeholder="write staff city name" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('city') border-danger @enderror" value="{{ $staff->staff_info->city ?? old('city') }}" required>
                                @error('city')
                                    <p class="text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div> -->
                        <!--  <div class="space-y-1">
                                <label for="street" class="block text-sm font-medium text-slate-700">Street:</label>
                                <input type="text" name="street" id="street" placeholder="write staff street" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('street') border-danger @enderror" value="{{ $staff->staff_info->street ?? old('street') }}" required>
                                @error('street')
                                    <p class="text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div> -->
                        <!--   <div class="space-y-1">
                                <label for="post_code" class="block text-sm font-medium text-slate-700">Post Code:</label>
                                <input type="text" name="post_code" id="post_code" placeholder="write staff post code" class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('post_code') border-danger @enderror" value="{{ $staff->staff_info->post_code ?? old('post_code') }}" required>
                                @error('post_code')
                                    <p class="text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div> -->

                        @isset($staff)
                        @else
                            <x-ui.input name="password" label="Password:" type="password"
                                placeholder="********"
                                required />

                            <x-ui.input name="password_confirmation" label="Confirm Password:" type="password"
                                placeholder="********"
                                required />
                        @endisset
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="border-t border-slate-200 px-4 py-3">
                    <x-ui.button variant="primary" type="submit">
                        @isset($staff)
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
    </section>
@endsection
