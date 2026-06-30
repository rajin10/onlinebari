@extends('layouts.admin.app')

@section('title')
    @isset($campaing)
        Edit campaing
    @else
        Add campaing
    @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <style>
        /* Dropify widget internals — cannot be expressed as Tailwind utilities */
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }

        /* select2 widget internal — targets third-party pseudo-element scope */
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            display: none !important
        }
    </style>
@endpush

@section('content')
    {{-- Page header --}}
    <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold text-slate-800">
            @isset($campaing)
                Edit campaing
            @else
                Add campaing
            @endisset
        </h1>
        <ol class="flex items-center gap-1 text-sm text-slate-500">
            <li><a href="{{ routeHelper('dashboard') }}" class="hover:text-primary">Home</a></li>
            <li class="before:mr-1 before:content-['/']">
                @isset($campaing)
                    Edit campaing
                @else
                    Add campaing
                @endisset
            </li>
        </ol>
    </div>

    {{-- Main content --}}
    <div class="w-full">
        <x-ui.card>
            <x-slot:header>
                <span class="text-base font-semibold text-slate-800">
                    @isset($campaing)
                        Edit campaing Details
                    @else
                        Add New Campaign
                    @endisset
                </span>
            </x-slot:header>

            <form action="{{ isset($campaing) ? route('admin.campaing.update') : route('admin.campaing.store') }}"
                method="POST" enctype="multipart/form-data">

                @csrf

                {{-- Name --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name:</label>
                    <input type="text" name="name" id="name" placeholder="Write campaing name"
                        class="block w-full rounded-md border px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('name') border-danger @else border-slate-300 @enderror"
                        value="{{ $campaing->name ?? old('name') }}" required autocomplete="off">
                    <input type="hidden" name="camid" value="{{ $campaing->id ?? '' }}"" id="camid">
                    @error('name')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cover Photo (file input — JS dropify() hooks on #cover_photo, keep raw input) --}}
                <div class="mb-4">
                    <label for="cover_photo" class="block text-sm font-medium text-slate-700 mb-1">Cover Photo:</label>
                    <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm @error('cover_photo') border-danger @enderror"
                        data-default-file="@isset($campaing) {{ asset('/') }}/uploads/campaign/{{ $campaing->cover_photo }}@enderror">
                    @error('cover_photo')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status toggle --}}
                <div class="mb-4">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" name="status" id="status" ___inline_directive___________________________________________________________________________2___>
                        <span class="text-sm font-medium text-slate-700">Status</span>
                    </label>
                    @error('status')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Flash sell toggle --}}
                <div class="mb-4">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" name="flash" id="flash" ___inline_directive__________________________________________________________________3___>
                        <span class="text-sm font-medium text-slate-700">flash Sell</span>
                    </label>
                    @error('flash')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Flash end date (shown/hidden by JS via #flas_end innerHTML) --}}
                <div class="mb-4" id="flas_end">
                    <?php if (isset($campaing)) {?>
                    @if ($campaing->is_flash == 1)
                        <label for="flash_end" class="block text-sm font-medium text-slate-700 mb-1">Flash end:</label>
                        <input type="datetime-local" name="flash_end" id="flash_end"
                            class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary"
                            value="{{ $campaing->end }}" >'
                    @endif
                    <?php }?>
                    @error('end')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <?php if (isset($campaing)) {?>

                {{-- Product select (select2 multi-select) --}}
                <div class="mb-4">
                    <label for="product" class="block text-sm font-medium text-slate-700 mb-1">Select Product:</label>
                    <select name="products[]" id="product" multiple data-placeholder="Select Product"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary select2 ___inline_directive________________4___"
                        {{ isset($campaing) ? '' : 'required' }} data-selected-text-format="count">
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option
                                <?php if (isset($campaing)) {?>
                                ___inline_directive____________________________________________________________________________________________________________________________________________5___each
                                <?php }?>
                                value="{{ $product->id }}"> {{ $product->title }} </option>
                        @endforeach
                    </select>
                    @error('categories')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campaign products table --}}
                <div class="mb-4">
                    <x-ui.table id="example1">
                        <thead>
                            <tr>
                                <th>Cover Photo</th>
                                <th>Name</th>
                                <th>Regular Price</th>
                                <th>Campaing Price</th>
                            </tr>
                        </thead>
                        <tbody id="discount_table">
                            <a href=""></a>
                            @isset($campaing)
                            <?php
                            foreach ($campaing->campaing_products as $product) {
                                echo ' <tr>
                                         <input type="hidden" name="adds[]" value="'.
                                    $product->cam_products->id.
                                    '">
                                         <td> <img src="'.
                                    asset('uploads/product/'.$product->cam_products->image).
                                    '" alt="Product Image" style="height: 100px;width: 100px;">  </td>
                                         <td>'.
                                    $product->cam_products->title.
                                    '</td>
                                         <td>'.
                                    $product->cam_products->regular_price.
                                    '</td>
                                         <td> <input class="border border-slate-200 rounded-lg px-2 py-1" value="'.
                                    $product->price.
                                    '" type="number" name="prices[]" id="">  <a href="'.
                                    route('admin.campaing.remove', ['id' => $product->id]).
                                    '">Remove</a></td>
                                      </tr>';
                            } ?>
                            @endisset
                        </tbody>
                    </x-ui.table>
                </div>

                <?php }?>

            <x-slot:footer>
                <div class="mb-4">
                    <x-ui.button type="submit" variant="primary" class="mt-1">
                        @isset($campaing)
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
                        @else
                            <i class="fas fa-plus-circle"></i>
                            Submit
                        @endisset
                    </x-ui.button>
                </div>
            </x-slot:footer>

            </form>
        </x-ui.card>
    </div>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#product').on('change', function() {
                var product_ids = $('#product').val();
                var campaign_id = $('#camid').val();
                if (product_ids.length > 0) {
                    $.post('{{ route('admin.campaing.getData') }}', {
                        _token: '{{ csrf_token() }}',
                        product_ids: product_ids,
                        campaign_id: campaign_id
                    }, function(response) {
                        $('#discount_table').append(response);
                    });
                } else {
                    $('#discount_table').append(null);
                }
            });
        });
        $('#flash').on('click', function() {
            var flas = document.getElementById('flash');
            var flas_end = document.getElementById('flas_end');
            if (flas.checked == true) {
                flas_end.innerHTML =
                    '<label for="flash_end">Flash end:</label><input type="datetime-local" name="flash_end" id="flash_end"  class="form-control "  >';
            } else {
                flas_end.innerHTML = "";
            }
        })
    </script>
@endpush
