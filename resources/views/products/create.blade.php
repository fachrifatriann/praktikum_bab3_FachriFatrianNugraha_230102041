{{-- Penggunaan Component Layout --}}


{{-- resources/views/products/create.blade.php --}}
@extends('layouts.master')


@section('title', 'Tambah Produk')


@section('content')


<div class="container">


@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>⚠️ Gagal Menyimpan!</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>✅ Berhasil!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">
        Tambah Produk Baru
    </h4>


    <a href="{{ route('products.index') }}" class="btn btn-secondary">
        Kembali
    </a>
</div>


<div class="card shadow-sm border-0">
    <div class="card-body">


        <form
            action="{{ route('products.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf


            <div class="mb-3">
                <label class="form-label">
                    Nama Produk
                </label>


                <input
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                >
                @error('name')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>


            <div class="mb-3">
                <label class="form-label">
                    Kategori
                </label>


                <select
                    name="category_id"
                    class="form-select @error('category_id') is-invalid @enderror"
                >
                    <option value="">
                        Pilih Kategori
                    </option>


                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>


            <div class="mb-3">
                <label class="form-label">
                    Harga
                </label>


                <input
                    type="number"
                    name="price"
                    class="form-control @error('price') is-invalid @enderror"
                    value="{{ old('price') }}"
                >
                @error('price')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>


            <div class="mb-3">
                <label class="form-label">
                    Stok
                </label>


                <input
                    type="number"
                    name="stock"
                    class="form-control @error('stock') is-invalid @enderror"
                    value="{{ old('stock') }}"
                >
                @error('stock')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>


            <div class="mb-3">
                <label class="form-label">
                    Deskripsi
                </label>


                <textarea
                    name="description"
                    class="form-control @error('description') is-invalid @enderror"
                    rows="4"
                >{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>


            <div class="mb-3">
                <label class="form-label">
                    Status
                </label>


                <select
                    name="status"
                    class="form-select @error('status') is-invalid @enderror"
                    required
                >
                    <option value="">
                        Pilih Status
                    </option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>
                </select>
                @error('status')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>


            <div class="mb-3">
                <label class="form-label">
                    Gambar Produk
                </label>


                <input
                    type="file"
                    name="image"
                    class="form-control @error('image') is-invalid @enderror"
                    accept="image/jpeg,image/png,image/webp"
                >
                <small class="text-muted d-block mt-2">
                    Format: JPEG, PNG, WebP | Maksimal 2MB
                </small>
                @error('image')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>


            <button
                type="submit"
                class="btn btn-primary"
            >
                Simpan Produk
            </button>


        </form>


    </div>
</div>


</div>


@endsection
