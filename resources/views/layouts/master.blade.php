{{-- resources/views/layouts/master.blade.php --}}


<!DOCTYPE html>
<html lang="id">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- Title: bisa di-override per halaman --}}
    <title>
        @yield('title', 'Toko App') — {{ config('app.name') }}
    </title>


    {{-- CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">


    <style>
        body {
            background-color: #f8f9fa;
        }


        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
        }


        .sidebar {
            position: sticky;
            top: 1rem;
            height: calc(100vh - 2rem);
            overflow-y: auto;
        }


        .sidebar .nav-link {
            color: #495057;
            border-radius: 8px;
            padding: .5rem .75rem;
            margin-bottom: 2px;
            transition: all .2s;
        }


        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #e7f1ff;
            color: #0d6efd;
        }


        .sidebar .nav-link i {
            margin-right: .5rem;
            width: 18px;
        }


        .content-area {
            min-height: calc(100vh - 56px);
        }
    </style>


    {{-- Tambahan CSS per halaman --}}
    @stack('styles')
</head>


<body>


    {{-- ── NAVBAR ──────────────────────────────────────── --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
        <div class="container-fluid px-4">


            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-bag-heart-fill me-2"></i>
                Toko App
            </a>


            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNav"
            >
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="mainNav">


                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}"
                        >
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                    </li>


                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                            href="{{ route('products.index') }}"
                        >
                            <i class="bi bi-box-seam"></i>
                            Produk
                        </a>
                    </li>


                    <li class="nav-item">
                        <a
                            class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                            href="{{ route('categories.index') }}"
                        >
                            <i class="bi bi-tags"></i>
                            Kategori
                        </a>
                    </li>
                </ul>


                {{-- Search Bar --}}
                <form
                    class="d-flex me-3"
                    action="{{ route('products.index') }}"
                    method="GET"
                >
                    <div class="input-group input-group-sm">
                        <input
                            type="text"
                            class="form-control"
                            name="search"
                            placeholder="Cari produk..."
                            value="{{ request('search') }}"
                        >


                        <button class="btn btn-light" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>


                {{-- User Menu --}}
              {{-- @auth
                    <div class="dropdown">
                        <button
                            class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-2"
                            data-bs-toggle="dropdown"
                        >
                            <i class="bi bi-person-circle fs-5"></i>


                            <span class="d-none d-md-inline">
                                {{ auth()->user()->name }}
                            </span>
                        </button>


                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">


                            <li class="px-3 py-2 border-bottom">
                                <div class="small fw-semibold">
                                    {{ auth()->user()->name }}
                                </div>


                                <div class="text-muted" style="font-size:.75rem;">
                                    {{ auth()->user()->email }}
                                </div>
                            </li>


                            <li>
                                <a class="dropdown-item small" href="#">
                                    <i class="bi bi-person me-2"></i>
                                    Profile
                                </a>
                            </li>


                            <li>
                                <hr class="dropdown-divider m-0">
                            </li>


                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf


                                    <button
                                        type="submit"
                                        class="dropdown-item small text-danger"
                                    >
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>


                        </ul>
                    </div>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="btn btn-outline-light btn-sm"
                    >
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        Login
                    </a>
                @endauth --}}


            </div>
        </div>
    </nav>


    {{-- ── MAIN CONTENT ─────────────────────────────────── --}}
    <div class="container-fluid px-4 py-4 content-area">


        {{-- Flash Messages --}}
        @foreach ([
            'success' => 'success',
            'error'   => 'danger',
            'info'    => 'info',
            'warning' => 'warning'
        ] as $key => $class)


            @if (session($key))
                <div
                    class="alert alert-{{ $class }} alert-dismissible fade show shadow-sm border-0 mb-3"
                    role="alert"
                >
                    <i class="bi bi-{{
                        $key === 'success'
                            ? 'check-circle'
                            : ($key === 'error'
                                ? 'x-circle'
                                : ($key === 'warning'
                                    ? 'exclamation-triangle'
                                    : 'info-circle'))
                    }} me-2"></i>


                    {{ session($key) }}


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>
                </div>
            @endif


        @endforeach


        {{-- Konten halaman diisi di sini --}}
        @yield('content')


    </div>


    {{-- ── FOOTER ───────────────────────────────────────── --}}
    <footer class="bg-white border-top py-3 mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center">


                <span class="text-muted small">
                    &copy; {{ date('Y') }}
                    {{ config('app.name') }}.
                    All rights reserved.
                </span>


                <span class="text-muted small">
                    Laravel {{ app()->version() }}
                    · PHP {{ PHP_VERSION }}
                </span>


            </div>
        </div>
    </footer>


    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    {{-- Tambahan JS per halaman --}}
    @stack('scripts')


</body>


</html>
