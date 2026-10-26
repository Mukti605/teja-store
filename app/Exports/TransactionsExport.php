<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting, WithTitle
{
    /** @param Collection<int, OrderItem> $items */
    public function __construct(private Collection $items)
    {
    }

    public function collection(): Collection
    {
        return $this->items;
    }

    public function headings(): array
    {
        return ['Tanggal', 'No. Invoice', 'Pelanggan', 'Produk', 'Kategori', 'Harga Satuan (Rp)', 'Qty', 'Subtotal (Rp)', 'Status'];
    }

    /** @param OrderItem $item */
    public function map($item): array
    {
        return [
            $item->order->created_at->format('d/m/Y H:i'),
            $item->order->invoice_no,
            $item->order->customer_name,
            $item->product_name,
            strtoupper($item->category),
            $item->price,
            $item->qty,
            $item->subtotal,
            Order::STATUSES[$item->order->status] ?? $item->order->status,
        ];
    }

    public function columnFormats(): array
    {
        return ['F' => '#,##0', 'G' => '0', 'H' => '#,##0'];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->freezePane('A2');

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0B7A8A']],
            ],
        ];
    }

    public function title(): string
    {
        return 'Tabel Transaksi';
    }
}
