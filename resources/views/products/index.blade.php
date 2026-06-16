{{-- resources/views/products/index.blade.php --}}

{{-- 1. Tentukan layout induk --}}
@extends('layouts.master')

{{-- 2. Override title --}}
@section('title', 'Daftar Produk')

{{-- 3. Tambahkan CSS khusus halaman ini --}}
@push('styles')
<style>
    .product-card:hover {
        transform: translateY(-3px);
        transition: .2s;
    }
</style>
@endpush

{{-- 4. Isi konten utama --}}
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="bi bi-box-seam text-primary me-2"></i>
            Daftar Produk
        </h4>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Produk
                </li>
            </ol>
        </nav>
    </div>

    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Tambah Produk
    </a>
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body">
        <form
            method="GET"
            action="{{ route('products.index') }}"
            class="row g-2"
        >
            <div class="col-md-5">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari nama produk..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="col-md-3">
                <select name="category_id" class="form-select">
                    <option value="">
                        Semua Kategori
                    </option>

                    @foreach ($categories as $cat)
                        <option
                            value="{{ $cat->id }}"
                            {{ request('category_id') == $cat->id ? 'selected' : '' }}
                        >
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search me-1"></i>
                    Cari
                </button>
            </div>

            <div class="col-md-2">
                <a
                    href="{{ route('products.index') }}"
                    class="btn btn-outline-secondary w-100"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabel Produk --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table
                class="table table-hover mb-0"
                style="font-size:.88rem;"
            >
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="px-4 py-3 border-0">#</th>
                        <th class="py-3 border-0">Produk</th>
                        <th class="py-3 border-0">Kategori</th>
                        <th class="py-3 border-0 text-end">Harga</th>
                        <th class="py-3 border-0 text-center">Stok</th>
                        <th class="py-3 border-0 text-center">Status</th>
                        <th class="py-3 border-0 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="px-4 py-3 text-muted">
                                {{ $products->firstItem() + $loop->index }}
                            </td>

                            <td class="py-3">
                                <div class="d-flex align-items-center gap-3">

                                    @if ($product->image)
                                        <img
                                            src="{{ asset('storage/' . $product->image) }}"
                                            class="rounded-2 border"
                                            style="width:44px; height:44px; object-fit:cover;"
                                        >
                                    @else
                                        <div
                                            class="rounded-2 bg-light border d-flex align-items-center justify-content-center"
                                            style="width:44px; height:44px;"
                                        >
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <div class="fw-semibold">
                                            {{ $product->name }}
                                        </div>

                                        @if ($product->is_featured)
                                            <span
                                                class="badge bg-warning text-dark"
                                                style="font-size:.7rem;"
                                            >
                                                ⭐ Featured
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            </td>

                            <td class="py-3">
                                <span class="badge bg-light text-dark border">
                                    {{ $product->category->name ?? '-' }}
                                </span>
                            </td>

                            <td class="py-3 text-end fw-semibold">
                                {{ $product->formatted_price }}
                            </td>

                            <td class="py-3 text-center">
                                <span
                                    class="badge {{
                                        $product->stock === 0
                                            ? 'bg-danger'
                                            : ($product->stock <= 5
                                                ? 'bg-warning text-dark'
                                                : 'bg-success')
                                    }}"
                                >
                                    {{ $product->stock }}
                                </span>
                            </td>

                            <td class="py-3 text-center">
                                {{-- Menggunakan Enum untuk badge --}}
                                <span class="badge bg-{{ $product->status->color() }}">
                                    {{ $product->status->label() }}
                                </span>
                            </td>

                            <td class="py-3 text-center">
                                <div class="d-flex justify-content-center gap-1">

                                    <a
                                        href="{{ route('products.show', $product) }}"
                                        class="btn btn-sm btn-outline-info"
                                        title="Detail"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a
                                        href="{{ route('products.edit', $product) }}"
                                        class="btn btn-sm btn-outline-warning"
                                        title="Edit"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form
                                        action="{{ route('products.destroy', $product) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus produk \'{{ addslashes($product->name) }}\'?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="7"
                                class="text-center py-5 text-muted"
                            >
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>

                                <div class="fw-semibold">
                                    Tidak ada produk ditemukan
                                </div>

                                @if (request('search'))
                                    <div class="small mt-1">
                                        Tidak ada hasil untuk
                                        "<strong>{{ request('search') }}</strong>"
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- Pagination --}}
    @if ($products->hasPages())
        <div
            class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3 px-4"
        >
            <div class="text-muted small">
                Menampilkan
                <strong>{{ $products->firstItem() }}</strong>
                –
                <strong>{{ $products->lastItem() }}</strong>

                dari

                <strong>{{ $products->total() }}</strong>
                produk
            </div>

            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>

@endsection

{{-- 5. JS khusus halaman ini --}}
@push('scripts')
<script>
    // Auto-hide alert setelah 4 detik
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            bootstrap.Alert.getOrCreateInstance(el).close();
        });
    }, 4000);
</script>
@endpush
