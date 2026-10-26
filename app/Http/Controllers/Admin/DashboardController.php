<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $notCancelled = fn ($q) => $q->where('status', '!=', Order::STATUS_DIBATALKAN);

        // Grafik 14 hari terakhir (pesanan yang dibatalkan tidak dihitung).
        $days = collect(range(13, 0))->map(fn ($i) => now()->subDays($i)->startOfDay());
        $byDay = Order::where('status', '!=', Order::STATUS_DIBATALKAN)
            ->where('created_at', '>=', $days->first())
            ->get(['id', 'total', 'created_at'])
            ->groupBy(fn ($o) => $o->created_at->format('Y-m-d'));

        $chart = [
            'labels' => $days->map(fn ($d) => $d->format('d/m'))->all(),
            'revenue' => $days->map(fn ($d) => (int) $byDay->get($d->format('Y-m-d'), collect())->sum('total'))->all(),
            'orders' => $days->map(fn ($d) => $byDay->get($d->format('Y-m-d'), collect())->count())->all(),
        ];

        return view('admin.dashboard', [
            'revenue' => Order::where('status', '!=', Order::STATUS_DIBATALKAN)->sum('total'),
            'ordersCount' => Order::count(),
            'pendingCount' => Order::where('status', Order::STATUS_MENUNGGU)->count(),
            'itemsSold' => OrderItem::whereHas('order', $notCancelled)->sum('qty'),
            'productsCount' => Product::active()->count(),
            'lowStock' => Product::active()->where('stock', '<=', 5)->orderBy('stock')->orderBy('name')->limit(6)->get(),
            'recent' => Order::latest()->limit(6)->get(),
            'topProducts' => OrderItem::select('product_name', DB::raw('SUM(qty) as qty_total'), DB::raw('SUM(subtotal) as revenue'))
                ->whereHas('order', $notCancelled)
                ->groupBy('product_name')
                ->orderByDesc('qty_total')
                ->limit(5)
                ->get(),
            'byCategory' => OrderItem::select('category', DB::raw('SUM(subtotal) as revenue'))
                ->whereHas('order', $notCancelled)
                ->groupBy('category')
                ->pluck('revenue', 'category'),
            'chart' => $chart,
        ]);
    }
}
