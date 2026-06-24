{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.master')

@section('title', 'Admin Dashboard')

@push('styles')
    <style>
        body           { background:#f0f2f5; font-family:'Segoe UI',sans-serif; }
        .sidebar       { width:240px; min-height:100vh; background:#1a3c5e; position:fixed; top:0; left:0; }
        .sidebar-brand { padding:1.5rem 1.25rem; border-bottom:1px solid rgba(255,255,255,.1); }
        .sidebar-brand span { font-size:1.1rem; font-weight:700; color:#fff; }
        .sidebar-nav a { display:flex; align-items:center; gap:.75rem; padding:.65rem 1.25rem;
                         color:rgba(255,255,255,.7); text-decoration:none; font-size:.9rem; transition:.2s; }
        .sidebar-nav a:hover, .sidebar-nav a.active { background:rgba(255,255,255,.1); color:#fff; }
        .main-content  { max-width:1320px; margin:0 auto; padding:2rem 1.5rem; }
        .topbar        { background:#fff; padding:.9rem 1.5rem; border-radius:.5rem;
                         box-shadow:0 1px 4px rgba(0,0,0,.06); margin-bottom:1.75rem;
                         display:flex; justify-content:space-between; align-items:center; }

        /* ── Filter Bar ── */
        .filter-bar    { background:#fff; border-radius:.6rem; padding:1.1rem 1.5rem;
                         box-shadow:0 1px 4px rgba(0,0,0,.06); margin-bottom:1.75rem; }
        .filter-bar .form-label  { font-size:.75rem; font-weight:600; text-transform:uppercase;
                                   letter-spacing:.04em; color:#6c757d; margin-bottom:.3rem; }
        .period-btn    { border:1.5px solid #dee2e6; background:#fff; color:#495057;
                         padding:.35rem .9rem; border-radius:.4rem; font-size:.85rem;
                         cursor:pointer; transition:.15s; }
        .period-btn:hover    { border-color:#1a3c5e; color:#1a3c5e; }
        .period-btn.active   { background:#1a3c5e; border-color:#1a3c5e; color:#fff; font-weight:600; }
        .filter-divider      { width:1px; background:#dee2e6; margin:0 .5rem; align-self:stretch; }

        /* ── Cards Stat (Modifikasi Kreatif dengan Hover Effect) ── */
        .card-stat     { background:#fff; border-radius:.6rem; padding:1.4rem 1.5rem;
                         box-shadow:0 1px 4px rgba(0,0,0,.06); border:none; transition: all 0.3s ease; }
        .card-stat:hover { transform: translateY(-3px); box-shadow: 0 4px 15px rgba(26,60,94,0.15); }
        .card-stat .icon { width:48px; height:48px; border-radius:.5rem;
                           display:flex; align-items:center; justify-content:center; font-size:1.4rem; }
        .card-stat .value { font-size:1.4rem; font-weight:700; color:#1a3c5e; }
        .card-stat .label { font-size:.8rem; color:#6c757d; margin-top:.2rem; }

        /* ── Section cards ── */
        .section-card  { background:#fff; border-radius:.6rem; padding:1.5rem;
                         box-shadow:0 1px 4px rgba(0,0,0,.06); margin-bottom:1.75rem; }
        .section-title { font-size:1rem; font-weight:700; color:#1a3c5e;
                         padding-bottom:.75rem; margin-bottom:1rem;
                         border-bottom:2px solid #e9ecef; display:flex; align-items:center; gap:.5rem; }
        .table         { font-size:.875rem; }
        .table thead th { background:#1a3c5e; color:#fff; font-weight:600; font-size:.8rem;
                          text-transform:uppercase; letter-spacing:.04em; border:none; padding:.75rem 1rem; }
        .table tbody tr:hover { background:#f8f9ff; }
        .table td      { padding:.7rem 1rem; vertical-align:middle; border-color:#f0f2f5; }
        .badge-rank    { width:26px; height:26px; border-radius:50%; display:inline-flex;
                         align-items:center; justify-content:center; font-size:.75rem; font-weight:700; }
        .rank-1 { background:#FFD700; color:#7a6000; }
        .rank-2 { background:#C0C0C0; color:#555; }
        .rank-3 { background:#CD7F32; color:#fff; }
        .rank-n { background:#e9ecef; color:#555; }
        .stars  { color:#f4a820; font-size:.85rem; }
        .bar-wrap { height:6px; border-radius:3px; background:#e9ecef; }
        .bar-fill { height:100%; border-radius:3px; background:linear-gradient(90deg,#1a3c5e,#2e86c1); }
        #customRange { display:none; }
    </style>
@endpush

@section('content')

<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div>
            <h5 class="mb-0 fw-bold" style="color:#1a3c5e">Dashboard Laporan</h5>
            <small class="text-muted">{{ now()->translatedFormat('l, d F Y') }}</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted">
                {{ $startDate->format('d M Y') }} – {{ $endDate->format('d M Y') }}
            </small>
            <span class="badge bg-success">● Live</span>
        </div>
    </div>

    {{-- ── FILTER BAR ──────────────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('admin.dashboard') }}" id="filterForm">
        <div class="filter-bar">
            <div class="d-flex flex-wrap align-items-end gap-3">

                {{-- Period buttons --}}
                <div>
                    <div class="form-label">Periode</div>
                    <div class="d-flex gap-1" id="periodBtns">
                        @foreach (['7' => '7 Hari', '30' => '30 Hari', '90' => '90 Hari', 'custom' => 'Custom'] as $val => $label)
                            <button type="button"
                                    class="period-btn {{ $period === $val ? 'active' : '' }}"
                                    data-period="{{ $val }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="period" id="periodInput" value="{{ $period }}">
                </div>

                {{-- Custom range --}}
                <div id="customRange" class="d-flex gap-2 align-items-end">
                    <div>
                        <div class="form-label">Dari</div>
                        <input type="date" name="start_date" class="form-control form-control-sm"
                               value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                    </div>
                    <div>
                        <div class="form-label">Sampai</div>
                        <input type="date" name="end_date" class="form-control form-control-sm"
                               value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="filter-divider"></div>

                {{-- Filter Kategori --}}
                <div>
                    <div class="form-label">Kategori</div>
                    <select name="category_id" class="form-select form-select-sm" style="min-width:160px">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ $categoryFilter == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Status --}}
                <div>
                    <div class="form-label">Status Order</div>
                    <select name="status" class="form-select form-select-sm" style="min-width:150px">
                        <option value="all" {{ $statusFilter === 'all' || !$statusFilter ? 'selected' : '' }}>
                            Semua (non-cancelled)
                        </option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" {{ $statusFilter === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-divider"></div>

                {{-- Tombol --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-funnel-fill me-1"></i> Terapkan
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm px-3">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                </div>

            </div>
        </div>
    </form>

    {{-- ── SUMMARY CARDS ───────────────────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['label'=>'Total Revenue',  'value'=>'Rp '.number_format($summary['total_revenue'],0,',','.'),
                 'icon'=>'bi-currency-dollar','bg'=>'#e8f4fd','color'=>'#1a6fad'],
                ['label'=>'Total Order',    'value'=>number_format($summary['total_orders'],0,',','.'),
                 'icon'=>'bi-cart-check',   'bg'=>'#eafaf1','color'=>'#1a7a4a'],
                ['label'=>'Produk Aktif',   'value'=>number_format($summary['total_products'],0,',','.'),
                 'icon'=>'bi-box-seam',     'bg'=>'#fef9e7','color'=>'#b7950b'],
                ['label'=>'User Bertransaksi','value'=>number_format($summary['total_users'],0,',','.'),
                 'icon'=>'bi-people-fill',  'bg'=>'#fdf2f8','color'=>'#8e44ad'],
            ];
        @endphp
        @foreach ($cards as $card)
        <div class="col-sm-6 col-xl-3">
            <div class="card-stat d-flex align-items-center gap-3">
                <div class="icon" style="background:{{ $card['bg'] }};color:{{ $card['color'] }}">
                    <i class="bi {{ $card['icon'] }}"></i>
                </div>
                <div>
                    <div class="value" style="white-space: nowrap;">{{ $card['value'] }}</div>
                    <div class="label">{{ $card['label'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── ROW 1: Top Produk + Revenue Kategori ── --}}
    <div class="row g-4">

        {{-- 1. Top 10 Produk --}}
        <div class="col-xl-7">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-trophy-fill text-warning"></i>
                    Top 10 Produk Terlaris
                </div>
                @if($topProducts->isEmpty())
                    <p class="text-muted text-center py-4">Tidak ada data untuk filter ini.</p>
                @else
                <table class="table table-borderless mb-0">
                    <thead>
                        <tr>
                            <th>#</th><th>Produk</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Revenue</th>
                            <th>Bar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $maxQty = $topProducts->max('total_qty') ?: 1; @endphp
                        @foreach ($topProducts as $i => $p)
                        <tr>
                            <td>
                                @php $rc = match($i){0=>'rank-1',1=>'rank-2',2=>'rank-3',default=>'rank-n'}; @endphp
                                <span class="badge-rank {{ $rc }}">{{ $i+1 }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold" style="color:#1a3c5e">{{ $p->name }}</div>
                                <small class="text-muted">{{ $p->category_name }} · {{ $p->total_orders }} order</small>
                            </td>
                            <td class="text-end fw-semibold">{{ number_format($p->total_qty) }}</td>
                            <td class="text-end text-success fw-semibold" style="white-space: nowrap;">
                                Rp {{ number_format($p->total_revenue,0,',','.') }}
                            </td>
                            <td style="width:90px">
                                <div class="bar-wrap">
                                    <div class="bar-fill" style="width:{{ round(($p->total_qty/$maxQty)*100) }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        {{-- 2. Revenue per Kategori (Grafis Batang Vertikal / Ke Atas) --}}
        <div class="col-xl-5">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-bar-chart-line-fill text-primary"></i>
                    Grafik Distribusi Revenue per Kategori
                </div>
                @if($revenueByCategory->isEmpty())
                    <p class="text-muted text-center py-4">Tidak ada data untuk filter ini.</p>
                @else
                <div style="height: 380px; position: relative;">
                    <canvas id="categoryRevenueChart"></canvas>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── ROW 2: Rating + User Aktif ─────────────────────────────────── --}}
    <div class="row g-4 mt-0">

        {{-- 3. Rating Produk --}}
        <div class="col-xl-6">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-star-fill text-warning"></i> Rating Produk Teratas
                </div>
                @if($productRatings->isEmpty())
                    <p class="text-muted text-center py-4">Tidak ada data untuk filter ini.</p>
                @else
                <table class="table table-borderless mb-0">
                    <thead>
                        <tr><th>Produk</th><th>Kategori</th><th class="text-center">Rating</th><th class="text-end">Reviews</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($productRatings as $p)
                        <tr>
                            <td class="fw-semibold" style="color:#1a3c5e;">{{ $p->name }}</td>
                            <td><span class="badge bg-light text-dark">{{ $p->category_name }}</span></td>
                            <td class="text-center">
                                <div class="stars">
                                    @for ($s=1;$s<=5;$s++)
                                        <i class="bi bi-star{{ $s<=round($p->avg_rating)?'-fill':'' }}"></i>
                                    @endfor
                                </div>
                                <small class="fw-semibold">{{ number_format($p->avg_rating,1) }}</small>
                            </td>
                            <td class="text-end text-muted">{{ number_format($p->total_reviews) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        {{-- 4. User Paling Aktif --}}
        <div class="col-xl-6">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-person-fill-check text-success"></i> Top 10 User Paling Aktif
                </div>
                @if($activeUsers->isEmpty())
                    <p class="text-muted text-center py-4">Tidak ada data untuk filter ini.</p>
                @else
                <div style="overflow-x: auto;">
                <table class="table table-borderless mb-0">
                    <thead>
                        <tr>
                            <th>#</th><th>User</th>
                            <th class="text-center">Order</th>
                            <th class="text-end">Total Belanja</th>
                            <th>Terakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($activeUsers as $i => $u)
                        <tr>
                            <td><span class="badge-rank {{ match($i){0=>'rank-1',1=>'rank-2',2=>'rank-3',default=>'rank-n'} }}">{{ $i+1 }}</span></td>
                            <td>
                                <div class="fw-semibold" style="color:#1a3c5e">{{ $u->name }}</div>
                                <small class="text-muted">{{ $u->email }}</small>
                            </td>
                            <td class="text-center"><span class="badge bg-primary rounded-pill">{{ $u->total_orders }}</span></td>
                            <td class="text-end fw-semibold text-success" style="white-space: nowrap;">Rp {{ number_format($u->total_spent,0,',','.') }}</td>
                            <td><small class="text-muted">{{ \Carbon\Carbon::parse($u->last_order_at)->diffForHumans() }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ── ROW 3: Sentimen Rating + Statistik Kupon ───────────────────── --}}
    <div class="row g-4 mt-0">

        {{-- 5. Analisis Sentimen Rating --}}
        <div class="col-xl-6">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-emoji-smile-fill text-warning"></i> Analisis Sentimen Rating Ulasan (Doughnut Chart)
                </div>
                <div style="height: 250px; position: relative;">
                    <canvas id="sentimentChart"></canvas>
                </div>
            </div>
        </div>

        {{-- 6. Statistik Kupon --}}
        <div class="col-xl-6">
            <div class="section-card">
                <div class="section-title">
                    <i class="bi bi-ticket-perforated-fill text-primary"></i> Kinerja &amp; Kuantitas Kupon
                </div>
                <div class="row text-center mt-4">
                    <div class="col-4">
                        <h3 class="fw-bold" style="color:#1a7a4a">{{ $couponStats['aktif'] }}</h3>
                        <p class="text-muted small mb-0">Kupon Aktif</p>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold" style="color:#c0392b">{{ $couponStats['kadaluarsa'] }}</h3>
                        <p class="text-muted small mb-0">Kupon Kedaluwarsa</p>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold" style="color:#1a6fad">{{ $couponStats['total_used'] }}</h3>
                        <p class="text-muted small mb-0">Total Penggunaan</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ── Toggle Filter Periode ──────────────────────────────────────────────────
    document.querySelectorAll('.period-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const period = this.dataset.period;
            document.getElementById('periodInput').value = period;
            document.getElementById('customRange').style.display = period === 'custom' ? 'flex' : 'none';

            if (period !== 'custom') document.getElementById('filterForm').submit();
        });
    });

    if (document.getElementById('periodInput').value === 'custom') {
        document.getElementById('customRange').style.display = 'flex';
    }

    // ── Chart 1: Sentimen Rating (Doughnut) ────────────────────────────────────
    const ctxSentiment = document.getElementById('sentimentChart').getContext('2d');
    new Chart(ctxSentiment, {
        type: 'doughnut',
        data: {
            labels: ['Positif (Bintang 4-5)', 'Netral (Bintang 3)', 'Negatif (Bintang 1-2)'],
            datasets: [{
                data: [
                    {{ $sentimentAnalysis['positif'] }},
                    {{ $sentimentAnalysis['netral'] }},
                    {{ $sentimentAnalysis['negatif'] }}
                ],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });

    // ── Chart 2: Revenue per Kategori (BATANG VERTIKAL / KE ATAS) ──
    @if(!$revenueByCategory->isEmpty())
    const ctxRevenue = document.getElementById('categoryRevenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'bar',
        data: {
            labels: {!! json_encode($revenueByCategory->pluck('category_name')) !!},
            datasets: [{
                label: 'Total Revenue',
                data: {!! json_encode($revenueByCategory->pluck('total_revenue')) !!},
                backgroundColor: [
                    'rgba(26, 60, 94, 0.85)',
                    'rgba(46, 134, 193, 0.85)',
                    'rgba(17, 122, 101, 0.85)',
                    'rgba(183, 149, 11, 0.85)',
                    'rgba(142, 68, 173, 0.85)'
                ],
                borderColor: [
                    '#1a3c5e', '#2e86c1', '#117a65', '#b7950b', '#8e44ad'
                ],
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            return ' Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    grid: { display: false },
                    ticks: {
                        callback: function(value) {
                            // Bagian ini diubah untuk menampilkan nilai Rupiah penuh (tanpa disingkat)
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
    @endif
</script>
@endpush

@endsection
