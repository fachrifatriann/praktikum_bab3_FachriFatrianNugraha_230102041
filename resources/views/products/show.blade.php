{{-- Penggunaan Component Layout --}}


{{-- resources/views/products/show.blade.php --}}


@extends('layouts.master')


@section('title', 'Detail Produk')


@section('content')


<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="bi bi-eye text-primary me-2"></i>
            Detail Produk
        </h4>




    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('products.index') }}">
                    Produk
                </a>
            </li>


            <li class="breadcrumb-item active">
                {{ $product->name }}
            </li>
        </ol>
    </nav>
</div>


<div class="d-flex gap-2">
    <a href="{{ route('products.edit', $product) }}"
       class="btn btn-warning">
        <i class="bi bi-pencil me-1"></i>
        Edit
    </a>


    <a href="{{ route('products.index') }}"
       class="btn btn-outline-secondary">
        Kembali
    </a>
</div>




</div>


<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-semibold">
            Informasi Produk
        </h5>
    </div>




<div class="card-body">
    <div class="row g-4">


        <div class="col-md-4">


            @if($product->image)
                <img
                    src="{{ asset('storage/' . $product->image) }}"
                    class="img-fluid rounded border w-100"
                    alt="{{ $product->name }}"
                >
            @else
                <div
                    class="border rounded bg-light d-flex align-items-center justify-content-center"
                    style="height:300px;"
                >
                    <i class="bi bi-image fs-1 text-muted"></i>
                </div>
            @endif


        </div>


        <div class="col-md-8">


            <div class="mb-3">
                <label class="form-label text-muted">
                    Nama Produk
                </label>


                <div class="form-control bg-light">
                    {{ $product->name }}
                </div>
            </div>


            <div class="row">


                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">
                        Kategori
                    </label>


                    <div class="form-control bg-light">
                        {{ $product->category->name ?? '-' }}
                    </div>
                </div>


                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">
                        Harga
                    </label>


                    <div class="form-control bg-light fw-semibold">
                        {{ $product->formatted_price }}
                    </div>
                </div>


            </div>


            <div class="row">


                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">
                        Stok
                    </label>


                    <div class="form-control bg-light">
                        {{ $product->stock }}
                    </div>
                </div>


                <div class="col-md-6 mb-3">
                    <label class="form-label text-muted">
                        Status
                    </label>


                    <div>
                        <span class="badge bg-{{ $product->status->color() }}">
                            {{ $product->status->label() }}
                        </span>


                        @if($product->is_featured)
                            <span class="badge bg-warning text-dark">
                                Featured
                            </span>
                        @endif
                    </div>
                </div>


            </div>


            <div class="mb-3">
                <label class="form-label text-muted">
                    Slug
                </label>


                <div class="form-control bg-light">
                    {{ $product->slug }}
                </div>
            </div>


            <div class="mb-3">
                <label class="form-label text-muted">
                    Deskripsi
                </label>


                <div class="form-control bg-light" style="min-height:120px;">
                    {{ $product->description ?: '-' }}
                </div>
            </div>


            <div class="small text-muted">
                Dibuat:
                {{ $product->created_at->format('d M Y H:i') }}
            </div>


        </div>
    </div>
</div>




</div>


@if($related->count())


<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white">
        <h6 class="mb-0">
            Produk Terkait
        </h6>
    </div>




<div class="card-body">
    <div class="row">


        @foreach($related as $item)
            <div class="col-md-3 mb-3">
                <div class="card h-100 border-0 shadow-sm">


                    <div class="card-body">


                        <h6 class="fw-semibold">
                            {{ $item->name }}
                        </h6>


                        <div class="text-muted small mb-2">
                            {{ $item->formatted_price }}
                        </div>


                        <a href="{{ route('products.show', $item) }}"
                           class="btn btn-sm btn-outline-primary">
                            Lihat Detail
                        </a>


                    </div>


                </div>
            </div>
        @endforeach


    </div>
</div>




</div>
@endif


@endsection
