@extends('layouts.shop')

@section('title', 'Teja Store: Laptop dan HP baru, stok terlihat jelas')

@section('content')
<div x-data="shop(@js($payload))" @keydown.escape.window="close()">

    {{-- ===== Hero ===== --}}
    <section class="bg-deep text-primary-foreground">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-14 sm:px-6 lg:grid-cols-2 lg:py-20">
            <div>
                <h1 class="animate-rise text-4xl font-extrabold leading-[1.1] tracking-tight sm:text-5xl">
                    Laptop dan HP baru, harga jelas, stok terlihat.
                </h1>
                <p class="animate-rise mt-5 max-w-md text-lg text-primary-foreground/80" style="animation-delay: 120ms">
                    {{ $countLaptop }} laptop dan {{ $countHp }} HP dari Acer, ASUS, Lenovo, Samsung, Oppo, Vivo, dan lainnya. Pilih, masukkan keranjang, selesai dalam satu kali checkout.
                </p>
                <div class="animate-rise mt-8 flex flex-wrap gap-3" style="animation-delay: 240ms">
                    <x-ui.button variant="accent" size="lg" x-on:click="setCategory('laptop')">Lihat laptop</x-ui.button>
                    <x-ui.button size="lg" class="bg-primary-foreground/15 text-primary-foreground shadow-none hover:bg-primary-foreground/25" x-on:click="setCategory('hp')">Lihat HP</x-ui.button>
                </div>
            </div>

            {{-- Satu momen visual: tiga produk asli bertumpuk --}}
            <div class="animate-rise relative mx-auto h-72 w-full max-w-md sm:h-80" style="animation-delay: 200ms">
                @foreach ($featured as $i => $p)
                    @php
                        $pos = [
                            'left-0 top-6 -rotate-6 w-52 sm:w-60',
                            'right-0 top-0 rotate-6 w-40 sm:w-44',
                            'left-1/4 bottom-0 rotate-1 w-48 sm:w-52',
                        ][$i] ?? 'left-0 top-0 w-40';
                    @endphp
                    <div class="absolute {{ $pos }} rounded-2xl bg-card p-3 shadow-2xl ring-1 ring-black/5">
                        <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="aspect-square w-full rounded-xl bg-muted object-contain" loading="eager">
                        <p class="mt-2 truncate text-xs font-bold text-foreground">{{ $p->name }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Katalog ===== --}}
    <section id="produk" class="mx-auto max-w-7xl scroll-mt-20 px-4 pt-10 sm:px-6">
        <div class="grid gap-3 sm:grid-cols-2">
            <button type="button" @click="category = category === 'laptop' ? 'semua' : 'laptop'"
                    :class="category === 'laptop' ? 'border-laptop bg-laptop/10 ring-2 ring-laptop/30' : 'border-border bg-card hover:border-laptop/60'"
                    class="flex items-center justify-between rounded-xl border p-5 text-left transition-all duration-200 active:scale-[0.99]">
                <span><span class="block text-lg font-extrabold text-laptop">Laptop</span><span class="text-sm text-muted-foreground">{{ $countLaptop }} produk untuk kuliah, kerja, dan gaming</span></span>
                <span class="text-sm font-semibold text-laptop" x-text="category === 'laptop' ? 'Dipilih' : 'Pilih'"></span>
            </button>
            <button type="button" @click="category = category === 'hp' ? 'semua' : 'hp'"
                    :class="category === 'hp' ? 'border-hp bg-hp/10 ring-2 ring-hp/30' : 'border-border bg-card hover:border-hp/60'"
                    class="flex items-center justify-between rounded-xl border p-5 text-left transition-all duration-200 active:scale-[0.99]">
                <span><span class="block text-lg font-extrabold text-hp">HP</span><span class="text-sm text-muted-foreground">{{ $countHp }} produk dari harga terjangkau sampai flagship</span></span>
                <span class="text-sm font-semibold text-hp" x-text="category === 'hp' ? 'Dipilih' : 'Pilih'"></span>
            </button>
        </div>

        {{-- Filter --}}
        <div class="sticky top-16 z-30 -mx-4 mt-6 border-b border-border/70 bg-background/90 px-4 py-3 backdrop-blur sm:-mx-6 sm:px-6">
            <div class="grid gap-2 sm:grid-cols-[1fr_auto_auto_auto]">
                <x-ui.input type="search" x-model.debounce.150ms="q" placeholder="Cari nama atau merek, misalnya Vivobook" aria-label="Cari produk" />
                <select x-model="brand" aria-label="Filter merek" class="h-10 rounded-lg border border-border bg-card px-3 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    <option value="">Semua merek</option>
                    @foreach ($brands as $b)<option value="{{ $b }}">{{ $b }}</option>@endforeach
                </select>
                <select x-model="sort" aria-label="Urutkan" class="h-10 rounded-lg border border-border bg-card px-3 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                    <option value="rekomendasi">Rekomendasi</option>
                    <option value="termurah">Harga termurah</option>
                    <option value="termahal">Harga termahal</option>
                    <option value="nama">Nama A-Z</option>
                </select>
                <x-ui.button variant="outline" x-on:click="reset()">Atur ulang</x-ui.button>
            </div>
            <p class="mt-2 text-sm text-muted-foreground" aria-live="polite"><span class="font-bold text-foreground" x-text="filtered.length"></span> produk ditampilkan</p>
        </div>

        {{-- Grid produk --}}
        <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-5 md:grid-cols-3 lg:grid-cols-4">
            <template x-for="p in products" :key="p.id">
                <article x-show="visible(p.id)" :style="`order: ${position(p.id)}`"
                         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition duration-150 ease-in" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                         class="group flex flex-col overflow-hidden rounded-xl border border-border bg-card shadow-sm transition-shadow duration-300 hover:shadow-lg">
                    <button type="button" @click="open(p)" class="relative block aspect-square overflow-hidden bg-muted text-left" :aria-label="'Lihat detail ' + p.name">
                        <img :src="p.image" :alt="p.name" loading="lazy" class="size-full object-contain p-4 transition-transform duration-500 ease-out group-hover:scale-105" :class="p.stock <= 0 && 'opacity-50 grayscale'">
                        <span class="absolute left-2 top-2 rounded-full px-2.5 py-0.5 text-xs font-bold"
                              :class="p.category === 'laptop' ? 'bg-laptop text-white' : 'bg-hp text-white'" x-text="p.category === 'laptop' ? 'Laptop' : 'HP'"></span>
                        <span x-show="p.stock <= 0" class="absolute inset-x-0 bottom-0 bg-deep/85 py-1 text-center text-xs font-bold text-primary-foreground">Stok habis</span>
                    </button>
                    <div class="flex flex-1 flex-col gap-1 p-3 sm:p-4">
                        <p class="text-xs font-semibold text-muted-foreground" x-text="p.brand"></p>
                        <h3 class="line-clamp-2 min-h-10 text-sm font-bold leading-snug" x-text="p.name"></h3>
                        <p class="mt-1 text-base font-extrabold text-primary" x-text="p.price_text"></p>
                        <p class="text-xs" :class="p.stock <= 0 ? 'text-destructive font-semibold' : (p.stock <= 5 ? 'text-warning font-semibold' : 'text-muted-foreground')"
                           x-text="p.stock <= 0 ? 'Stok habis' : (p.stock <= 5 ? 'Sisa ' + p.stock + ' unit' : 'Stok tersedia')"></p>
                        <button type="button" @click="add(p)" :disabled="p.stock <= 0 || busy === p.id"
                                class="mt-3 inline-flex h-9 items-center justify-center rounded-lg bg-primary text-sm font-semibold text-primary-foreground transition-all duration-200 hover:bg-primary/90 active:scale-95 disabled:cursor-not-allowed disabled:opacity-50">
                            <span x-text="busy === p.id ? 'Menambahkan...' : (p.stock <= 0 ? 'Habis' : 'Tambah ke keranjang')"></span>
                        </button>
                    </div>
                </article>
            </template>
        </div>

        <div x-cloak x-show="filtered.length === 0" x-transition.opacity class="mt-6 rounded-xl border border-dashed border-border bg-card p-10 text-center">
            <p class="text-lg font-bold">Tidak ada produk yang cocok</p>
            <p class="mt-1 text-sm text-muted-foreground">Coba kata kunci lain atau hapus filter merek dan kategori.</p>
            <x-ui.button class="mt-4" x-on:click="reset()">Tampilkan semua produk</x-ui.button>
        </div>
    </section>

    {{-- ===== Modal detail produk ===== --}}
    <div x-cloak x-show="selected" x-effect="document.body.classList.toggle('overflow-hidden', !!selected)" class="fixed inset-0 z-50 grid place-items-end sm:place-items-center" role="dialog" aria-modal="true">
        <div x-show="selected" x-transition.opacity.duration.250ms @click="close()" class="absolute inset-0 bg-deep/60 backdrop-blur-sm"></div>

        <div x-show="selected"
             x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-y-8 opacity-0 sm:scale-95" x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
             x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="translate-y-6 opacity-0"
             class="relative grid max-h-[92vh] w-full max-w-3xl overflow-y-auto rounded-t-2xl bg-card shadow-2xl sm:grid-cols-2 sm:rounded-2xl">
            <button type="button" @click="close()" class="absolute right-3 top-3 z-10 grid size-9 place-items-center rounded-full bg-card/90 text-lg shadow transition-transform hover:scale-110" aria-label="Tutup">&times;</button>
            <div class="grid aspect-square place-items-center bg-muted p-6">
                <img :src="selected?.image" :alt="selected?.name" class="max-h-full max-w-full object-contain">
            </div>
            <div class="flex flex-col gap-3 p-6">
                <div class="flex items-center gap-2">
                    <span class="rounded-full px-2.5 py-0.5 text-xs font-bold text-white" :class="selected?.category === 'laptop' ? 'bg-laptop' : 'bg-hp'" x-text="selected?.category === 'laptop' ? 'Laptop' : 'HP'"></span>
                    <span class="text-sm font-semibold text-muted-foreground" x-text="selected?.brand"></span>
                </div>
                <h2 class="text-2xl font-extrabold leading-tight" x-text="selected?.name"></h2>
                <p class="text-3xl font-extrabold text-primary" x-text="selected?.price_text"></p>
                <p class="text-sm leading-relaxed text-muted-foreground" x-text="selected?.description"></p>
                <p class="text-sm font-semibold" :class="selected?.stock <= 0 ? 'text-destructive' : 'text-success'" x-text="selected?.stock <= 0 ? 'Stok habis' : 'Stok: ' + selected?.stock + ' unit'"></p>

                <div class="mt-auto flex items-center gap-3 pt-2" x-show="selected?.stock > 0">
                    <div class="inline-flex h-11 items-center rounded-lg border border-border bg-background">
                        <button type="button" @click="qty = Math.max(1, qty - 1)" class="h-full w-10 text-lg transition-colors hover:bg-secondary" aria-label="Kurangi">&minus;</button>
                        <span class="w-8 text-center font-bold" x-text="qty"></span>
                        <button type="button" @click="qty = Math.min(selected.stock, qty + 1)" class="h-full w-10 text-lg transition-colors hover:bg-secondary" aria-label="Tambah">+</button>
                    </div>
                    <button type="button" @click="add(selected, qty)" :disabled="busy === selected?.id"
                            class="inline-flex h-11 flex-1 items-center justify-center rounded-lg bg-accent px-4 text-sm font-bold text-accent-foreground shadow-sm transition-all duration-200 hover:brightness-95 active:scale-95 disabled:opacity-60">
                        <span x-text="busy === selected?.id ? 'Menambahkan...' : 'Tambah ke keranjang'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
