<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\Rupiah;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::active()->orderBy('id')->get();

        // Data untuk Alpine.js (filter, urut, dan modal detail berjalan di browser).
        $payload = $products->map(fn (Product $p) => [
            'id' => $p->id,
            'name' => $p->name,
            'brand' => $p->brand,
            'category' => $p->category,
            'price' => $p->price,
            'price_text' => Rupiah::format($p->price),
            'stock' => $p->stock,
            'image' => $p->image_url,
            'description' => $p->description,
        ])->values();

        $featured = $products->whereIn('slug', [
            'asus-vivobook-ultra-15-oled',
            'oppo-reno12-pro',
            'acer-nitro-5-an515',
        ])->values();

        return view('shop.index', [
            'payload' => $payload,
            'brands' => $products->pluck('brand')->unique()->sort()->values(),
            'featured' => $featured,
            'countLaptop' => $products->where('category', 'laptop')->count(),
            'countHp' => $products->where('category', 'hp')->count(),
        ]);
    }
}
