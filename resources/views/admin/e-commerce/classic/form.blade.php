@extends('layouts.admin.app')

@section('title', 'Account')
@push('css')
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <style>
        /* Third-party plugin overrides — cannot be expressed as Tailwind utilities */
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            display: none !important
        }

        .note-editor.note-frame {
            border: 1px solid rgba(0, 0, 0, .2) !important;
        }
    </style>
@endpush

@section('content')
    <div class="mx-auto mb-4 mt-5 max-w-screen-xl">
        <form class="rounded bg-white"
            action="{{ isset($product) ? route('admin.product.clasified.update') : route('admin.product.clasified.create') }}"
            method="POST" enctype="multipart/form-data">

            @csrf
            @if (isset($product))
                <input type="hidden" value="{{ $product->id }}" name="power" id="id">
            @endif

            <div class="grid grid-cols-1 gap-4 p-4 md:grid-cols-2">

                {{-- Title --}}
                <div class="md:col-span-2">
                    <x-ui.input
                        name="title"
                        label="Title:"
                        type="text"
                        :value="$product->title ?? old('title')"
                        placeholder="Write blog Title"
                        required
                        autocomplete="off"
                    />
                </div>

                {{-- Location --}}
                <div class="md:col-span-2">
                    <x-ui.input
                        name="location"
                        label="Locaiton:"
                        type="text"
                        :value="$product->location ?? old('location')"
                        placeholder="Write location"
                        required
                        autocomplete="off"
                    />
                </div>

                {{-- Contact Number --}}
                <div>
                    <x-ui.input
                        name="contact"
                        label="Contact Number:"
                        type="tel"
                        :value="$product->contact ?? old('contact')"
                        placeholder="Write contact Number"
                        required
                        autocomplete="off"
                    />
                </div>

                {{-- Price --}}
                <div>
                    <x-ui.input
                        name="price"
                        label="Price:"
                        type="number"
                        :value="$product->price ?? old('price')"
                        placeholder="Write  price"
                        required
                        autocomplete="off"
                    />
                </div>

                {{-- Description (summernote) --}}
                <div class="md:col-span-2">
                    <label for="description" class="mb-1 block text-sm font-medium text-slate-700">Description:</label>
                    <textarea name="description" id="description" rows="5"
                        placeholder="Write product short description"
                        class="block w-full rounded-md border border-slate-300 px-3 py-2 text-sm shadow-sm focus:border-primary focus:ring-1 focus:ring-primary @error('description') border-danger @enderror"
                        required>{{ $product->description ?? old('description') }}</textarea>
                    @error('description')
                        <p class="text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Thumbnail (dropify) --}}
                <div>
                    <label for="image" class="mb-1 block text-sm font-medium text-slate-700">Thumbnail:</label>
                    <input type="file" name="image" id="image" accept="image/*"
                        class="block w-full text-sm text-slate-700 @error('image') border border-danger rounded @enderror"
                        data-default-file="@isset($product){{ asset('/') }}/uploads/product/{{ $product->thumbnail }}@enderror">
                    @error('image')
                        <p class="text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status toggle --}}
                <div class="md:col-span-2">
                    <label class="inline-flex cursor-pointer items-center gap-3">
                        <input type="checkbox" name="status" id="status" class="sr-only peer"
                            @isset($product) {{ $product->status ? 'checked' : '' }} @endisset>
                        <div class="relative h-6 w-11 rounded-full bg-slate-300 transition-colors peer-checked:bg-primary
                             after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5
                             after:rounded-full after:bg-white after:shadow after:transition-all
                             peer-checked:after:translate-x-5"></div>
                        <span class="text-sm font-medium text-slate-700">Published</span>
                    </label>
                    @error('status')
                        <p class="text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="md:col-span-2">
                    <x-ui.button type="submit" variant="primary">
                        @isset($product)
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
                        @else
                            <i class="fas fa-plus-circle"></i>
                            Submit
                        @endisset
                    </x-ui.button>
                </div>

            </div>

        </form>
    </div>
@endsection

@push('js')
    <script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script src="/assets/plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(function() {
            $('#image').dropify();
            $('#description').summernote();
        });
    </script>
@endpush

@push('js')
    <!-- DataTables  & Plugins -->
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(function() {
            $("#example1").DataTable();
        })
    </script>
    <script>
        $(document).on('click', '#add', function(e) {
            let htmlData = '<div class="input-group mt-2">';
            htmlData += '<input type="file" class="form-control" accept="image/*" name="images[]" required>';

            htmlData += '<div class="input-group-append" id="remove" style="cursor:context-menu">';
            htmlData += '<span class="input-group-text">Remove</span>';
            htmlData += '</div>';
            htmlData += '</div>';
            $('#increment').append(htmlData);
        });
        // increment
        $(document).on('click', '#remove', function(e) {
            $(this).parent().remove();
        });

        $(document).on('click', '#deleteData', function(e) {
            let id = $(this).data('id');
            e.preventDefault();
            let conf = confirm('Are you sure delete this data!!');
            if (conf) {

                document.getElementById('delete-data-form-' + id).submit();
            }

        })
    </script>
@endpush
