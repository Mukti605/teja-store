<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

/** Transaksi contoh agar dashboard, grafik, dan export langsung terisi. Hapus dari DatabaseSeeder jika tidak perlu. */
class DemoOrderSeeder extends Seeder
{
    public function run(): void
    {
        if (Order::exists()) {
            return;
        }

        mt_srand(2026);
        $products = Product::all();
        $names = ['Budi Santoso', 'Ni Luh Ayu', 'Made Wirawan', 'Rina Marlina', 'Kadek Dwi', 'Putu Adi', 'Siti Aisyah', 'Gede Arta', 'Dewi Lestari', 'Rafi Pratama'];
        $payments = array_keys(Order::PAYMENT_METHODS);
        $statuses = ['selesai', 'selesai', 'selesai', 'dikirim', 'diproses', 'menunggu', 'dibatalkan'];

        for ($i = 0; $i < 32; $i++) {
            $date = now()->subDays(mt_rand(0, 20))->setTime(mt_rand(8, 21), mt_rand(0, 59));
            $name = $names[array_rand($names)];

            $order = new Order([
                'invoice_no' => Order::generateInvoiceNo(),
                'customer_name' => $name,
                'customer_phone' => '08'.mt_rand(1000000000, 9999999999),
                'customer_address' => 'Jl. Contoh No. '.mt_rand(1, 99).', Denpasar',
                'payment_method' => $payments[array_rand($payments)],
                'status' => $statuses[array_rand($statuses)],
                'total' => 0,
            ]);
            $order->created_at = $date;
            $order->updated_at = $date;
            $order->save();

            $total = 0;
            foreach ($products->random(mt_rand(1, 3)) as $product) {
                $qty = mt_rand(1, 2);
                $subtotal = $product->price * $qty;
                $total += $subtotal;
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'category' => $product->category,
                    'price' => $product->price,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                ]);
            }
            $order->update(['total' => $total]);
        }
    }
}
