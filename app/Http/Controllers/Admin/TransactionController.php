<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TransactionsExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/** Tabel Transaksi: satu baris = satu produk di dalam pesanan (dasar laporan & export). */
class TransactionController extends Controller
{
    private const FILTER_KEYS = ['q', 'status', 'category', 'from', 'to'];

    public function index(Request $request)
    {
        $filters = $request->only(self::FILTER_KEYS);
        $base = OrderItem::filter($filters);

        return view('admin.transactions.index', [
            'items' => (clone $base)->with('order')->latest('id')->paginate(15)->withQueryString(),
            'totalQty' => (clone $base)->sum('qty'),
            'totalAmount' => (clone $base)->sum('subtotal'),
            'filters' => $filters,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function excel(Request $request)
    {
        $items = $this->exportRows($request);

        return Excel::download(new TransactionsExport($items), 'tabel-transaksi-'.now()->format('Ymd-His').'.xlsx');
    }

    public function pdf(Request $request)
    {
        $items = $this->exportRows($request);

        return Pdf::loadView('admin.exports.transactions-pdf', [
            'items' => $items,
            'filters' => $request->only(self::FILTER_KEYS),
            'totalQty' => $items->sum('qty'),
            'totalAmount' => $items->sum('subtotal'),
            'printedAt' => now(),
        ])->setPaper('a4', 'landscape')->download('tabel-transaksi-'.now()->format('Ymd-His').'.pdf');
    }

    private function exportRows(Request $request)
    {
        return OrderItem::filter($request->only(self::FILTER_KEYS))
            ->with('order')
            ->latest('id')
            ->get();
    }
}
