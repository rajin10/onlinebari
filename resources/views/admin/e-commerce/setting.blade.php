@extends('layouts.admin.app')

@section('title', 'Settings')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }
    </style>
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="mb-4">
        <div class="">
            <div class="flex flex-wrap items-center justify-between mb-2">
                <div class="w-full md:w-1/2">
                    <h1 class="text-2xl font-semibold text-slate-800">Setting</h1>
                </div>
                <div class="w-full md:w-1/2">
                    <ol class="flex items-center gap-1 text-sm text-slate-500 md:justify-end">
                        <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
                        <li class="text-slate-400">/</li>
                        <li class="text-slate-700">My Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="mb-6">
        <x-ui.card header="Application Settings">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

                {{-- Application Images card (col-12 col-md-4) --}}
                <div class="md:col-span-4">
                    <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 px-4 py-3 font-medium text-white bg-success rounded-t-lg">
                            Application Images
                        </div>
                        <form action="{{ routeHelper('setting/logo') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="p-4">
                                <label for="logo" class="block text-sm font-medium text-slate-700 mb-1">Logo</label>
                                <input type="file" name="logo" id="logo"
                                    class="w-full @error('logo') border-danger @enderror"
                                    data-default-file="{{ '/uploads/setting/' . setting('logo') }}">
                                @error('logo')
                                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="p-4" id="meta_img_wrap">
                                <label for="auth_logo" class="block text-sm font-medium text-slate-700 mb-1">Meta Imgaes 1200*627px</label>
                                <input type="file" name="auth_logo" id="auth_logo"
                                    class="w-full @error('auth_logo') border-danger @enderror"
                                    data-default-file="{{ '/uploads/setting/' . setting('auth_logo') }}">
                                @error('auth_logo')
                                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                @enderror
                                <a href="{{ route('admin.setting.seo') }}" class="text-primary hover:underline text-sm mt-1 inline-block">Meta Data <i class="fas fa-caret-right"></i></a>
                            </div>

                            <div class="p-4">
                                <label for="favicon" class="block text-sm font-medium text-slate-700 mb-1">Favicon</label>
                                <input type="file" name="favicon" id="favicon"
                                    class="w-full @error('favicon') border-danger @enderror"
                                    data-default-file="{{ '/uploads/setting/' . setting('favicon') }}">
                                @error('favicon')
                                    <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                @enderror
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

                {{-- Update Notice card (col-12 col-md-8) --}}
                <div class="md:col-span-8">
                    <div class="rounded-lg border border-slate-200 bg-secondary text-white mb-3 shadow-sm">
                        <div class="border-b border-white/20 px-4 py-3 font-medium">Update Notice</div>
                        <div class="p-4">
                            <h5 class="font-semibold mb-2">We are allways for there for support.</h5>
                            <p>We are coming with new innovation @Finvasoft</p>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-sm-8">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Setting</h3>
                        </div>
                        <form action="{{routeHelper('setting')}}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="type" value="1">
                            <div class="card-body">
                                <div class="form-row">
                                    @foreach ($settings as $key => $setting)
                                    @php
                                    $name = str_replace('_', ' ', $setting->name);
                                    @endphp
                                    @if ($setting->name == 'footer_description')
                                    <div class="form-group col-md-12 {{$setting->name}}">
                                        <label for="{{$setting->name}}" class="text-capitalize">{{$name}}</label>
                                        <textarea name="{{$setting->name}}" id="{{$setting->name}}" rows="4"
                                            class="form-control @error($setting->name) is-invalid @enderror">{{$setting->value}}</textarea>
                                        @error($setting->name)
                                        <small class="form-text text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    @elseif ($setting->name == 'fb_pixel')
                                    <div class="form-group col-md-12 {{$setting->name}}">
                                        <label for="{{$setting->name}}" class="text-capitalize">{{$name}}</label>
                                        <textarea name="{{$setting->name}}" id="{{$setting->name}}" rows="4"
                                            class="form-control @error($setting->name) is-invalid @enderror">{{$setting->value}}</textarea>
                                        @error($setting->name)
                                        <small class="form-text text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    @elseif ($setting->name == 'is_point')
                                    <div class="form-group col-md-12 {{$setting->name}}">
                                        <label for="{{$setting->name}}" class="text-capitalize">{{$name}}</label>
                                        <select name="{{$setting->name}}" id="{{$setting->name}}">
                                            <option @if ($setting->value == '1')selected @endif value="1">Active</option>
                                            <option @if ($setting->value == '0')selected @endif value="0">Deactive</option>
                                        </select>
                                        @error($setting->name)
                                        <small class="form-text text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    @elseif ($setting->name == 'mega_cat')
                                    @elseif ($setting->name == 'sub_cat')
                                    @elseif ($setting->name == 'mini_cat')
                                    @elseif ($setting->name == 'extra_cat')
                                    @elseif ($setting->name == 'g_bkash')
                                    @elseif ($setting->name == 'g_nagad')
                                    @elseif ($setting->name == 'g_rocket')
                                    @elseif ($setting->name == 'g_bank')
                                    @elseif ($setting->name == 'g_wallate')
                                    @elseif ($setting->name == 'g_cod')
                                    @elseif ($setting->name == 'g_aamar')
                                    @elseif ($setting->name == 'g_uddok')
                                    @elseif ($setting->name == 'meta_img')
                                    @elseif ($setting->name == 'uapi')
                                    @elseif ($setting->name == 'astore')
                                    @elseif ($setting->name == 'akey')
                                    @elseif ($setting->name == 'amode')
                                    @elseif ($setting->name == 'umode')
                                    @elseif ($setting->name == 'ubase')

                                    @else

                                    <div class="form-group col-md-6 {{$setting->name}}">
                                        <label for="{{$setting->name}}" class="text-capitalize">{{$name}}</label>
                                        <input type="text" name="{{$setting->name}}" value="{{$setting->value}}"
                                            class="form-control @error($setting->name) is-invalid @enderror">
                                        @error($setting->name)
                                        <small class="form-text text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    @endif

                                    @endforeach
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div> --}}

            </div>

        </x-ui.card>
    </section>
    <!-- /.content -->

@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('input[type="file"]').dropify();
            $('.col-md-6.logo').remove();
            $('.col-md-6.auth_logo').remove();
            $('.col-md-6.favicon').remove();
        });
    </script>
@endpush
