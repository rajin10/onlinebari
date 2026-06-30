@extends('layouts.admin.app')

@section('title', 'Type List')


@section('content')

    <div class="mb-4"></div>

    <section class="mb-6">
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <x-ui.stat-tile
                variant="warning"
                value="Inhouse"
                label="Add new product"
                icon="fas fa-plus"
                :href="route('admin.product.inhouse.create')"
            />
        </div>
    </section>

@endsection
