<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // ── Validasi & parsing filter ─────────────────────────────────────────
        $period     = $request->input('period', '30');   // 7 / 30 / 90 / custom
        $startDate  = null;
        $endDate    = null;

        if ($period === 'custom') {
            $startDate = $request->input('start_date')
                ? now()->parse($request->input('start_date'))->startOfDay()
                : now()->subDays(30)->startOfDay();
            $endDate   = $request->input('end_date')
                ? now()->parse($request->input('end_date'))->endOfDay()
                : now()->endOfDay();
        } else {
            $startDate = now()->subDays((int) $period)->startOfDay();
            $endDate   = now()->endOfDay();
        }

        $categoryFilter = $request->input('category_id');   // opsional
        $statusFilter   = $request->input('status');        // opsional

        // ── Closure helper: apply filter tanggal & status ke query orders ────
        $applyOrderFilter = function ($query) use ($startDate, $endDate, $statusFilter) {
            $query->whereBetween('orders.created_at', [$startDate, $endDate]);
            if ($statusFilter && $statusFilter !== 'all') {
                $query->where('orders.status', $statusFilter);
            } else {
                $query->whereNotIn('orders.status', ['cancelled']);
            }
        };

        // ── 1. Top 10 produk terlaris ─────────────────────────────────────────
        $topProducts = DB::table('order_items')
            ->join('orders',   'order_items.order_id',   '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity)           as total_qty'),
                DB::raw('SUM(order_items.subtotal)           as total_revenue'),
                DB::raw('COUNT(DISTINCT order_items.order_id) as total_orders'),
            )
            ->when($categoryFilter, fn($q) => $q->where('products.category_id', $categoryFilter))
            ->tap($applyOrderFilter)
            ->groupBy('products.id', 'products.name', 'products.price', 'categories.name')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        // ── 2. Revenue per kategori ───────────────────────────────────────────
        $revenueByCategory = DB::table('order_items')
            ->join('orders',     'order_items.order_id',   '=', 'orders.id')
            ->join('products',   'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id',   '=', 'categories.id')
            ->select(
                'categories.id',
                'categories.name as category_name',
                DB::raw('SUM(order_items.subtotal)            as total_revenue'),
                DB::raw('SUM(order_items.quantity)            as total_qty'),
                DB::raw('COUNT(DISTINCT orders.id)            as total_orders'),
            )
            ->when($categoryFilter, fn($q) => $q->where('categories.id', $categoryFilter))
            ->tap($applyOrderFilter)
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        // ── 3. Rating produk ──────────────────────────────────────────────────
        $productRatings = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.name',
                'categories.name as category_name',
                DB::raw('ROUND(AVG(products.rating), 1) as avg_rating'),
                DB::raw('SUM(products.review_count)     as total_reviews'),
            )
            ->where('products.review_count', '>=', 0) // Diubah ke >= agar produk baru tetap muncul
            ->when($categoryFilter, fn($q) => $q->where('products.category_id', $categoryFilter))
            ->groupBy('products.id', 'products.name', 'categories.name')
            ->orderByDesc('avg_rating')
            ->orderByDesc('total_reviews')
            ->limit(10)
            ->get();

        // ── 4. User paling aktif ──────────────────────────────────────────────
        $activeUsers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('SUM(orders.total)        as total_spent'),
                DB::raw('AVG(orders.total)        as avg_order_value'),
                DB::raw('MAX(orders.created_at)   as last_order_at'),
            )
            ->when($categoryFilter, fn($q) => $q->where('products.category_id', $categoryFilter))
            ->tap($applyOrderFilter)
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_orders')
            ->limit(10)
            ->get();

        // ── Summary cards (ikut filter) ───────────────────────────────────────
        $summaryQuery = DB::table('orders')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->when($categoryFilter, fn($q) => $q->where('products.category_id', $categoryFilter))
            ->when(
                $statusFilter && $statusFilter !== 'all',
                fn($q) => $q->where('orders.status', $statusFilter),
                fn($q) => $q->whereNotIn('orders.status', ['cancelled'])
            );

        $summary = [
            'total_revenue'  => (clone $summaryQuery)->sum('order_items.subtotal') ?? 0,
            'total_orders'   => (clone $summaryQuery)->count(DB::raw('DISTINCT orders.id')),
            'total_products' => DB::table('products')->where('status', 'active')->count(),
            'total_users'    => (clone $summaryQuery)->count(DB::raw('DISTINCT orders.user_id')),
        ];

        // ── Data untuk dropdown filter ────────────────────────────────────────
        $categories = DB::table('categories')->orderBy('name')->get(['id', 'name']);
        $statuses   = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        return view('admin.dashboard', compact(
            'topProducts', 'revenueByCategory', 'productRatings', 'activeUsers',
            'summary', 'categories', 'statuses',
            'period', 'startDate', 'endDate', 'categoryFilter', 'statusFilter',
        ));
    }
}
