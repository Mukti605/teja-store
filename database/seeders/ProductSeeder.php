<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Data contoh. Harga dan stok hanya placeholder: ubah sesuai kebutuhan,
     * lalu jalankan `php artisan migrate:fresh --seed`.
     */
    public function run(): void
    {
        $deskripsi = [
            'laptop' => 'Laptop baru bergaransi, cocok untuk kuliah, kerja, dan kebutuhan harian. Tanyakan spesifikasi lengkap dan promo bundling kepada admin.',
            'hp' => 'HP baru bergaransi, siap pakai. Tanyakan pilihan warna, varian memori, dan promo kepada admin.',
        ];

        // nama, slug, brand, kategori, harga, stok, file gambar (public/images/products)
        $data = [
            ['Acer Aspire ES 14', 'acer-aspire-es-14', 'Acer', 'laptop', 5999000, 3, 'acer-aspire-es-14.jpg'],
            ['Samsung Galaxy A34 5G', 'samsung-galaxy-a34-5g', 'Samsung', 'hp', 4799000, 10, 'samsung-galaxy-a34-5g.jpg'],
            ['Axioo Hype 10', 'axioo-hype-10', 'Axioo', 'laptop', 4799000, 17, 'axioo-hype-10.jpg'],
            ['Infinix Note 50 Pro', 'infinix-note-50-pro', 'Infinix', 'hp', 3299000, 6, 'infinix-note-50-pro.jpg'],
            ['ASUS Vivobook Ultra 15 OLED', 'asus-vivobook-ultra-15-oled', 'ASUS', 'laptop', 12499000, 13, 'asus-vivobook-ultra-15-oled.jpg'],
            ['Infinix Hot 30i', 'infinix-hot-30i', 'Infinix', 'hp', 1399000, 0, 'infinix-hot-30i.jpg'],
            ['Lenovo IdeaPad Slim 3', 'lenovo-ideapad-slim-3', 'Lenovo', 'laptop', 7999000, 9, 'lenovo-ideapad-slim-3.jpg'],
            ['Infinix Note 40 Pro 5G', 'infinix-note-40-pro-5g', 'Infinix', 'hp', 3199000, 16, 'infinix-note-40-pro-5g.jpg'],
            ['ASUS Zenbook UX54 60A', 'asus-zenbook-ux54-60a', 'ASUS', 'laptop', 14999000, 5, 'asus-zenbook-ux54-60a.jpg'],
            ['Oppo Reno10', 'oppo-reno10', 'Oppo', 'hp', 5999000, 12, 'oppo-reno10.jpg'],
            ['ASUS Zenbook UM462', 'asus-zenbook-um462', 'ASUS', 'laptop', 13499000, 19, 'asus-zenbook-um462.jpg'],
            ['Oppo Reno16 Pro', 'oppo-reno16-pro', 'Oppo', 'hp', 9999000, 8, 'oppo-reno16-pro.jpg'],
            ['Axioo Hype 3 G12', 'axioo-hype-3-g12', 'Axioo', 'laptop', 3999000, 15, 'axioo-hype-3-g12.jpg'],
            ['Oppo Reno12 Pro', 'oppo-reno12-pro', 'Oppo', 'hp', 7999000, 4, 'oppo-reno12-pro.jpg'],
            ['Axioo Hype 5', 'axioo-hype-5', 'Axioo', 'laptop', 5299000, 11, 'axioo-hype-5.jpg'],
            ['Samsung Galaxy A14 5G', 'samsung-galaxy-a14-5g', 'Samsung', 'hp', 2399000, 18, 'samsung-galaxy-a14-5g.jpg'],
            ['Lenovo ThinkPad X9-14 Gen 83ID', 'lenovo-thinkpad-x9-14-gen-83id', 'Lenovo', 'laptop', 24999000, 7, 'lenovo-thinkpad-x9-14-gen-83id.jpg'],
            ['Vivo V25e', 'vivo-v25e', 'Vivo', 'hp', 3499000, 14, 'vivo-v25e.jpg'],
            ['Lenovo V14 G3', 'lenovo-v14-g3', 'Lenovo', 'laptop', 6499000, 3, 'lenovo-v14-g3.jpg'],
            ['Vivo Y400', 'vivo-y400', 'Vivo', 'hp', 3499000, 10, 'vivo-y400.jpg'],
            ['Acer Aspire Lite AL14', 'acer-aspire-lite-al14', 'Acer', 'laptop', 5499000, 17, 'acer-aspire-lite-al14.jpg'],
            ['Infinix Hot 60 Pro', 'infinix-hot-60-pro', 'Infinix', 'hp', 2799000, 6, 'infinix-hot-60-pro.jpg'],
            ['Acer Aspire Go', 'acer-aspire-go', 'Acer', 'laptop', 4999000, 13, 'acer-aspire-go.jpg'],
            ['Oppo A6s', 'oppo-a6s', 'Oppo', 'hp', 1999000, 20, 'oppo-a6s.jpg'],
            ['Lenovo LOQ 15A RP10E', 'lenovo-loq-15a-rp10e', 'Lenovo', 'laptop', 15999000, 9, 'lenovo-loq-15a-rp10e.jpg'],
            ['Vivo V19', 'vivo-v19', 'Vivo', 'hp', 3999000, 16, 'vivo-v19.jpg'],
            ['Acer Nitro 5 AN515', 'acer-nitro-5-an515', 'Acer', 'laptop', 13999000, 5, 'acer-nitro-5-an515.jpg'],
            ['Infinix GT 30 Pro', 'infinix-gt-30-pro', 'Infinix', 'hp', 4299000, 12, 'infinix-gt-30-pro.jpg'],
            ['ASUS Vivobook 14 Series Intel', 'asus-vivobook-14-series-intel', 'ASUS', 'laptop', 8499000, 19, 'asus-vivobook-14-series-intel.jpg'],
            ['Vivo Y100', 'vivo-y100', 'Vivo', 'hp', 2999000, 8, 'vivo-y100.jpg'],
            ['Axioo Pongo 735', 'axioo-pongo-735', 'Axioo', 'laptop', 11999000, 15, 'axioo-pongo-735.jpg'],
            ['Samsung Galaxy A55 5G', 'samsung-galaxy-a55-5g', 'Samsung', 'hp', 6299000, 4, 'samsung-galaxy-a55-5g.jpg'],
            ['Vivo Y21T', 'vivo-y21t', 'Vivo', 'hp', 1999000, 11, 'vivo-y21t.jpg'],
            ['Samsung Galaxy A26 5G', 'samsung-galaxy-a26-5g', 'Samsung', 'hp', 3799000, 18, 'samsung-galaxy-a26-5g.jpg'],
            ['Samsung Galaxy A53 5G', 'samsung-galaxy-a53-5g', 'Samsung', 'hp', 4299000, 7, 'samsung-galaxy-a53-5g.jpg'],
            ['Oppo A18', 'oppo-a18', 'Oppo', 'hp', 1699000, 14, 'oppo-a18.jpeg'],
        ];

        foreach ($data as [$name, $slug, $brand, $category, $price, $stock, $image]) {
            Product::updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'brand' => $brand,
                'category' => $category,
                'price' => $price,
                'stock' => $stock,
                'image' => $image,
                'description' => $deskripsi[$category],
                'is_active' => true,
            ]);
        }
    }
}
