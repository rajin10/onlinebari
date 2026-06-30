@php
    [$app, $route] = match (auth()->user()?->is_admin) {
        true => ['layouts.admin.app', route('admin.dashboard')],
        false => ['layouts.frontend.app', route('home')],
        default => ['layouts.frontend.app', route('home')],
    };
@endphp

@extends($app)

@section('title', 'Contact')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="">500 Error Page</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ $route }}">Home</a></li>
                        <li class="breadcrumb-item active">500 Error Page</li>
                    </ol>
                </div>
            </div>
        </div><!-- /. -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-warning"> 500</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Something went wrong.</h3>

                <p>
                    We will work on fixing that right away. Meanwhile, you may <a href="{{ $route }}">return to
                        dashboard</a> or try using the search form.
                    We could not find the page you were looking for.
                </p>
            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>
    <!-- /.content -->
@endsection
