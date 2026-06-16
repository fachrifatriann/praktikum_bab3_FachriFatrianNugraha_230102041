@extends('layouts.master')

@section('title', 'Daftar Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="bi bi-tags text-primary me-2"></i>
            Daftar Kategori
        </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Kategori</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        Tambah Kategori
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius:12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:.88rem;">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="px-4 py-3 border-0">#</th>
                        <th class="py-3 border-0">Nama Kategori</th>
                        <th class="py-3 border-0">Slug</th>
                        <th class="py-3 border-0">Deskripsi</th>
                        <th class="py-3 border-0 text-center">Jumlah Produk</th>
                        <th class="py-3 border-0 text-center">Status</th>
                        <th class="py-3 border-0 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3 text-muted">
                            {{ $categories->firstItem() + $loop->index }}
                        </td>
                        <td class="py-3 fw-semibold">{{ $category->name }}</td>
                        <td class="py-3 text-muted small">{{ $category->slug }}</td>
                        <td class="py-3 text-muted small">
                            {{ $category->description
                                ? \Illuminate\Support\Str::limit($category->description, 50)
                                : '-' }}
                        </td>
                        <td class="py-3 text-center">
                            <span class="badge bg-primary">
                                {{ $category->products_count }}
                            </span>
                        </td>
                        <td class="py-3 text-center">
                            @if ($category->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-3 text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('categories.edit', $category) }}"
                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                {{-- tombol hapus --}}
                                <button type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    title="Hapus"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalHapus"
                                    data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                            <div class="fw-semibold">Belum ada kategori</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($categories->hasPages())
    <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3 px-4">
        <div class="text-muted small">
            Menampilkan <strong>{{ $categories->firstItem() }}</strong>
            – <strong>{{ $categories->lastItem() }}</strong>
            dari <strong>{{ $categories->total() }}</strong> kategori
        </div>
        {{ $categories->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

{{-- Bootstrap Modal Konfirmasi Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah kamu yakin ingin menghapus kategori
                <strong id="namaKategori"></strong>?
                <div class="alert alert-warning mt-3 mb-0 small">
                    <i class="bi bi-info-circle me-1"></i>
                    Kategori yang masih memiliki produk aktif tidak dapat dihapus.
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                <form id="formHapus" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modalHapus = document.getElementById('modalHapus');
    modalHapus.addEventListener('show.bs.modal', function (e) {
        const btn  = e.relatedTarget;
        const id   = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        document.getElementById('namaKategori').textContent = name;
        document.getElementById('formHapus').action = `/categories/${id}`;
    });
</script>
@endpush
